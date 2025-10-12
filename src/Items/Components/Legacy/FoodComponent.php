<?php

namespace Nexly\Items\Components\Legacy;

use Attribute;
use Nexly\Items\Components\Legacy\Types\LegacyEffect;
use Nexly\Items\Components\Legacy\Types\LegacyItemCooldownType;
use Nexly\Items\Components\Legacy\Types\LegacyItemUseActionType;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;

#[Attribute(Attribute::TARGET_CLASS)]
class FoodComponent extends LegacyItemComponent
{
    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return LegacyComponentIds::FOOD->getValue();
    }

    /**
     * @param int $nutrition
     * @param float $saturationModifier
     * @param bool $canAlwaysEat
     * @param LegacyItemCooldownType $cooldownType
     * @param int $cooldownTick
     * @param string $usingConvertsTo
     * @param LegacyItemUseActionType $useActionType
     * @param float[] $onUseRange
     * @param LegacyEffect[] $effects
     * @param int[] $removeEffects
     */
    public function __construct(
        private readonly int                     $nutrition,
        private readonly float                   $saturationModifier,
        private readonly bool                    $canAlwaysEat = false,
        private readonly LegacyItemCooldownType  $cooldownType = LegacyItemCooldownType::NONE,
        private readonly int                     $cooldownTick = 0,
        private string                           $usingConvertsTo = "air",
        private readonly LegacyItemUseActionType $useActionType = LegacyItemUseActionType::NONE,
        private readonly array                   $onUseRange = [],
        private readonly array                   $effects = [],
        private readonly array                   $removeEffects = [],
    ) {
    }

    /**
     * Set the item that this food converts to when used.
     *
     * @param Item $item
     */
    public function setUsingConvertsTo(Item $item): void
    {
        [$rId] = ($converter = TypeConverter::getInstance())->getItemTranslator()->toNetworkId($item);
        if ($rId == null) {
            throw new \InvalidArgumentException("Item does not have a valid network ID");
        }

        $this->usingConvertsTo = $converter->getItemTypeDictionary()->fromIntId($rId);
    }

    /**
     * Get the maximum damage value.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("nutrition", new IntTag($this->nutrition))
            ->setTag("saturation_modifier", new FloatTag($this->saturationModifier))
            ->setTag("can_always_eat", new ByteTag($this->canAlwaysEat))
            ->setTag("cooldown_type", new StringTag($this->cooldownType->getValue()))
            ->setTag("cooldown_time", new IntTag($this->cooldownTick))
            ->setTag("using_converts_to", CompoundTag::create()->setTag("name", new StringTag($this->usingConvertsTo)))
            ->setTag("on_use_action", new IntTag($this->useActionType->getValue()))
            ->setTag("on_use_range", new ListTag(array_map(fn (float $value) => new FloatTag($value), $this->onUseRange), NBT::TAG_Float))
            ->setTag("effects", new ListTag(array_map(fn (LegacyEffect $effect) => $effect->toNBT(), $this->effects), NBT::TAG_Compound))
            ->setTag("remove_effects", new ListTag(array_map(fn (int $value) => new IntTag($value), $this->removeEffects), NBT::TAG_Int));
    }
}
