<?php

namespace Nexly\Blocks\Traits;

use pocketmine\nbt\tag\CompoundTag;

class TraitState
{
    /** @var string[] */
    protected array $enabledTraits;

    /**
     * @param TraitStateId ...$states
     */
    public function __construct(TraitStateId ...$states)
    {
        $this->enabledTraits = array_values(array_unique(
            array_map(fn (TraitStateId $state) => $state->value, $states)
        ));
    }

    /**
     * @return array
     */
    public function getEnabledTraits(): array
    {
        return $this->enabledTraits;
    }

    /**
     * @param array $values
     * @return self
     */
    public static function fromArray(array $values): self
    {
        $enums = array_map(fn ($v) => TraitStateId::from($v), $values);
        return new self(...$enums);
    }

    /**
     * @return bool
     */
    public function isCardinal(): bool
    {
        return in_array(TraitStateId::CARDINAL_DIRECTION->value, $this->enabledTraits, true);
    }

    /**
     * @return bool
     */
    public function isFacing(): bool
    {
        return in_array(TraitStateId::FACING_DIRECTION->value, $this->enabledTraits, true);
    }

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        $names = [];
        foreach ($this->enabledTraits as $stateValue) {
            if ($stateValue === TraitStateId::CORNER_AND_CARDINAL_DIRECTION->value) {
                $names[] = 'minecraft:corner';
                $names[] = 'minecraft:cardinal_direction';
            } else {
                $names[] = 'minecraft:' . $stateValue;
            }
        }
        return array_values(array_unique($names));
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setByte("cardinal_direction", $this->isCardinal())
            ->setByte("facing_direction", $this->isFacing());

        foreach ($this->enabledTraits as $stateValue) {
            $nbt->setByte($stateValue, 1);
        }

        return $nbt;
    }
}
