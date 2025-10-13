<?php

namespace Nexly\Blocks\Vanilla;

use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\Ladder;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\handler\InGamePacketHandler;
use pocketmine\player\Player;

class NexlyLadder extends Ladder
{
    private float $climbSpeed = 0.15; // Default climb speed

    public function __construct(BlockIdentifier $idInfo, string $name, float $climbSpeed, BlockTypeInfo $typeInfo)
    {
        parent::__construct($idInfo, $name, $typeInfo);
        $this->climbSpeed = $climbSpeed;
    }

    /**
     * Determines the speed at which a player can climb this ladder.
     *
     * @return float
     */
    public function getClimbSpeed(): float
    {
        return $this->climbSpeed;
    }
}