<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class GeometryBlockComponent extends BlockComponent
{
    /**
     * @param string $identifier
     * @param string $culling
     * @param string $cullingLayer
     * @param string $cullingShape
     * @param string|null $nWayVisualRotation
     * @param bool|array $uvLock
     * @param bool $ignoreGeometryForIsSolid
     * @param bool $needsLegacyTopRotation
     * @param bool $useBlockTypeLightAbsorption
     * @param CompoundTag|null $boneVisibility
     */
    public function __construct(
        private readonly string $identifier = "minecraft:geometry.full_block",
        private readonly string $culling = "",
        private readonly string $cullingLayer = "minecraft:culling_layer.undefined",
        private readonly string $cullingShape = "minecraft:empty",
        private readonly ?string $nWayVisualRotation = null,
        private readonly bool|array $uvLock = false,
        private readonly bool $ignoreGeometryForIsSolid = true,
        private readonly bool $needsLegacyTopRotation = false,
        private readonly bool $useBlockTypeLightAbsorption = false,
        private ?CompoundTag $boneVisibility = null
    ) {
        $this->boneVisibility ??= CompoundTag::create();
        if (is_array($uvLock) && count($uvLock) > 64) {
            throw new \InvalidArgumentException("UV lock cannot contain more than 64 bone names.");
        }
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
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
                new ByteTag($visibility ? 1 : 0) : CompoundTag::create()
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
        $nbt = CompoundTag::create()
            ->setTag("bone_visibility", $this->boneVisibility)
            ->setTag("identifier", new StringTag($this->identifier))
            ->setTag("culling", new StringTag($this->culling))
            ->setTag("culling_layer", new StringTag($this->cullingLayer))
            ->setTag("ignoreGeometryForIsSolid", new ByteTag($this->ignoreGeometryForIsSolid ? 1 : 0))
            ->setTag("needsLegacyTopRotation", new ByteTag($this->needsLegacyTopRotation ? 1 : 0))
            ->setTag("useBlockTypeLightAbsorption", new ByteTag($this->useBlockTypeLightAbsorption ? 1 : 0));

        $nbt->setTag("culling_shape", new StringTag($this->cullingShape));

        if ($this->nWayVisualRotation !== null) {
            $nbt->setTag("n_way_visual_rotation", new StringTag($this->nWayVisualRotation));
        }

        $nbt->setTag(
            "uv_lock",
            is_bool($this->uvLock)
            ? new ByteTag($this->uvLock ? 1 : 0)
            : new ListTag(array_map(fn (string $bone): StringTag => new StringTag($bone), $this->uvLock), NBT::TAG_String)
        );

        return $nbt;
    }
}
