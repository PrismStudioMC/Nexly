<?php

namespace Nexly\Blocks\Mappers;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\MaterialInstancesBlockComponent;
use Nexly\Blocks\Components\TransformationBlockComponent;
use Nexly\Blocks\Components\Types\Material;
use Nexly\Blocks\Components\Types\MaterialRenderMethod;
use Nexly\Blocks\Components\Types\MaterialTarget;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\math\Vector3;
use RuntimeException;

class WoodBlockMapper extends BlockMapper
{
    /**
     * @param Builder $builder
     * @param Block $block
     * @param array $states
     * @return Permutation
     */
    public function map(Builder $builder, Block $block, array $states): Permutation
    {
        $axis = $states[StateNames::PILLAR_AXIS];

        return Permutation::create(MolangHelper::stateEquals(StateNames::PILLAR_AXIS, $axis))
            ->addComponent(new TransformationBlockComponent(rotation: match ($axis) {
                StateValues::PILLAR_AXIS_X => new Vector3(0, 0, 90),
                StateValues::PILLAR_AXIS_Y => new Vector3(0, 0, 0),
                StateValues::PILLAR_AXIS_Z => new Vector3(90, 0, 0),
                default => throw new RuntimeException("Invalid axis"),
            }))
            ->addComponent(new MaterialInstancesBlockComponent([
                new Material(
                    texture: $builder->getName() . "_top",
                    target: MaterialTarget::UP,
                    renderMethod: MaterialRenderMethod::ALPHA_TEST_SINGLE_SIDED
                ),
                new Material(
                    texture: $builder->getName() . "_top",
                    target: MaterialTarget::DOWN,
                    renderMethod: MaterialRenderMethod::ALPHA_TEST_SINGLE_SIDED
                ),
                new Material(
                    texture: $builder->getName() . "_side",
                    target: MaterialTarget::ALL,
                    renderMethod: MaterialRenderMethod::ALPHA_TEST_SINGLE_SIDED
                )
            ]));
    }
}
