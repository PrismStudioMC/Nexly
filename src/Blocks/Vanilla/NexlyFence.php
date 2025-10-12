<?php

namespace Nexly\Blocks\Vanilla;

use pocketmine\block\Block;
use pocketmine\block\Fence;
use pocketmine\block\FenceGate;
use pocketmine\block\utils\SupportType;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\math\Facing;

/**
 * Class NexlyFence
 *
 * A custom fence block that extends the base Fence class and includes additional functionality
 * for reading state from the world, recalculating connections, and handling nearby block changes.
 *
 * @package Nexly\Blocks\Vanilla
 *
 * Minecraft does not allow us to reproduce identical fences.
 * In certain patterns, you will be able to pass through them because we cannot create collision boxes other than squares/rectangles.
 * @deprecated
 */
class NexlyFence extends Fence
{
    /**
     * Reads the state of the block from the world and updates its connections accordingly.
     *
     * @return Block
     */
    public function readStateFromWorld(): Block
    {
        return $this;
    }

    /**
     * Recalculates the connections of this fence to adjacent blocks.
     *
     * @return bool
     */
    protected function recalculateConnections(): bool
    {
        $changed = 0;

        foreach (Facing::HORIZONTAL as $facing) {
            $block = $this->getSide($facing);
            if ($block instanceof Fence || $block instanceof FenceGate || $block->getSupportType(Facing::opposite($facing)) === SupportType::FULL) {
                if (!isset($this->connections[$facing])) {
                    $this->connections[$facing] = $facing;
                    $changed++;
                }
            } elseif (isset($this->connections[$facing])) {
                unset($this->connections[$facing]);
                $changed++;
            }
        }
        return $changed > 0;
    }

    /**
     * Describes the block's state focusing solely on its connections.
     *
     * @param RuntimeDataDescriber $w
     * @return void
     */
    protected function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        foreach ($this->connections as $facing => $zebi) {
            $this->connections[$facing] = $facing;
        }

        $w->horizontalFacingFlags($this->connections);
    }

    /**
     * Called when a nearby block changes, triggering a recalculation of connections.
     *
     * @return void
     */
    public function onNearbyBlockChange(): void
    {
        if ($this->recalculateConnections()) {
            $this->position->getWorld()->setBlock($this->position, $this);
        }
    }
}
