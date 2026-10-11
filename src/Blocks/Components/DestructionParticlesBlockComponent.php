<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\TintMethod;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DestructionParticlesBlockComponent extends BlockComponent
{
    /**
     * @param int $particleCount
     * @param string|null $texture
     * @param TintMethod $tintMethod
     */
    public function __construct(
        protected int $particleCount = 100,
        protected ?string $texture = null,
        protected TintMethod $tintMethod = TintMethod::NONE,
    ) {
        if ($particleCount < 0 || $particleCount > 255) {
            throw new InvalidArgumentException('Destruction particle count must be between 0 and 255.');
        }
        if ($texture === '') {
            throw new InvalidArgumentException('Particle texture cannot be empty when provided.');
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::DESTRUCTION_PARTICLES->value;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag('particle_count', new IntTag($this->particleCount))
            ->setTag('tint_method', new StringTag($this->tintMethod->value));

        if ($this->texture !== null) {
            $nbt->setTag('texture', new StringTag($this->texture));
        }
        return $nbt;
    }
}
