<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class TransformationBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly Vector3 $rotation = new Vector3(0, 0, 0),
        private readonly Vector3 $scale = new Vector3(1, 1, 1),
        private readonly Vector3 $translation = new Vector3(0, 0, 0)
    ) {

    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public static function getName(): string
    {
        return BlockComponentIds::TRANSFORMATION->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("RX", new IntTag(intdiv((int) $this->rotation->getX(), 90)))
            ->setTag("RY", new IntTag(intdiv((int) $this->rotation->getY(), 90)))
            ->setTag("RZ", new IntTag(intdiv((int) $this->rotation->getZ(), 90)))
            ->setTag("SX", new FloatTag($this->scale->getX()))
            ->setTag("SY", new FloatTag($this->scale->getY()))
            ->setTag("SZ", new FloatTag($this->scale->getZ()))
            ->setTag("TX", new FloatTag($this->translation->getX()))
            ->setTag("TY", new FloatTag($this->translation->getY()))
            ->setTag("TZ", new FloatTag($this->translation->getZ()));
    }
}
