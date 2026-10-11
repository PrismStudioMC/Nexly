<?php

namespace Nexly\Blocks\Mappers;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\MaterialInstancesBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\TransformationBlockComponent;
use Nexly\Blocks\Components\Types\BoxCollision;
use Nexly\Blocks\Components\Types\Material;
use Nexly\Blocks\Components\Types\MaterialRenderMethod;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\math\Vector3;
use RuntimeException;

class DoorBlockMapper extends BlockMapper
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
        $top = $states[StateNames::UPPER_BLOCK_BIT];
        $hinge = $states[StateNames::DOOR_HINGE_BIT];
        $open = $states[StateNames::OPEN_BIT];

        $expr = MolangHelper::and(
            MolangHelper::stateEquals(StateNames::MC_CARDINAL_DIRECTION, $dir),
            MolangHelper::stateEquals(StateNames::UPPER_BLOCK_BIT, $top),
            MolangHelper::stateEquals(StateNames::DOOR_HINGE_BIT, $hinge),
            MolangHelper::stateEquals(StateNames::OPEN_BIT, $open)
        );

        $permutation = Permutation::create($expr);

        $permutation->addComponent(new MaterialInstancesBlockComponent([
            new Material(
                texture: $builder->getName() . ($top ? "_upper" : "_lower"),
                renderMethod: MaterialRenderMethod::ALPHA_TEST_SINGLE_SIDED
            )
        ]));

        $box = $open
            ? new BoxCollision(new Vector3(-8, 0, 5), new Vector3(16, 16, 3))
            : new BoxCollision(new Vector3(-8, 0, -8), new Vector3(3, 16, 16));

        $permutation->addComponent(new CollisionBoxBlockComponent(true, [$box]));
        $permutation->addComponent(new SelectionBoxBlockComponent(true, [$box]));

        $permutation->addComponent(new TransformationBlockComponent(rotation: match ($dir) {
            StateValues::MC_CARDINAL_DIRECTION_NORTH => new Vector3(0, 0, 0),
            StateValues::MC_CARDINAL_DIRECTION_SOUTH => new Vector3(0, 180, 0),
            StateValues::MC_CARDINAL_DIRECTION_WEST => new Vector3(0, 90, 0),
            StateValues::MC_CARDINAL_DIRECTION_EAST => new Vector3(0, 270, 0),
            default => throw new RuntimeException("Invalid direction")
        }));

        return $permutation;
    }
}
