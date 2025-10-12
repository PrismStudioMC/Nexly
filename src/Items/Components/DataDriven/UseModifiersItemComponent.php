<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class UseModifiersItemComponent extends DataDrivenItemComponent
{
    /**
     * @param float $duration
     * @param float|null $movementModifier
     */
    public function __construct(
        private readonly float $duration,
        private readonly ?float $movementModifier = null,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::USE_MODIFIERS->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("use_duration", new FloatTag($this->duration));

        if ($this->movementModifier !== null) {
            $nbt->setTag("movement_modifier", new FloatTag($this->movementModifier));
        }

        return $nbt;
    }
}
