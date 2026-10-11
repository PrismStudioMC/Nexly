<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\Face;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
final class RedstoneProducerBlockComponent extends BlockComponent
{
    /**
     * @param int $power
     * @param array|null $connectedFaces
     * @param Face|null $stronglyPoweredFace
     * @param bool $transformRelative
     */
    public function __construct(
        protected int $power,
        protected ?array $connectedFaces = null,
        protected ?Face $stronglyPoweredFace = null,
        protected bool $transformRelative = false,
    ) {
        if ($power < 0 || $power > 15) {
            throw new InvalidArgumentException('Redstone power must be from 0 to 15.');
        }

        $this->connectedFaces ??= Face::cases();
        if (count(array_unique(array_map(static fn (Face $face): string => $face->value, $this->connectedFaces))) !== count($this->connectedFaces)) {
            throw new InvalidArgumentException('Connected redstone faces must be unique.');
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::REDSTONE_PRODUCER->value;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag('power', new IntTag($this->power))
            ->setTag('connected_faces', new ListTag(array_map(static fn (Face $face) => new StringTag($face->value), $this->connectedFaces), NBT::TAG_String))
            ->setTag('transform_relative', new ByteTag($this->transformRelative));

        if ($this->stronglyPoweredFace !== null) {
            $nbt->setString('strongly_powered_face', $this->stronglyPoweredFace->value);
        }
        return $nbt;
    }
}
