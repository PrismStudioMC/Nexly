<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Blocks\Components\Types\BlockNbtDescriptor;
use pocketmine\block\Block;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

#[Attribute(Attribute::TARGET_CLASS)]
class BlockPlacerItemComponent extends DataDrivenItemComponent
{
    /**
     * @param string $block
     * @param array $useOn
     * @param bool $canUseBlockAsIcon
     * @param bool $replaceBlockItem
     * @param bool $alignedPlacement
     */
    public function __construct(
        protected string $block,
        protected array $useOn = [],
        protected bool $canUseBlockAsIcon = false,
        protected bool $replaceBlockItem = false,
        protected bool $alignedPlacement = false
    ) {
        foreach ($this->useOn as $k => $useOn) {
            if (is_string($useOn)) {
                $this->useOn[$k] = new BlockNbtDescriptor($useOn);
            }
        }
    }

    /**
     * @param Block $block
     * @param array $useOn
     * @param bool $canUseBlockAsIcon
     * @param bool $replaceBlockItem
     * @param bool $alignedPlacement
     * @return self
     */
    public static function make(Block $block, array $useOn = [], bool $canUseBlockAsIcon = false, bool $replaceBlockItem = false, bool $alignedPlacement = false): self
    {
        return new self(
            GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(),
            array_map(fn (Block $b) => GlobalBlockStateHandlers::getSerializer()->serialize($b->getStateId())->getName(), $useOn),
            $canUseBlockAsIcon,
            $replaceBlockItem,
            $alignedPlacement
        );
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
            ->setTag("use_on", new ListTag(array_map(fn (BlockNbtDescriptor $block): CompoundTag => $block->toNBT(), $this->useOn), NBT::TAG_Compound))
            ->setTag("canUseBlockAsIcon", new ByteTag($this->canUseBlockAsIcon))
            ->setTag("replace_block_item", new ByteTag($this->replaceBlockItem))
            ->setTag("aligned_placement", new ByteTag($this->alignedPlacement));
    }
}
