<?php

namespace Nexly\Items\Components\DataDriven\Types;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class DurabilityThreshold
{
    public function __construct(
        private readonly int $durability,
        private readonly ?string $sound = null,
        private readonly ?string $particle = null,
    ) {
    }

    /**
     * @return int
     */
    public function getDurability(): int
    {
        return $this->durability;
    }

    /**
     * @return string|null
     */
    public function getSound(): ?string
    {
        return $this->sound;
    }

    /**
     * @return string|null
     */
    public function getParticle(): ?string
    {
        return $this->particle;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("durability", new IntTag($this->durability));

        if ($this->sound !== null) {
            $nbt->setTag("sound", new StringTag($this->sound));
        }

        if ($this->particle !== null) {
            $nbt->setTag("particle", new StringTag($this->particle));
        }

        return $nbt;
    }
}
