<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\ItemAnimation;
use pocketmine\entity\Consumable;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class UseAnimationProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly ItemAnimation $animation
    ) {
    }

    /**
     * Create an UseAnimationProperty from an Item.
     *
     * @param Item $item
     * @return UseAnimationProperty
     */
    public static function fromItem(Item $item): UseAnimationProperty
    {
        return new self(match (true) {
            $item instanceof Consumable => ItemAnimation::EAT,
            $item instanceof Potion => ItemAnimation::DRINK,
            default => ItemAnimation::NONE,
        });
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::USE_ANIMATION->getValue();
    }

    /**
     * @return IntTag
     */
    public function toNBT(): IntTag
    {
        return new IntTag($this->animation->getValue());
    }
}
