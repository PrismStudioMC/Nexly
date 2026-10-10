<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\math\Facing;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class PlacementFilterBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly array $allowedFaces = [],
        private readonly array $blockFilter = [],
    )
    {
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::PLACEMENT_FILTER->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("conditions", CompoundTag::create()
                ->setTag("allowed_faces", new ListTag(array_map(function (mixed $face) {
                    return new StringTag(is_int($face) ? Facing::toString($face) : (string)$face);
                }, $this->allowedFaces), NBT::TAG_String)))
                ->setTag("block_filter", new ListTag(array_map(function (string $block) {
                    return new StringTag($block);
                }, $this->allowedFaces), NBT::TAG_String));
    }
}