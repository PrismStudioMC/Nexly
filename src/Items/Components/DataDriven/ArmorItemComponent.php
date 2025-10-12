<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\OreTextureType;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

/** @deprecated */
#[Attribute(Attribute::TARGET_CLASS)]
class ArmorItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly OreTextureType $textureType,
        private readonly ?int           $protection = null,
    ) {
    }

    /**
     * Create an ArmorItemComponent from an Armor instance.
     *
     * @param Armor $item
     * @return self
     */
    public static function from(Item $item): self
    {
        return new self(
            OreTextureType::fromItem($item),
            $item instanceof Armor ? $item->getDefensePoints() : null
        );
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::ARMOR->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("texture_type", new StringTag($this->textureType->getValue()));

        if ($this->protection === null) {
            return $nbt;
        }

        return $nbt->setTag("protection", new IntTag($this->protection));
    }
}
