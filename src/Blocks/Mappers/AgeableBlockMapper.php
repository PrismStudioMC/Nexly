<?php

namespace Nexly\Blocks\Mappers;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\MaterialInstancesBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\Types\Material;
use Nexly\Blocks\Components\Types\RenderMethod;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;

class AgeableBlockMapper extends BlockMapper
{
    /**
     * @param Builder $builder
     * @param Block $block
     * @param array $states
     * @return Permutation
     */
    public function map(Builder $builder, Block $block, array $states): Permutation
    {
        $age = $states[StateNames::AGE];

        return Permutation::create(MolangHelper::stateEquals(StateNames::AGE, $age))
            ->addComponent(SelectionBoxBlockComponent::fromCrops($block, $age))
            ->addComponent(new MaterialInstancesBlockComponent([
                new Material($builder->getName() . "_{$age}", renderMethod: RenderMethod::ALPHA_TEST)
            ]));
    }
}
