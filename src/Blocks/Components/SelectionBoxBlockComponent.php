<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\BoxCollision;
use pocketmine\block\Beetroot;
use pocketmine\block\Block;
use pocketmine\block\Carrot;
use pocketmine\block\NetherWartPlant;
use pocketmine\block\Potato;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class SelectionBoxBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly bool $enabled,
        private ?BoxCollision $collision = null,
    ) {
        $this->collision ??= new BoxCollision(new Vector3(-8.0, 0.0, -8.0), new Vector3(16.0, 16.0, 16.0));
    }

    /**
     * Creates the component from a Block instance.
     *
     * @param int $age
     * @param Block $block
     * @return self
     */
    public static function fromCrops(Block $block, int $age): self
    {
        return new self(
            true,
            new BoxCollision(
                new Vector3(-8.0, 0.0, -8.0),
                match(true) {
                    $block instanceof Carrot => new Vector3(16.0, (($age + 1.0) * (1 / $block::MAX_AGE)) * 0.7 * 16, 16.0),
                    $block instanceof Potato, $block instanceof Beetroot => new Vector3(16.0, (($age + 1.0) * (1 / $block::MAX_AGE)) * 0.6 * 16, 16.0),
                    $block instanceof NetherWartPlant => new Vector3(16.0, ($age + 1.0) * 0.25 * 16, 16.0),
                    default => new Vector3(16.0, ($age + 1) * (1 / ($block::MAX_AGE ?? 7)) * 16, 16.0),
                }
            )
        );
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::SELECTION_BOX->getValue();
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
