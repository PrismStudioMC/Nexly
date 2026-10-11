<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class RedstoneConductivityBlockComponent extends BlockComponent
{
    /**
     * @param bool $allowsWireToStepDown
     * @param bool $redstoneConductor
     */
    public function __construct(
        protected bool $allowsWireToStepDown = true,
        protected bool $redstoneConductor = false
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::REDSTONE_CONDUCTIVITY;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag('allows_wire_to_step_down', new ByteTag($this->allowsWireToStepDown))
            ->setTag('redstone_conductor', new ByteTag($this->redstoneConductor));
    }
}
