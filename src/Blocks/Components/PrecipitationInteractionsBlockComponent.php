<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\PrecipitationType;
use Nexly\Blocks\Components\Types\SupportShape;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class PrecipitationInteractionsBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly PrecipitationType $precipitation,
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::PRECIPITATION_INTERACTIONS->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("precipitation_behavior", new StringTag(strtolower($this->precipitation->getName())));
    }
}
