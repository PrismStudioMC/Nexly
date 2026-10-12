<?php

namespace Nexly\Blocks\Traits;

use InvalidArgumentException;
use Nexly\Blocks\Components\Types\Face;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

class BlockTrait
{
    /**
     * @param string $id
     * @param TraitState $state
     * @param float $rotationOffset
     * @param string|null $multiBlockDirection
     * @param int|null $multiBlockParts
     */
    public function __construct(
        protected string     $id,
        protected TraitState $state,
        protected float      $rotationOffset = 0.0,
        protected ?string    $multiBlockDirection = null,
        protected ?int       $multiBlockParts = null
    ) {
        if ($id === TraitId::PLACEMENT_DIRECTION) {
            if (!in_array($rotationOffset, [0.0, 90.0, 180.0, 270.0], true)) {
                throw new InvalidArgumentException("y_rotation_offset must be exactly 0.0, 90.0, 180.0, or 270.0 degrees.");
            }
        } elseif ($id === TraitId::MULTI_BLOCK) {
            $parts = $multiBlockParts ?? 2;
            if ($parts < 2 || $parts > 4) {
                throw new InvalidArgumentException("minecraft:multi_block.parts must be an integer in [2, 4]");
            }

            $direction = strtolower($multiBlockDirection ?? Face::UP);
            if (!in_array($direction, Face::ALL, true)) {
                throw new InvalidArgumentException("Invalid value for 'direction' in multi_block trait.");
            }

            $states = $state->getEnabledTraits();
            if (count($states) !== 1 || $states[0] !== TraitStateId::MULTI_BLOCK_PART) {
                throw new InvalidArgumentException('"minecraft:multi_block" trait requires exactly one enabled_state: multi_block_part.');
            }
        } elseif ($multiBlockParts !== null || $multiBlockDirection !== null) {
            throw new InvalidArgumentException("Multi-block properties are only allowed on the MULTI_BLOCK trait.");
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return TraitState
     */
    public function getState(): TraitState
    {
        return $this->state;
    }

    /**
     * @return float
     */
    public function getRotationOffset(): float
    {
        return $this->rotationOffset;
    }

    /**
     * @return string|null
     */
    public function getMultiBlockDirection(): ?string
    {
        return $this->multiBlockDirection;
    }

    /**
     * @return int|null
     */
    public function getMultiBlockParts(): ?int
    {
        return $this->multiBlockParts;
    }

    /**
     * @param string $id
     * @param CompoundTag $nbt
     * @return self
     */
    public static function fromNBT(string $id, CompoundTag $nbt): self
    {
        $rotationOffset = 0.0;
        $multiBlockDirection = null;
        $multiBlockParts = null;

        if ($id === TraitId::PLACEMENT_DIRECTION) {
            $rotationOffset = $nbt->getFloat("y_rotation_offset", 0.0);
        } elseif ($id === TraitId::MULTI_BLOCK) {
            $dirTag = $nbt->getTag("direction");
            $multiBlockDirection = $dirTag?->getValue();

            $partsTag = $nbt->getTag("parts");
            $multiBlockParts = $partsTag?->getValue();
        }

        $enabledStatesTag = $nbt->getTag("enabled_states");
        $state = $enabledStatesTag !== null ? TraitState::fromNBT($enabledStatesTag) : new TraitState();

        return new self(
            id: $id,
            state: $state,
            rotationOffset: $rotationOffset,
            multiBlockDirection: $multiBlockDirection,
            multiBlockParts: $multiBlockParts
        );
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("enabled_states", $this->state->toNBT())
            ->setTag("name", new StringTag($this->id));

        if ($this->id === TraitId::PLACEMENT_DIRECTION) {
            $nbt->setFloat("y_rotation_offset", $this->rotationOffset);
        } elseif ($this->id === TraitId::MULTI_BLOCK) {
            if ($this->multiBlockDirection !== null) {
                $nbt->setString("direction", $this->multiBlockDirection);
            }
            if ($this->multiBlockParts !== null) {
                $nbt->setInt("parts", $this->multiBlockParts);
            }
        }
        return $nbt;
    }
}
