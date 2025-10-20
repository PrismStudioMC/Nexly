<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\block\Block;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

// PlanterItemComponent in v1.21.111
#[Attribute(Attribute::TARGET_CLASS)]
class BlockPlacerItemComponent extends DataDrivenItemComponent
{
    /**
     * @param string $block
     * @param array $useOn
     * @param bool $canUseBlockAsIcon
     */
    public function __construct(
        private readonly string $block,
        private readonly array $useOn = [],
        private readonly bool $canUseBlockAsIcon = false,
    ) {

    }

    /**
     * Create a BlockPlacerItemComponent from a Block instance.
     *
     * @param Block $block
     * @param Block ...$useOn
     * @return $this
     */
    public static function from(Block $block, Block ...$useOn): self
    {
        return new self(
            GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(),
            array_map(fn (Block $b) => GlobalBlockStateHandlers::getSerializer()->serialize($b->getStateId())->getName(), $useOn)
        );
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::BLOCK_PLACER->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("block", new StringTag($this->block))
            ->setTag("canUseBlockAsIcon", new ByteTag($this->canUseBlockAsIcon))
            ->setTag("use_on", new ListTag($this->useOn, NBT::TAG_String));
    }
}
