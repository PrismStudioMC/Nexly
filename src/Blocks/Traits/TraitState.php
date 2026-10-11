<?php

namespace Nexly\Blocks\Traits;

use pocketmine\nbt\tag\ByteTag;
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
     * @return string[]
     */
    public function getEnabledTraits(): array
    {
        return $this->enabledTraits;
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
     * @param CompoundTag $nbt
     * @return self
     */
    public static function fromNBT(CompoundTag $nbt): self
    {
        $enums = [];

        foreach ($nbt->getValue() as $key => $tag) {
            if ($tag instanceof ByteTag && $tag->getValue() === 1) {
                $enum = TraitStateId::tryFrom($key);
                if ($enum !== null) {
                    $enums[] = $enum;
                }
            }
        }

        return new self(...$enums);
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setByte("cardinal_direction", (int) $this->isCardinal()) // Sécurité PHP 8
            ->setByte("facing_direction", (int) $this->isFacing());

        foreach ($this->enabledTraits as $stateValue) {
            $nbt->setByte($stateValue, 1);
        }

        return $nbt;
    }
}