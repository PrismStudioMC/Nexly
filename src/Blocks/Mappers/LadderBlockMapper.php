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
use RuntimeException;

class LadderBlockMapper extends BlockMapper
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

        $rotation = match ($dir) {
            2 => new Vector3(0, 0, 0),
            3 => new Vector3(0, 180, 0),
            4 => new Vector3(0, 90, 0),
            5 => new Vector3(0, 270, 0),
            default => throw new RuntimeException("Invalid direction")
        };

        $expr = MolangHelper::stateEquals(StateNames::FACING_DIRECTION, $dir);
        return Permutation::create($expr)
            ->addComponent(new CollisionBoxBlockComponent(true, [BoxCollision::LADDER()]))
            ->addComponent(new SelectionBoxBlockComponent(true, [BoxCollision::LADDER()]))
            ->addComponent(new TransformationBlockComponent(rotation: $rotation));
    }
}
