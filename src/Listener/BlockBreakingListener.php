<?php

declare(strict_types=1);

namespace Nexly\Listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\handler\InGamePacketHandler;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\PlayerAction;
use pocketmine\network\mcpe\protocol\types\PlayerBlockActionStopBreak;
use pocketmine\network\mcpe\protocol\types\PlayerBlockActionWithBlockInfo;
use pocketmine\network\PacketHandlingException;
use pocketmine\player\Player;
use pocketmine\player\SurvivalBlockBreakHandler;
use WeakMap;

use function in_array;

/**
 * TODO: PMMP Implement this ?
 *
 * @deprecated
 */
final class BlockBreakingListener implements Listener
{
    private const MAX_BLOCK_ACTIONS = 100;
    private const MAX_DIST_SQ = 10_000;

    /** @var WeakMap<Player, SurvivalBlockBreakHandler|null> */
    private WeakMap $breakHandlers;

    /** Réflexion mise en cache */
    private static ?\ReflectionProperty $rpPlayerBlockBreakHandler = null;
    private static ?\ReflectionProperty $rpHandlerLastBlockAttacked = null;
    private static ?\ReflectionProperty $rpAuthInputBlockActions   = null;

    public function __construct()
    {
        $this->breakHandlers = new WeakMap();
    }

    /**
     * Handle PlayerQuitEvent to clean up any associated block break handlers.
     *
     * @param PlayerQuitEvent $ev
     * @return void
     */
    public function onQuit(PlayerQuitEvent $ev): void
    {
        $p = $ev->getPlayer();
        if ($this->breakHandlers->offsetExists($p)) {
            $this->breakHandlers->offsetUnset($p);
        }
    }

    /**
     * Handle incoming DataPacketReceiveEvent to process PlayerAuthInputPacket and PlayerActionPacket.
     *
     * @param DataPacketReceiveEvent $ev
     * @return void
     */
    public function onInbound(DataPacketReceiveEvent $ev): void
    {
        $origin  = $ev->getOrigin();
        $handler = $origin->getHandler();
        if (!$handler instanceof InGamePacketHandler) {
            return;
        }

        $player = $origin->getPlayer();
        if ($player === null) {
            return;
        }

        $pk = $ev->getPacket();

        if ($pk instanceof PlayerAuthInputPacket) {
            $this->handleAuthInput($player, $handler, $pk);
            return;
        }

        if ($pk instanceof PlayerActionPacket) {
            $this->handleAction(
                $player,
                $handler,
                $pk->action,
                $pk->blockPosition ?? new BlockPosition(0, 0, 0),
                $pk->face ?? Facing::DOWN
            );
        }
    }

    /**
     * Handle a PlayerAuthInputPacket, processing its block actions and updating block break progress.
     *
     * @param Player $player
     * @param InGamePacketHandler $handler
     * @param PlayerAuthInputPacket $pk
     * @return void
     */
    private function handleAuthInput(Player $player, InGamePacketHandler $handler, PlayerAuthInputPacket $pk): void
    {
        $actions = $pk->getBlockActions();
        if ($actions !== null) {
            if (\count($actions) > self::MAX_BLOCK_ACTIONS) {
                throw new PacketHandlingException("Too many block actions in PlayerAuthInputPacket");
            }

            foreach ($actions as $action) {
                if ($action instanceof PlayerBlockActionStopBreak) {
                    $this->handleAction($player, $handler, $action->getActionType(), new BlockPosition(0, 0, 0), Facing::DOWN);
                } elseif ($action instanceof PlayerBlockActionWithBlockInfo) {
                    $this->handleAction($player, $handler, $action->getActionType(), $action->getBlockPosition(), $action->getFace());
                }
            }
        }

        if ($this->breakHandlers->offsetExists($player)) {
            /** @var SurvivalBlockBreakHandler|null $bh */
            $bh = $this->breakHandlers->offsetGet($player);
            if ($bh !== null) {
                $bh->update();
                if ($bh->getBreakProgress() >= 1) {
                    $player->breakBlock($bh->getBlockPos());
                    $this->breakHandlers->offsetUnset($player);
                }
            }
        }

        $this->clearAuthInputBlockActions($pk);
    }

    /**
     * Handle a block action from a PlayerActionPacket or PlayerAuthInputPacket.
     *
     * @param Player $player
     * @param InGamePacketHandler $handler
     * @param int $action
     * @param BlockPosition $bp
     * @param int $face
     * @return void
     */
    private function handleAction(
        Player $player,
        InGamePacketHandler $handler,
        int $action,
        BlockPosition $bp,
        int $face
    ): void {
        $pos = self::v($bp);
        $session = $player->getNetworkSession();

        $session->getLogger()->debug("PlayerAction $action on $pos (face: $face)");
        $last = $this->getLastBlockAttacked($handler);

        switch ($action) {
            case PlayerAction::START_BREAK:
            case PlayerAction::CONTINUE_DESTROY_BLOCK: {
                self::validateFacing($face);

                if ($last !== null && $bp->equals($last)) {
                    $session->getLogger()->debug("Ignoring action $action on $pos (already destroying this block)");
                    $this->syncBlocksNearby($player, $pos, $face);
                    break;
                }

                if (!$player->attackBlock($pos, $face)) {
                    $this->syncBlocksNearby($player, $pos, $face);
                } else {
                    // on capture le handler réel pour suivre la progression côté listener
                    $bh = $this->getPlayerBlockBreakHandler($player);
                    if ($bh !== null) {
                        // on clone pour décorréler de l’instance interne du joueur
                        $this->breakHandlers->offsetSet($player, clone $bh);
                    }
                }

                $this->setLastBlockAttacked($handler, $bp);
                break;
            }

            case PlayerAction::ABORT_BREAK:
            case PlayerAction::STOP_BREAK: {
                $player->stopBreakBlock($pos);
                $this->setLastBlockAttacked($handler, null);
                if ($this->breakHandlers->offsetExists($player)) {
                    $this->breakHandlers->offsetUnset($player);
                }
                break;
            }

            case PlayerAction::CRACK_BREAK: {
                self::validateFacing($face);
                $player->continueBreakBlock($pos, $face);
                $this->setLastBlockAttacked($handler, $bp);
                break;
            }

            case PlayerAction::CREATIVE_PLAYER_DESTROY_BLOCK: {
                if (!$player->isCreative()) {
                    $session->getLogger()->debug("Ignoring action $action on $pos (not creative)");
                    $this->syncBlocksNearby($player, $pos, $face);
                    break;
                }
                if (!$player->breakBlock($pos)) {
                    $this->syncBlocksNearby($player, $pos, $face);
                }
                break;
            }

            case PlayerAction::PREDICT_DESTROY_BLOCK: {
                if ($player->isCreative()) {
                    $session->getLogger()->debug("Ignoring action $action on $pos (creative)");
                    break;
                }

                if ($last === null) {
                    $session->getLogger()->debug("Ignoring action $action on $pos (no current block)");
                    $this->syncBlocksNearby($player, $pos, $face);
                    break;
                }

                if ($pos->distanceSquared($player->getLocation()) > self::MAX_DIST_SQ) {
                    $session->getLogger()->debug("Ignoring action $action on $pos (too far)");
                    break;
                }

                $target = $player->getWorld()->getBlock($pos);
                $hasBH  = $this->breakHandlers->offsetExists($player);
                /** @var SurvivalBlockBreakHandler|null $bh */
                $bh     = $hasBH ? $this->breakHandlers->offsetGet($player) : null;

                if (($bh === null) && !$target->getBreakInfo()->breaksInstantly()) {
                    $session->getLogger()->debug("Ignoring action $action on $pos (no BlockBreakHandler)");
                    $this->syncBlocksNearby($player, $pos, $face);
                    break;
                }

                if (($bh !== null) && !$target->getBreakInfo()->breaksInstantly()) {
                    $session->getLogger()->debug("Predict on $pos with progress=" . $bh->getBreakProgress());
                    $bh->update();
                    if ($bh->getBreakProgress() < 1) {
                        $session->getLogger()->debug("Ignoring predict (progress < 1)");
                        $this->syncBlocksNearby($player, $pos, $face);
                        break;
                    }
                }

                if (!$player->breakBlock($pos)) {
                    $this->syncBlocksNearby($player, $pos, $face);
                    break;
                }

                $this->setLastBlockAttacked($handler, null);
                break;
            }
        }

        $player->setUsingItem(false);
    }

    /**
     * Convert a BlockPosition to a Vector3.
     *
     * @param BlockPosition $bp
     * @return Vector3
     */
    private static function v(BlockPosition $bp): Vector3
    {
        return new Vector3($bp->getX(), $bp->getY(), $bp->getZ());
    }

    /**
     * Clear the blockActions property of the PlayerAuthInputPacket via reflection.
     *
     * @param PlayerAuthInputPacket $pk
     * @return void
     */
    private function clearAuthInputBlockActions(PlayerAuthInputPacket $pk): void
    {
        if (self::$rpAuthInputBlockActions === null) {
            $rc = new \ReflectionClass($pk);
            if (!$rc->hasProperty('blockActions')) {
                return;
            }
            self::$rpAuthInputBlockActions = $rc->getProperty('blockActions');
        }
        // pas de setAccessible (déprécié). Fonctionne si la prop n’est pas privée.
        self::$rpAuthInputBlockActions->setValue($pk, null);
    }

    /**
     * Get the blockBreakHandler property of the Player via reflection.
     *
     * @param Player $player
     * @return SurvivalBlockBreakHandler|null
     */
    private function getPlayerBlockBreakHandler(Player $player): ?SurvivalBlockBreakHandler
    {
        if (self::$rpPlayerBlockBreakHandler === null) {
            $rc = new \ReflectionClass($player);
            if (!$rc->hasProperty('blockBreakHandler')) {
                return null;
            }
            self::$rpPlayerBlockBreakHandler = $rc->getProperty('blockBreakHandler');
        }
        /** @var SurvivalBlockBreakHandler|null $bh */
        $bh = self::$rpPlayerBlockBreakHandler->getValue($player);
        return $bh;
    }

    /**
     * Get the lastBlockAttacked property of the InGamePacketHandler via reflection.
     *
     * @param InGamePacketHandler $handler
     * @return BlockPosition|null
     */
    private function getLastBlockAttacked(InGamePacketHandler $handler): ?BlockPosition
    {
        if (self::$rpHandlerLastBlockAttacked === null) {
            $rc = new \ReflectionClass($handler);
            if (!$rc->hasProperty('lastBlockAttacked')) {
                return null;
            }
            self::$rpHandlerLastBlockAttacked = $rc->getProperty('lastBlockAttacked');
        }
        /** @var BlockPosition|null $bp */
        $bp = self::$rpHandlerLastBlockAttacked->getValue($handler);
        return $bp;
    }

    /**
     * Set the lastBlockAttacked property of the InGamePacketHandler via reflection.
     *
     * @param InGamePacketHandler $handler
     * @param BlockPosition|null $bp
     * @return void
     */
    private function setLastBlockAttacked(InGamePacketHandler $handler, ?BlockPosition $bp): void
    {
        if (self::$rpHandlerLastBlockAttacked === null) {
            $rc = new \ReflectionClass($handler);
            if (!$rc->hasProperty('lastBlockAttacked')) {
                return;
            }
            self::$rpHandlerLastBlockAttacked = $rc->getProperty('lastBlockAttacked');
        }
        self::$rpHandlerLastBlockAttacked->setValue($handler, $bp);
    }

    /**
     * Sync the specified block and its adjacent blocks to the player if within range.
     *
     * @param Player $player
     * @param Vector3 $blockPos
     * @param int|null $face
     * @return void
     */
    private function syncBlocksNearby(Player $player, Vector3 $blockPos, ?int $face): void
    {
        if ($blockPos->distanceSquared($player->getLocation()) >= self::MAX_DIST_SQ) {
            return;
        }

        $blocks = $blockPos->sidesArray();
        if ($face !== null) {
            $sidePos = $blockPos->getSide($face);
            array_push($blocks, ...$sidePos->sidesArray());
        } else {
            $blocks[] = $blockPos;
        }

        foreach ($player->getWorld()->createBlockUpdatePackets($blocks) as $packet) {
            $player->getNetworkSession()->sendDataPacket($packet);
        }
    }

    /**
     * Validate that the facing value is one of the valid Facing constants.
     *
     * @throws PacketHandlingException
     */
    private static function validateFacing(int $facing): void
    {
        if (!in_array($facing, Facing::ALL, true)) {
            throw new PacketHandlingException("Invalid facing value $facing");
        }
    }
}
