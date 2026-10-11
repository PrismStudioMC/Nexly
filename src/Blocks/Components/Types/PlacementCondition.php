<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

class PlacementCondition
{
    /**
     * @param list<PlacementFace> $allowedFaces
     * @param list<string> $blockFilter
     */
    public function __construct(
        protected array $allowedFaces = [],
        protected array $blockFilter = []
    ) {
        $filters = [];
        foreach ($blockFilter as $filter) {
            $filters[] = $filter instanceof NbtDescriptor ? $filter : new NbtDescriptor($filter);
        }
        $this->blockFilter = $filters;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $condition = CompoundTag::create();

        if (!empty($this->allowedFaces)) {
            $condition->setTag('allowed_faces', new ListTag(
                array_map(fn (string $face) => new StringTag($face), $this->allowedFaces),
                NBT::TAG_String
            ));
        }
        if (!empty($this->blockFilter)) {
            $condition->setTag('block_filter', new ListTag(
                array_map(fn (NbtDescriptor $filter) => $filter->toNBT(), $this->blockFilter),
                NBT::TAG_Compound
            ));
        }

        return $condition;
    }
}
