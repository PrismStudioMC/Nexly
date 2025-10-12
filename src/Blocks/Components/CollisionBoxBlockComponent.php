<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\BlockCollision;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class CollisionBoxBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly bool           $enabled,
        private ?BlockCollision $collision = null,
    ) {
        $this->collision ??= new BlockCollision(new Vector3(-8.0, 0.0, -8.0), new Vector3(16.0, 16.0, 16.0));
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public static function getName(): string
    {
        return BlockComponentIds::COLLISION_BOX->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return $this->collision->toNBT()
            ->setTag("enabled", new ByteTag($this->enabled));
    }
}
