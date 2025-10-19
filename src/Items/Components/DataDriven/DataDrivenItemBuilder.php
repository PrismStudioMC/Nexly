<?php

namespace Nexly\Items\Components\DataDriven;

use Nexly\Items\Components\DataDriven\Property\BlockProperty;
use Nexly\Items\Components\DataDriven\Property\CanDestroyInCreative;
use Nexly\Items\Components\DataDriven\Property\DamageProperty;
use Nexly\Items\Components\DataDriven\Property\EnchantableSlotProperty;
use Nexly\Items\Components\DataDriven\Property\EnchantableValueProperty;
use Nexly\Items\Components\DataDriven\Property\HandEquippedProperty;
use Nexly\Items\Components\DataDriven\Property\IconProperty;
use Nexly\Items\Components\DataDriven\Property\LiquidClippedProperty;
use Nexly\Items\Components\DataDriven\Property\MaxStackSizeProperty;
use Nexly\Items\Components\DataDriven\Property\MiningSpeedProperty;
use Nexly\Items\Components\DataDriven\Property\PropertyItemComponent;
use Nexly\Items\Components\DataDriven\Property\ShouldDespawnProperty;
use Nexly\Items\Components\DataDriven\Property\StackedByDataProperty;
use Nexly\Items\Components\DataDriven\Property\UseAnimationProperty;
use Nexly\Items\Components\DataDriven\Property\UseDurationProperty;
use Nexly\Items\Components\DataDriven\Types\ItemEnchantSlot;
use Nexly\Items\Components\DataDriven\Types\ItemRepair;
use Nexly\Items\Components\DataDriven\Types\ItemSlot;
use Nexly\Items\ItemBuilder;
use Nexly\Items\ItemVersion;
use pocketmine\block\Air;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Armor;
use pocketmine\item\Bucket;
use pocketmine\item\Durable;
use pocketmine\item\Dye;
use pocketmine\item\Food;
use pocketmine\item\ProjectileItem;
use pocketmine\item\Record;
use pocketmine\item\SpawnEgg;
use pocketmine\item\Sword;
use pocketmine\item\TieredTool;
use pocketmine\item\Tool;
use pocketmine\item\ToolTier;
use pocketmine\item\VanillaArmorMaterials;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class DataDrivenItemBuilder extends ItemBuilder
{
    /** @var PropertyItemComponent[] */
    private array $properties = [];
    /** @var DataDrivenItemComponent[] */
    private array $components = [];

    public static function create(): DataDrivenItemBuilder
    {
        return new self();
    }

    /**
     * Get the item version for data-driven items.
     *
     * @return ItemVersion
     */
    public static function getVersion(): ItemVersion
    {
        return ItemVersion::DATA_DRIVEN;
    }

    /**
     * @return PropertyItemComponent[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Check if a property exists by its name.
     *
     * @param string $name
     * @return bool
     */
    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * Check if a property exists by its name.
     *
     * @param PropertyItemComponent $property
     * @return $this
     */
    public function addProperty(PropertyItemComponent $property): self
    {
        $this->properties[$property::getName()] = $property;
        return $this;
    }

    /**
     * Remove a property by its name.
     *
     * @param string $name
     * @return $this
     */
    public function removeProperty(string $name): self
    {
        unset($this->properties[$name]);
        return $this;
    }

    /**
     * @return DataDrivenItemComponent[]
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * Check if a component exists by its name.
     *
     * @param string $name
     * @return bool
     */
    public function hasComponent(string $name): bool
    {
        return isset($this->components[$name]);
    }

    /**
     * Add a DataDrivenComponent to the builder.
     *
     * @param DataDrivenItemComponent $component
     * @return $this
     */
    public function addComponent(DataDrivenItemComponent $component): self
    {
        $this->components[$component::getName()] = $component;
        return $this;
    }

    /**
     * Remove a component by its name.
     *
     * @param string $name
     * @return void
     */
    public function removeComponent(string $name): void
    {
        unset($this->components[$name]);
    }

    /**
     * Create a LegacyItemBuilder from an Item instance.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create();
        $properties = CompoundTag::create();
        foreach ($this->getProperties() as $k => $property) {
            $properties->setTag($property::getName(), $property->toNBT());
        }
        foreach ($this->getComponents() as $k => $component) {
            $nbt->setTag($component::getName(), $component->toNBT());
        }

        $nbt->setTag("item_properties", $properties);
        return CompoundTag::create()
            ->setTag("id", new IntTag($this->getNumericId()))
            ->setTag("name", new StringTag($this->getStringId()))
            ->setTag("components", $nbt);
    }

    /**
     * Load components from the item instance.
     *
     * @return $this
     */
    public function loadProperties(): self
    {
        $item = $this->getItem();

        $icon = $this->getStringId();
        if (str_contains($icon, ":")) {
            $icon = explode(":", $icon, 2)[1];
        }

        $this->addProperty(new IconProperty($icon));
        $this->addProperty(new StackedByDataProperty(false));
        $this->addProperty(new ShouldDespawnProperty(false));
        $this->addProperty(new MaxStackSizeProperty($item->getMaxStackSize()));
        $this->addProperty(new HandEquippedProperty($item instanceof Tool));
        $this->addProperty(new CanDestroyInCreative(!$item instanceof Sword));
        $this->addProperty(new LiquidClippedProperty($item instanceof Bucket));
        $this->addProperty(new MiningSpeedProperty($item->getMiningEfficiency(false)));

        if ($item->getAttackPoints() > 1) {
            $this->addProperty(new DamageProperty($item->getAttackPoints() - 1));
        }

        $block = $item->getBlock();
        if (!($block instanceof Air)) {
            $this->addProperty(BlockProperty::from($block));
        }

        if ($item instanceof Food) {
            $this->addProperty(new UseDurationProperty(20));
        }

        $this->addProperty(UseAnimationProperty::fromItem($item));
        $this->addProperty(EnchantableSlotProperty::fromItem($item));
        $this->addProperty(EnchantableValueProperty::fromItem($item));
        return $this;
    }

    /**
     * Load components from the item instance.
     *
     * @return $this
     */
    public function loadComponents(): self
    {
        $item = $this->getItem();
        $this->addComponent(new DisplayNameItemComponent("item." . $this->getStringId() . ".name"));
        $this->addComponent(new EnchantableItemComponent(ItemEnchantSlot::fromItem($item), $item->getEnchantability()));
        $this->addComponent(new FireResistantItemComponent($item->isFireProof()));

        if ($item->getFuelTime() > 0) {
            $this->addComponent(new FuelItemComponent($item->getFuelTime() / 20));
        }

        if ($item instanceof Durable && !$item->isUnbreakable()) {
            $this->addComponent(new DurabilityItemComponent($item->getMaxDurability()));

            $items = [];
            if ($item instanceof Armor) {
                $items[] = ItemRepair::from(RepairableItemComponent::VANILLA_COST_FORMULE, match (true) {
                    $item->getMaterial() === VanillaArmorMaterials::LEATHER() => VanillaItems::LEATHER(),
                    $item->getMaterial() === VanillaArmorMaterials::IRON() => VanillaItems::IRON_INGOT(),
                    $item->getMaterial() === VanillaArmorMaterials::GOLD() => VanillaItems::GOLD_INGOT(),
                    $item->getMaterial() === VanillaArmorMaterials::DIAMOND() => VanillaItems::DIAMOND(),
                    $item->getMaterial() === VanillaArmorMaterials::NETHERITE() => VanillaItems::NETHERITE_INGOT(),
                    $item->getMaterial() === VanillaArmorMaterials::TURTLE() => VanillaItems::SCUTE(),
                    default => VanillaItems::AIR(),
                });
            } elseif ($item instanceof TieredTool) {
                $items[] = ItemRepair::from(RepairableItemComponent::VANILLA_COST_FORMULE, ...match (true) {
                    $item->getToolTier() === ToolTier::WOOD => [VanillaBlocks::OAK_WOOD()->asItem(), VanillaBlocks::OAK_LOG()->asItem(), VanillaBlocks::BIRCH_LOG()->asItem(), VanillaBlocks::SPRUCE_LOG()->asItem(), VanillaBlocks::JUNGLE_LOG()->asItem(), VanillaBlocks::ACACIA_LOG()->asItem(), VanillaBlocks::DARK_OAK_LOG()->asItem()],
                    $item->getToolTier() === ToolTier::STONE => [VanillaBlocks::COBBLESTONE()->asItem(), VanillaBlocks::STONE()->asItem()],
                    $item->getToolTier() === ToolTier::IRON => [VanillaItems::IRON_INGOT()],
                    $item->getToolTier() === ToolTier::GOLD => [VanillaItems::GOLD_INGOT()],
                    $item->getToolTier() === ToolTier::DIAMOND => [VanillaItems::DIAMOND()],
                    $item->getToolTier() === ToolTier::NETHERITE => [VanillaItems::NETHERITE_INGOT()],
                    default => [VanillaItems::AIR()],
                });
            }

            $this->addComponent(new RepairableItemComponent($items));
        }

        if ($item instanceof Armor) {
            $this->addComponent(new WearableItemComponent(ItemSlot::fromArmorTypeInfo($item->getArmorSlot()), $item->getDefensePoints()));
            $this->addComponent(ArmorItemComponent::from($item));
        }

        $block = $item->getBlock();
        if (!($block instanceof Air)) {
            $this->addComponent(BlockPlacerItemComponent::from($block));
        }

        $cooldown = $item->getCooldownTicks();
        if ($cooldown > 0) {
            $this->addComponent(new CooldownItemComponent($cooldown / 20, $item->getCooldownTag()));
        }

        if ($item instanceof Dye) {
            $this->addComponent(new DyeableItemComponent(sprintf("%02X%02X%02X%02X", ($color = $item->getColor())->r, $color->g, $color->b, $color->a)));
        }

        if ($item instanceof SpawnEgg) {
            $this->addComponent(new EntityPlacerItemComponent());
        }

        if ($item instanceof Food) {
            $this->addComponent(new FoodItemComponent(
                $item->getFoodRestore(),
                $item->getSaturationRestore(),
                !$item->requiresHunger()
            ));
        }

        if ($item instanceof ProjectileItem) {
            $this->addComponent(new ProjectileItemComponent());
            $this->addComponent(new ThrowableItemComponent());
        }

        if ($item instanceof Record) {
            $this->addComponent(new RecordItemComponent($item->getRecordType()->getSoundName()));
        }
        return $this;
    }
}
