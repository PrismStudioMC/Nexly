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
use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\math\Vector3;
use RuntimeException;

class FenceGateBlockMapper extends BlockMapper
{
    /**
     * @param Builder $builder
     * @param Block $block
     * @param array $states
     * @return Permutation
     */
    public function map(Builder $builder, Block $block, array $states): Permutation
    {
        $dir = $states[StateNames::MC_CARDINAL_DIRECTION];
        $inWall = $states[StateNames::IN_WALL_BIT];
        $open = $states[StateNames::OPEN_BIT];

        $expr = MolangHelper::and(
            MolangHelper::stateEquals(StateNames::MC_CARDINAL_DIRECTION, $dir),
            MolangHelper::stateEquals(StateNames::IN_WALL_BIT, $inWall),
            MolangHelper::stateEquals(StateNames::OPEN_BIT, $open)
        );
        return Permutation::create($expr)
            ->addComponent(new CollisionBoxBlockComponent(!$open, [BoxCollision::FENCE_GATE()]))
            ->addComponent(new SelectionBoxBlockComponent(true, [BoxCollision::FENCE_GATE()]))
            ->addComponent(new TransformationBlockComponent(
                rotation: match ($dir) {
                    StateValues::MC_CARDINAL_DIRECTION_NORTH => new Vector3(0, 0, 0),
                    StateValues::MC_CARDINAL_DIRECTION_SOUTH => new Vector3(0, 180, 0),
                    StateValues::MC_CARDINAL_DIRECTION_EAST => new Vector3(0, 270, 0),
                    StateValues::MC_CARDINAL_DIRECTION_WEST => new Vector3(0, 90, 0),
                    default => throw new RuntimeException("Invalid direction")
                },
                translation: new Vector3(0, $inWall ? -0.187 : 0, 0)
            ));
    }
}
