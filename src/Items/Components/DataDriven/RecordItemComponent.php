<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class RecordItemComponent extends DataDrivenItemComponent
{
    /**
     * @param string $sound
     * @param int $duration
     * @param int $signal
     */
    public function __construct(
        private readonly string $sound,
        private readonly int $duration = 20,
        private readonly int $signal = 1
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::RECORD->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("sound_event", new StringTag($this->sound))
            ->setTag("duration", new IntTag($this->duration))
            ->setTag("comparator_signal", new IntTag($this->signal));
    }
}
