<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\BoxCollision;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class CollisionBoxBlockComponent extends BlockComponent
{
    /**
     * @param bool $enabled
     * @param BoxCollision[] $collisions
     */
    public function __construct(
        private readonly bool $enabled,
        private array $collisions = [],
    ) {
        if (empty($this->collisions))
            $this->collisions[] = new BoxCollision(new Vector3(-8.0, 0.0, -8.0), new Vector3(16.0, 16.0, 16.0));
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
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
        return CompoundTag::create()
            ->setTag("enabled", new ByteTag($this->enabled))
            ->setTag("boxes", new ListTag(
                    array_map(
                        fn (BoxCollision $collision) => $collision->toNBT(true),
                        $this->collisions
                    ),
                    NBT::TAG_Compound)
            );
    }
}
