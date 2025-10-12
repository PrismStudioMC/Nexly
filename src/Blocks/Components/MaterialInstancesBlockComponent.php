<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\Material;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class MaterialInstancesBlockComponent extends BlockComponent
{
    /**
     * @param Material[] $materials
     */
    public function __construct(
        private readonly array $materials,
        private ?CompoundTag $mappings = null
    ) {
        $this->mappings ??= CompoundTag::create();
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public static function getName(): string
    {
        return BlockComponentIds::MATERIAL_INSTANCES->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $materials = CompoundTag::create();
        foreach ($this->materials as $material) {
            $materials->setTag($material->getTarget()->getValue(), $material->toNBT());
        }

        return CompoundTag::create()
            ->setTag("mappings", $this->mappings)
            ->setTag("materials", $materials);
    }
}
