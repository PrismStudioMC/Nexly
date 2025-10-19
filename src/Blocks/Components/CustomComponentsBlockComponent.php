<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class CustomComponentsBlockComponent extends BlockComponent
{
    public function __construct(
        private bool $hasPlayerInteract = true,
        private bool $hasPlayerPlacing = true,
        private bool $isV1Component = true,
    ) {
    }

    /**
     * Returns the name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::CUSTOM_COMPONENTS->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("hasPlayerInteract", new ByteTag($this->hasPlayerInteract))
            ->setTag("hasPlayerPlacing", new ByteTag($this->hasPlayerPlacing))
            ->setTag("isV1Component", new ByteTag($this->isV1Component));
    }
}
