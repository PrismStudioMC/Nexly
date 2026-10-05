<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\block\Block;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

#[Attribute(Attribute::TARGET_CLASS)]
class DiggerItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly bool $efficiency,
        private array $diggers
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::DIGGER->getValue();
    }

    /**
     * Add a block with its corresponding speed to the digger component.
     *
     * @param Block $block
     * @param int $speed
     * @return $this
     */
    public function addBlock(Block $block, int $speed): self
    {
        $blockName = GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName();
        $this->diggers[] = CompoundTag::create()->setTag(
            "block",
            CompoundTag::create()
            ->setTag("name", new StringTag($blockName))
            ->setTag("speed", new IntTag($speed))
        );
        return $this;
    }

    /**
     * Add multiple blocks with the same speed to the digger component.
     *
     * @param int $speed
     * @param Block ...$blocks
     * @return $this
     */
    public function addBlocks(int $speed, Block ...$blocks): self
    {
        foreach ($blocks as $block) {
            $this->addBlock($block, $speed);
        }
        return $this;
    }

    /**
     * Add a tag with its corresponding speed to the digger component.
     *
     * @param array|string $tags
     * @param int $speed
     * @return $this
     */
    public function addTag(array|string $tags, int $speed): self
    {
        $this->diggers[] = CompoundTag::create()->setTag("block", CompoundTag::create()
            ->setString("tags", "query.any_tag(" . (is_string($tags) ? "'$tags'" : implode(", ", array_map(fn (string $tag) => "'$tag'", $tags))) . ")"))
            ->setInt("speed", $speed);
        return $this;
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("on_dig", CompoundTag::create())
            ->setTag("use_efficiency", new ByteTag($this->efficiency))
            ->setTag("destroy_speeds", new ListTag($this->diggers, CompoundTag::class));
    }
}
