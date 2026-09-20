<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\ConnectionType;
use Nexly\Blocks\Components\Types\HorizontalFace;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class ConnectionRuleComponent extends BlockComponent
{
    /**
     * @param list<HorizontalFace> $enabledDirections
     */
    public function __construct(
        private readonly ConnectionType $from = ConnectionType::ALL,
        private readonly array $enabledDirections = [HorizontalFace::SOUTH, HorizontalFace::NORTH, HorizontalFace::EAST, HorizontalFace::WEST],
    ) {
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::CONNECTION_RULE->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("accepts_connections_from", new StringTag($this->from->getValue()))
            ->setTag("enabled_directions", new ListTag(array_map(fn (HorizontalFace $direction): StringTag => new StringTag($direction->getValue()), $this->enabledDirections), NBT::TAG_String));
    }
}
