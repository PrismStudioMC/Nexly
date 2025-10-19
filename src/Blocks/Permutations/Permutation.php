<?php

namespace Nexly\Blocks\Permutations;

use Nexly\Blocks\Components\BlockComponent;
use pocketmine\nbt\tag\CompoundTag;

final class Permutation
{
    /*** @var BlockComponent[] */
    private array $components = [];

    public function __construct(
        private string $condition
    ) {
    }

    /**
     * @param string $condition
     * @return self
     */
    public static function create(string $condition = ""): Permutation
    {
        return new self($condition);
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     * @return Permutation
     */
    public function setCondition(string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    /**
     * @return array
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @param BlockComponent $component
     * @return $this
     */
    public function addComponent(BlockComponent $component): self
    {
        $this->components[$component->getName()] = $component;
        return $this;
    }

    /**
     * Returns the permutation in the correct NBT format supported by the client.
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $components = CompoundTag::create();
        foreach ($this->components as $name => $component) {
            $components->setTag($name, $component->toNBT());
        }

        return CompoundTag::create()
            ->setString("condition", $this->condition)
            ->setTag("components", $components);
    }
}
