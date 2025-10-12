<?php

namespace Nexly\Items\Components\Legacy\Types;

use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\nbt\tag\CompoundTag;

class LegacyEffect
{
    /**
     * LegacyEffect constructor.
     *
     * @param int $id
     * @param string $name
     * @param string $descriptionId
     * @param int $duration
     * @param int $amplifier
     * @param float $chance
     */
    public function __construct(
        private readonly int    $id,
        private readonly string $name,
        private readonly string $descriptionId,
        private readonly int    $duration = -1,
        private readonly int    $amplifier = 0,
        private readonly float $chance = 100.0
    ) {
    }

    /**
     * Create a LegacyEffect from an EffectInstance.
     *
     * @param EffectInstance $effectInstance
     * @return self
     */
    public static function from(EffectInstance $effectInstance): self
    {
        return new self(
            EffectIdMap::getInstance()->toId($effectInstance->getType()),
            $effectInstance->getType()->getName(),
            "", // Description ID is not available in EffectInstance
            $effectInstance->getDuration(),
            $effectInstance->getAmplifier(),
            100.0 // Default chance to 100%
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescriptionId(): string
    {
        return $this->descriptionId;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return int
     */
    public function getAmplifier(): int
    {
        return $this->amplifier;
    }

    /**
     * @return float
     */
    public function getChance(): float
    {
        return $this->chance;
    }

    /**
     * Convert the effect to a CompoundTag for NBT representation.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setInt("id", $this->getId())
            ->setString("name", $this->getName())
            ->setString("description_id", $this->getDescriptionId())
            ->setInt("duration", $this->getDuration())
            ->setInt("amplifier", $this->getAmplifier())
            ->setFloat("chance", $this->getChance());
    }
}
