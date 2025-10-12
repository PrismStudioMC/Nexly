<?php

namespace Nexly\Items\Components\Legacy;

use Attribute;
use Nexly\Items\Components\Legacy\Types\LegacyFace;
use pocketmine\block\Block;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

#[Attribute(Attribute::TARGET_CLASS)]
class SeedComponent extends LegacyItemComponent
{
    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return LegacyComponentIds::SEED->getValue();
    }

    /**
     * Create a SeedComponent from blocks.
     *
     * @param Block $result
     * @param Block ...$blocks
     * @return self
     */
    public static function fromBlocks(Block $result, Block ...$blocks): self
    {
        return new self(
            GlobalBlockStateHandlers::getSerializer()->serialize($result->getStateId())->getName(),
            array_map(fn (Block $block) => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(), $blocks),
        );
    }

    public function __construct(
        private string $result,
        private array $plantAt = [],
        private readonly LegacyFace $plantFace = LegacyFace::UP,
    ) {
        $this->result = str_replace(["minecraft:", "grass_block", "air"], ["", "grass", "light_block"], strtolower($this->result));
        $this->plantAt = array_map(fn (string $block) => str_replace(["minecraft:", "grass_block"], ["", "grass"], strtolower($block)), $this->plantAt);
    }

    /**
     * Get the maximum damage value.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("crop_result", new StringTag($this->result))
            ->setTag("plant_at_face", new StringTag($this->plantFace->getValue()))
            ->setTag("plant_at_any_solid_surface", new ByteTag(empty($this->plantAt)))
            ->setTag("plant_at", new ListTag(array_map(fn (string $block) => new StringTag($block), $this->plantAt), NBT::TAG_String));
    }
}
