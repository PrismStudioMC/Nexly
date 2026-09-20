<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class TransformationBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly Vector3 $rotation = new Vector3(0, 0, 0),
        private readonly Vector3 $scale = new Vector3(1, 1, 1),
        private readonly Vector3 $translation = new Vector3(0, 0, 0),
        private readonly Vector3 $rotationPivot = new Vector3(0, 0, 0),
        private readonly Vector3 $scalePivot = new Vector3(0, 0, 0),
        private readonly bool $hasJsonVersionBeforeValidation = false,
    ) {
        foreach ([$rotation->getX(), $rotation->getY(), $rotation->getZ()] as $angle) {
            if (abs(fmod((float) $angle, 90.0)) > PHP_FLOAT_EPSILON) {
                throw new \InvalidArgumentException("Block rotation values must be multiples of 90 degrees.");
            }
        }
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
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
            ->setTag("TZ", new FloatTag($this->translation->getZ()))
            ->setTag("RPX", new FloatTag($this->rotationPivot->getX()))
            ->setTag("RPY", new FloatTag($this->rotationPivot->getY()))
            ->setTag("RPZ", new FloatTag($this->rotationPivot->getZ()))
            ->setTag("SPX", new FloatTag($this->scalePivot->getX()))
            ->setTag("SPY", new FloatTag($this->scalePivot->getY()))
            ->setTag("SPZ", new FloatTag($this->scalePivot->getZ()))
            ->setTag("hasJsonVersionBeforeValidation", new ByteTag($this->hasJsonVersionBeforeValidation ? 1 : 0));
    }
}
