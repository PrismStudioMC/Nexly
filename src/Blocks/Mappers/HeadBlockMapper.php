<?php

namespace Nexly\Blocks\Mappers;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\TransformationBlockComponent;
use Nexly\Blocks\Components\Types\BoxCollision;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\math\Vector3;

class HeadBlockMapper extends BlockMapper
{
    /**
     * @param Builder $builder
     * @param Block $block
     * @param array $states
     * @return Permutation
     */
    public function map(Builder $builder, Block $block, array $states): Permutation
    {
        $dir = $states[StateNames::FACING_DIRECTION];
        $rot = $states[StateNames::ROTATION];

        $translation = match ($dir) {
            2 => new Vector3(0, 0.25, 0.24),  // North
            3 => new Vector3(0, 0.25, -0.24), // South
            4 => new Vector3(0.24, 0.25, 0),  // West
            5 => new Vector3(-0.24, 0.25, 0), // East
            default => new Vector3(0, 0, 0)   // UP (Sol)
        };

        $expr = MolangHelper::and(
            MolangHelper::stateEquals(StateNames::FACING_DIRECTION, $dir),
            MolangHelper::stateEquals(StateNames::ROTATION, $rot)
        );
        return Permutation::create($expr)
            ->addComponent(new CollisionBoxBlockComponent(true, [BoxCollision::MOBHEAD()]))
            ->addComponent(new SelectionBoxBlockComponent(true, [BoxCollision::MOBHEAD()]))
            ->addComponent(new TransformationBlockComponent(translation: $translation));
    }
}
