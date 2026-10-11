<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class LeashableBlockComponent extends BlockComponent
{
    /**
     * @param Vector3|null $offset
     */
    public function __construct(
        protected ?Vector3 $offset = null
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::LEASHABLE->value;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create();
        if ($this->offset !== null) {
            $nbt->setTag('offset', new ListTag([
                new FloatTag($this->offset->x),
                new FloatTag($this->offset->y),
                new FloatTag($this->offset->z),
            ], NBT::TAG_Float));
        }
        return $nbt;
    }

}
