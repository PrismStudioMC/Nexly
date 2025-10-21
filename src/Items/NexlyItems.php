<?php

namespace Nexly\Items;

use Nexly\Items\Components\DataDriven\DataDrivenItemBuilder;
use Nexly\Items\Components\DataDriven\DataDrivenItemComponent;
use Nexly\Items\Components\DataDriven\Property\PropertyComponentIds;
use Nexly\Items\Components\DataDriven\Property\PropertyItemComponent;
use Nexly\Items\Components\DataDriven\Types\IgnoreBlockVisual;
use Nexly\Items\Components\Legacy\LegacyItemBuilder;
use Nexly\Items\Components\Legacy\LegacyItemComponent;
use Nexly\Items\Creative\CreativeInfo;
use pocketmine\item\Item;

class NexlyItems
{
    /**
     * Register a custom item with a string identifier.
     *
     * @param string $stringId
     * @param Item $item
     * @param CreativeInfo|null $creativeInfo
     * @return void
     */
    public static function register(string $stringId, Item $item, CreativeInfo $creativeInfo = null): void
    {
        $version = ItemVersion::fromItem($item);
        if ($version->equals(ItemVersion::LEGACY)) {
            self::registerLegacy($stringId, $item, $creativeInfo);
        } else {
            self::registerDataDriven($stringId, $item, $creativeInfo);
        }
    }

    /**
     * Register a custom item with a string identifier.
     *
     * @param string $stringId
     * @param Item $item
     * @param CreativeInfo|null $creativeInfo
     * @param bool $autoload
     * @return void
     */
    public static function registerLegacy(string $stringId, Item $item, CreativeInfo $creativeInfo = null, bool $autoload = true): void
    {
        $version = ItemVersion::fromItem($item);
        if ($version !== ItemVersion::LEGACY) {
            throw new \InvalidArgumentException("Item must be a legacy item.");
        }

        $builder = LegacyItemBuilder::create();
        $builder->setStringId($stringId);
        $builder->setNumericId($item->getTypeId());
        $builder->setItem($item);
        $builder->setCreativeInfo($creativeInfo);

        if ($autoload) {
            $builder->loadFromItems();
        }

        $reflection = new \ReflectionClass($item);
        $attributes = $reflection->getAttributes();

        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();
            if ($instance instanceof LegacyItemComponent) {
                $builder->addComponent($instance);
            }
        }

        $builder->register();
    }

    /**
     * Register a custom item with a string identifier.
     *
     * @param string $stringId
     * @param Item $item
     * @param CreativeInfo|null $creativeInfo
     * @param bool $initProperties
     * @param bool $initComponents
     * @return void
     */
    public static function registerDataDriven(string $stringId, Item $item, CreativeInfo $creativeInfo = null, bool $initProperties = true, bool $initComponents = true): void
    {
        $version = ItemVersion::fromItem($item);
        if ($version !== ItemVersion::DATA_DRIVEN) {
            throw new \InvalidArgumentException("Item must be a data driven item.");
        }

        $builder = DataDrivenItemBuilder::create();
        $builder->setStringId($stringId);
        $builder->setNumericId($item->getTypeId());
        $builder->setItem($item);
        $builder->setCreativeInfo($creativeInfo);

        if ($initProperties) {
            $builder->loadProperties();
        }
        if ($initComponents) {
            $builder->loadComponents();
        }

        $reflection = new \ReflectionClass($item);
        $attributes = $reflection->getAttributes();

        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();
            if ($instance instanceof PropertyItemComponent) {
                $builder->addProperty($instance);
            } elseif ($instance instanceof DataDrivenItemComponent) {
                $builder->addComponent($instance);
            }
        }

        $builder->register();
    }
}
