<?php

namespace Nexly\Blocks\Mappers;

use Nexly\Blocks\BlockBuilder as Builder;
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

class LeverBlockMapper extends BlockMapper
{
    public function map(Builder $builder, Block $block, array $states): Permutation
    {
        $dir = $states[StateNames::LEVER_DIRECTION];
        $open = $states[StateNames::OPEN_BIT];

        $isVertical = in_array($dir, [
            StateValues::LEVER_DIRECTION_UP_NORTH_SOUTH,
            StateValues::LEVER_DIRECTION_UP_EAST_WEST,
            StateValues::LEVER_DIRECTION_DOWN_NORTH_SOUTH,
            StateValues::LEVER_DIRECTION_DOWN_EAST_WEST
        ]);

        $selectionBox = $isVertical
            ? new BoxCollision(new Vector3(-4.0, 0.0, -4.0), new Vector3(8.0, 10.0, 8.0))
            : new BoxCollision(new Vector3(-3.0, 0.0, -4.0), new Vector3(6.0, 6.0, 8.0));

        $rotation = match ($dir) {
            StateValues::LEVER_DIRECTION_UP_NORTH_SOUTH => new Vector3(0, 0, 0),
            StateValues::LEVER_DIRECTION_UP_EAST_WEST => new Vector3(0, 90, 0),
            StateValues::LEVER_DIRECTION_DOWN_NORTH_SOUTH => new Vector3(180, 0, 0),
            StateValues::LEVER_DIRECTION_DOWN_EAST_WEST => new Vector3(180, 90, 0),
            StateValues::LEVER_DIRECTION_NORTH => new Vector3(90, 180, 0),
            StateValues::LEVER_DIRECTION_SOUTH => new Vector3(90, 0, 0),
            StateValues::LEVER_DIRECTION_EAST => new Vector3(90, 90, 0),
            StateValues::LEVER_DIRECTION_WEST => new Vector3(90, 270, 0),
            default => throw new RuntimeException("Invalid lever direction")
        };

        $expr = MolangHelper::and(
            MolangHelper::stateEquals(StateNames::LEVER_DIRECTION, $dir),
            MolangHelper::stateEquals(StateNames::OPEN_BIT, $open)
        );
        return Permutation::create($expr)
            ->addComponent(new SelectionBoxBlockComponent(true, [$selectionBox]))
            ->addComponent(new TransformationBlockComponent(rotation: $rotation));
    }
}
