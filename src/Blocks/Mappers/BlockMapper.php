<?php

namespace Nexly\Blocks\Mappers;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Permutations\Permutation;
use pocketmine\block\Block;

abstract class BlockMapper
{
    public function __construct()
    {
    }

    /**
     * @param Builder $builder
     * @param Block $block
     * @param array $properties
     * @param array $currentStates
     * @return void
     */
    public function apply(Builder $builder, Block $block, array $properties, array $currentStates = []): void
    {
        if (empty($properties)) {
            $builder->addPermutation($this->map($builder, $block, $currentStates));
            return;
        }

        $property = array_shift($properties);
        $propertyName = $property->getName();

        foreach ($property->getValues() as $value) {
            $newStates = $currentStates;
            $newStates[$propertyName] = $value;

            $this->apply($builder, $block, $properties, $newStates);
        }
    }

    abstract public function map(Builder $builder, Block $block, array $states): Permutation;
}
