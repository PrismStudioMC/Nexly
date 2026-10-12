<?php

namespace Nexly\Blocks\Mappers;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\MaterialInstancesBlockComponent;
use Nexly\Blocks\Components\Types\Material;
use Nexly\Blocks\Components\Types\RenderMethod;
use Nexly\Blocks\Components\Types\Target;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;

class FarmlandBlockMapper extends BlockMapper
{
    public function map(Builder $builder, Block $block, array $states): Permutation
    {
        $moisture = $states[StateNames::MOISTURIZED_AMOUNT];
        $isWet = $moisture > 0;

        $expr = MolangHelper::stateEquals(StateNames::MOISTURIZED_AMOUNT, $moisture);
        return Permutation::create($expr)
            ->addComponent(new MaterialInstancesBlockComponent([
                new Material(
                    texture: $builder->getName() . ($isWet ? "_wet" : "_dry"),
                    target: Target::UP,
                    renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED
                )
            ]));
    }
}
