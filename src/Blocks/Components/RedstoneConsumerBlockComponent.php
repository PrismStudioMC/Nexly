<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class RedstoneConsumerBlockComponent extends BlockComponent
{
    public function __construct(
        protected int $minPower = 0,
        protected bool $propagatesPower = false
    ) {
        if ($minPower < 0 || $minPower > 15) {
            throw new InvalidArgumentException('Redstone minimum power must be from 0 to 15.');
        }
    }

    public function getName(): string
    {
        return BlockComponentIds::REDSTONE_CONSUMER->value;
    }

    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag('min_power', new IntTag($this->minPower))
            ->setTag('propagates_power', new ByteTag($this->propagatesPower));
    }
}
