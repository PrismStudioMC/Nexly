<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\PrecipitationType;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class PrecipitationInteractionsBlockComponent extends BlockComponent
{
    /**
     * @param string $precipitation
     */
    public function __construct(
        protected string $precipitation = PrecipitationType::NONE,
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::PRECIPITATION_INTERACTIONS;
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("precipitation_behavior", new StringTag(strtolower($this->precipitation)));
    }
}
