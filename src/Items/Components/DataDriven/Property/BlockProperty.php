<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use pocketmine\block\Block;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

#[Attribute(Attribute::TARGET_CLASS)]
class BlockProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly string $blockName
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::BLOCK->getValue();
    }

    /**
     * Create a BlockComponent from a Block instance.
     *
     * @param Block $block
     * @return self
     */
    public static function from(Block $block): self
    {
        return new self(
            GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(),
        );
    }

    /**
     * @return StringTag
     */
    public function toNBT(): StringTag
    {
        return new StringTag($this->blockName);
    }
}
