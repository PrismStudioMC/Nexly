<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\block\Block;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

#[Attribute(Attribute::TARGET_CLASS)]
class EntityPlacerItemComponent extends DataDrivenItemComponent
{
    /**
     * @param string $entity
     * @param string[] $dispenseOn
     * @param string[] $useOn
     */
    public function __construct(
        private readonly string $entity = "none",
        private readonly array $dispenseOn = [],
        private readonly array $useOn = [],
    ) {
    }

    /**
     * Create an EntityPlacerItemComponent from the given parameters.
     *
     * @param string $entity
     * @param Block[] $dispenseOn
     * @param Block[] $useOn
     * @return self
     */
    public static function from(string $entity, array $dispenseOn = [], array $useOn = []): self
    {
        return new self(
            $entity,
            array_map(fn (Block $block) => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(), $dispenseOn),
            array_map(fn (Block $block) => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(), $useOn)
        );
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::ENTITY_PLACER->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("entity", new StringTag($this->entity))
            ->setTag("dispense_on", new ListTag(array_map(fn (string $name) => new StringTag($name), $this->dispenseOn), NBT::TAG_String))
            ->setTag("use_on", new ListTag(array_map(fn (string $name) => new StringTag($name), $this->useOn), NBT::TAG_String));
    }
}
