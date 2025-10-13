<?php

declare(strict_types=1);

namespace Nexly\Listener;

use Nexly\Blocks\Vanilla\NexlyLadder;
use pocketmine\block\Ladder;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\handler\InGamePacketHandler;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\PlayerAction;
use pocketmine\network\mcpe\protocol\types\PlayerAuthInputFlags;
use pocketmine\network\mcpe\protocol\types\PlayerBlockActionStopBreak;
use pocketmine\network\mcpe\protocol\types\PlayerBlockActionWithBlockInfo;
use pocketmine\network\PacketHandlingException;
use pocketmine\player\Player;
use pocketmine\player\SurvivalBlockBreakHandler;
use WeakMap;

final class LadderClimbingListener implements Listener
{
    /**
     * Called when a player joins the server to initialize climbing ability.
     *
     * @param PlayerJoinEvent $ev
     * @return void
     */
    public function onLogin(PlayerJoinEvent $ev): void
    {
        $ev->getPlayer()->setCanClimb(false); // Disable climbing by default
    }

    /**
     * @param DataPacketReceiveEvent $ev
     * @return void
     */
    public function onMove(DataPacketReceiveEvent $ev): void
    {
        $origin = $ev->getOrigin();
        $packet = $ev->getPacket();

        $handler = $origin->getHandler();
        if(!$handler instanceof InGamePacketHandler) {
            return; // Not an in-game packet handler
        }

        if(!$packet instanceof PlayerAuthInputPacket) {
            return; // Not a PlayerAuthInputPacket
        }

        $player = $origin->getPlayer();
        if($player == null || !$player->isConnected()) {
            return; // Player is not connected
        }

        $now = $packet->getPosition()->subtract(0, $player->getEyeHeight(), 0);
        if($player->getPosition()->distance($now) > 2) {
            return; // Player moved too far
        }

        $block = $player->getWorld()->getBlock($now);
        if(!$block instanceof Ladder) {
            if($player->canClimbWalls()) {
                $player->setCanClimbWalls(false);
            }
            return;
        }

        if(!$player->canClimbWalls()) {
            $player->setCanClimbWalls();
        }

        if($player->isSneaking()) {
            $player->setMotion(Vector3::zero());
            return; // Stop climbing when sneaking
        }

        if(!$packet->getInputFlags()->get(PlayerAuthInputFlags::HORIZONTAL_COLLISION)) {
            return; // Not colliding with a wall
        }

        if($block instanceof NexlyLadder) {
            $speed = $block->getClimbSpeed();

            $upBlock = $block->getSide(Facing::UP);
            if(!$upBlock instanceof Ladder) {
                $speed /= 1.5;
            }

            $this->addVelocity($player, $now, $speed);
        }
    }

    /**
     * Applies climbing velocity to the player if they are on a ladder.
     *
     * @param Player $player
     * @param Vector3 $now
     * @param float $climbSpeed
     * @return void
     */
    public function addVelocity(Player $player, Vector3 $now, float $climbSpeed): void
    {
        if($climbSpeed <= 0) {
            return; // No climbing speed defined
        }

        if(!$player->isConnected()){
            return;
        }

        $pos = $player->getPosition();
        if($pos->y >= $now->y) {
            return; // Player is moving downwards
        }

        $player->setMotion(new Vector3(0, $climbSpeed, 0));
    }
}
