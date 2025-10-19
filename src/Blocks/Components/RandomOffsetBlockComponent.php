<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\RangeOffset;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\network\mcpe\protocol\types\BlockPosition;

#[Attribute(Attribute::TARGET_CLASS)]
class RandomOffsetBlockComponent extends BlockComponent
{
    public function __construct(
        private BlockPosition $steps,
        private RangeOffset   $offset,
    ) {
    }

    /**
     * Returns the name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::RANDOM_OFFSET->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag(
                "x",
                CompoundTag::create()
                ->setTag("steps", new IntTag($this->steps->getX()))
                ->setTag(
                    "range",
                    CompoundTag::create()
                    ->setTag("min", new FloatTag($this->offset->getMin()->getX()))
                    ->setTag("max", new FloatTag($this->offset->getMax()->getX()))
                )
            )
            ->setTag(
                "y",
                CompoundTag::create()
                ->setTag("steps", new IntTag($this->steps->getY()))
                ->setTag(
                    "range",
                    CompoundTag::create()
                    ->setTag("min", new FloatTag($this->offset->getMin()->getY()))
                    ->setTag("max", new FloatTag($this->offset->getMax()->getY()))
                )
            )
            ->setTag(
                "z",
                CompoundTag::create()
                ->setTag("steps", new IntTag($this->steps->getZ()))
                ->setTag(
                    "range",
                    CompoundTag::create()
                    ->setTag("min", new FloatTag($this->offset->getMin()->getZ()))
                    ->setTag("max", new FloatTag($this->offset->getMax()->getZ()))
                )
            );
    }
}
