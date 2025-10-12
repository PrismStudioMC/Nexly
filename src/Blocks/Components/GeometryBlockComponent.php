<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class GeometryBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly string $name = "minecraft:geometry.full_block",
        private ?CompoundTag    $boneVisibility = null
    ) {
        $this->boneVisibility ??= CompoundTag::create();
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public static function getName(): string
    {
        return BlockComponentIds::GEOMETRY->getValue();
    }

    /**
     * @param string $boneName
     * @param bool|string $visibility
     * @return $this
     */
    public function add(string $boneName, bool|string $visibility): self
    {
        $this->boneVisibility->setTag(
            $boneName,
            is_bool($visibility) ?
            new ByteTag($visibility) : CompoundTag::create()
                ->setString("expression", $visibility)
                ->setShort("version", 12)
        );
        return $this;
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("bone_visibility", $this->boneVisibility)
            ->setTag("culling", new StringTag(""))
            ->setTag("identifier", new StringTag($this->name));
    }
}
