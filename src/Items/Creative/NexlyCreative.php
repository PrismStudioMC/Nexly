<?php

namespace Nexly\Items\Creative;

use pocketmine\block\Air;
use pocketmine\block\Anvil;
use pocketmine\block\BaseSign;
use pocketmine\block\Bed;
use pocketmine\block\Block;
use pocketmine\block\Button;
use pocketmine\block\Candle;
use pocketmine\block\Carpet;
use pocketmine\block\Chest;
use pocketmine\block\Clay;
use pocketmine\block\Concrete;
use pocketmine\block\ConcretePowder;
use pocketmine\block\Crops;
use pocketmine\block\Door;
use pocketmine\block\Fence;
use pocketmine\block\FenceGate;
use pocketmine\block\Flower;
use pocketmine\block\GlazedTerracotta;
use pocketmine\block\MobHead;
use pocketmine\block\NetherWartPlant;
use pocketmine\block\Planks;
use pocketmine\block\PressurePlate;
use pocketmine\block\Rail;
use pocketmine\block\Sapling;
use pocketmine\block\ShulkerBox;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\block\Trapdoor;
use pocketmine\block\Wall;
use pocketmine\block\WallSign;
use pocketmine\block\Wood;
use pocketmine\block\Wool;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\CreativeCategory;
use pocketmine\inventory\CreativeGroup as CreativeGroupPM;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Armor;
use pocketmine\item\Arrow;
use pocketmine\item\Axe;
use pocketmine\item\Banner;
use pocketmine\item\Dye;
use pocketmine\item\EnchantedBook;
use pocketmine\item\Food;
use pocketmine\item\GoatHorn;
use pocketmine\item\Hoe;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\item\Pickaxe;
use pocketmine\item\Potion;
use pocketmine\item\Record;
use pocketmine\item\Shovel;
use pocketmine\item\SpawnEgg;
use pocketmine\item\SplashPotion;
use pocketmine\item\Sword;

class NexlyCreative
{
    /** @var CreativeGroupPM[] */
    private static array $groups = [];
    /** @var CreativeCategory[]  */
    private static array $groupToCategory = [];
    /** @var CreativeGroup[]  */
    private static array $categoryToGroups = [];

    /**
     * Load existing creative groups from the creative inventory.
     *
     * @return void
     */
    private static function loadGroups(): void
    {
        if (!empty(self::$categoryToGroups)) {
            return;
        } // Already loaded
        if (!empty(self::$groupToCategory)) {
            return;
        } // Already loaded

        foreach (CreativeInventory::getInstance()->getAllEntries() as $entry) {
            $category = $entry->getCategory();
            $group = $entry->getGroup();
            if ($group === null) {
                continue;
            }

            self::$groups[$group->getName()?->getText() ?? $group->getName()] = $group;
            self::$categoryToGroups[$category->name] = $entry;
            self::$groupToCategory[$group->getName()?->getText()] = $category;
        }
    }

    /**
     * Add an item to the creative inventory under the specified category and group.
     *
     * @param Item $item
     * @param CreativeCategory|null $category
     * @param CreativeGroup|null $group
     * @return void
     */
    public static function add(Item $item, CreativeCategory $category = null, CreativeGroup $group = null): void
    {
        self::loadGroups(); // Ensure groups are loaded

        if ($category === null && $group !== null) {
            $category = self::$groupToCategory[$group->getValue()];
        }
        if ($category === null) {
            $category = $item instanceof ItemBlock ? CreativeCategory::CONSTRUCTION : CreativeCategory::ITEMS;
        }

        $pm = null;
        if ($group !== null) {
            $pm = self::$groups[$group->getValue()] ??= new CreativeGroupPM($group->getValue(), $item);
            self::$categoryToGroups[$category->name] = $pm;
            self::$groupToCategory[$group->getValue()] = $category;
        }

        CreativeInventory::getInstance()->add($item, $category, $pm);
    }

    /**
     * @param Item $item
     * @return CreativeInfo|null
     *
     * @internal
     * @deprecated
     */
    public static function detectCreativeInfoFrom(Item $item): ?CreativeInfo
    {
        return match (true) {
            $item instanceof Sword => new CreativeInfo(null, CreativeGroup::GROUP_SWORD),
            $item instanceof Pickaxe => new CreativeInfo(null, CreativeGroup::GROUP_PICKAXE),
            $item instanceof Axe => new CreativeInfo(null, CreativeGroup::GROUP_AXE),
            $item instanceof Shovel => new CreativeInfo(null, CreativeGroup::GROUP_SHOVEL),
            $item instanceof Hoe => new CreativeInfo(null, CreativeGroup::GROUP_HOE),
            $item instanceof Armor => match($item->getArmorSlot()) {
                ArmorInventory::SLOT_HEAD => new CreativeInfo(null, CreativeGroup::GROUP_HELMET),
                ArmorInventory::SLOT_CHEST => new CreativeInfo(null, CreativeGroup::GROUP_CHESTPLATE),
                ArmorInventory::SLOT_LEGS => new CreativeInfo(null, CreativeGroup::GROUP_LEGGINGS),
                ArmorInventory::SLOT_FEET => new CreativeInfo(null, CreativeGroup::GROUP_BOOTS),
                default => null,
            },
            $item instanceof Food && $item->getBlock() instanceof Crops => new CreativeInfo(null, CreativeGroup::GROUP_CROP),
            $item instanceof Food => new CreativeInfo(null, CreativeGroup::GROUP_MISC_FOOD),
            $item instanceof Potion => new CreativeInfo(null, CreativeGroup::GROUP_POTION),
            $item instanceof SplashPotion => new CreativeInfo(null, CreativeGroup::GROUP_SLASH_POTION),
            $item instanceof EnchantedBook => new CreativeInfo(null, CreativeGroup::GROUP_ENCHANTED_BOOK),
            $item instanceof Arrow => new CreativeInfo(null, CreativeGroup::GROUP_ARROW),
            $item instanceof Dye => new CreativeInfo(null, CreativeGroup::GROUP_DYE),
            $item instanceof Record => new CreativeInfo(null, CreativeGroup::GROUP_RECORD),
            $item instanceof GoatHorn => new CreativeInfo(null, CreativeGroup::GROUP_GOAT_HORN),
            $item instanceof Banner => new CreativeInfo(null, CreativeGroup::GROUP_BANNER),
            $item instanceof SpawnEgg => new CreativeInfo(null, CreativeGroup::GROUP_MOB_EGGS),
            !$item->getBlock() instanceof Air => self::detectCreativeInfoFromBlock($item->getBlock()),
            default => new CreativeInfo(CreativeCategory::ITEMS),
        };
    }

    /**
     * @param Block $block
     * @return CreativeInfo|null
     */
    public static function detectCreativeInfoFromBlock(Block $block): ?CreativeInfo
    {
        return match (true) {
            $block instanceof Crops => new CreativeInfo(null, CreativeGroup::GROUP_SEED),
            $block instanceof Wood => new CreativeInfo(null, CreativeGroup::GROUP_LOG),
            $block instanceof Planks => new CreativeInfo(null, CreativeGroup::GROUP_PLANKS),
            $block instanceof Stair => new CreativeInfo(null, CreativeGroup::GROUP_STAIRS),
            $block instanceof Slab => new CreativeInfo(null, CreativeGroup::GROUP_SLAB),
            $block instanceof Wall => new CreativeInfo(null, CreativeGroup::GROUP_WALLS),
            $block instanceof Fence => new CreativeInfo(null, CreativeGroup::GROUP_FENCE),
            $block instanceof FenceGate => new CreativeInfo(null, CreativeGroup::GROUP_FENCE_GATE),
            $block instanceof PressurePlate => new CreativeInfo(null, CreativeGroup::GROUP_PRESSURE_PLATE),
            $block instanceof Button => new CreativeInfo(null, CreativeGroup::GROUP_BUTTONS),
            $block instanceof Door => new CreativeInfo(null, CreativeGroup::GROUP_DOOR),
            $block instanceof Trapdoor => new CreativeInfo(null, CreativeGroup::GROUP_TRAPDOOR),
            $block instanceof WallSign => new CreativeInfo(null, CreativeGroup::GROUP_HANDGING_SIGN),
            $block instanceof BaseSign => new CreativeInfo(null, CreativeGroup::GROUP_SIGN),
            $block instanceof Chest => new CreativeInfo(null, CreativeGroup::GROUP_CHEST),
            $block instanceof Anvil => new CreativeInfo(null, CreativeGroup::GROUP_ANVIL),
            $block instanceof ShulkerBox => new CreativeInfo(null, CreativeGroup::GROUP_SHULKER_BOX),
            $block instanceof Sapling => new CreativeInfo(null, CreativeGroup::GROUP_SAPLING),
            $block instanceof MobHead => new CreativeInfo(null, CreativeGroup::GROUP_SKULL),
            $block instanceof Candle => new CreativeInfo(null, CreativeGroup::GROUP_CANDLES),
            $block instanceof Bed => new CreativeInfo(null, CreativeGroup::GROUP_BED),
            $block instanceof Wool => new CreativeInfo(null, CreativeGroup::GROUP_WOOL),
            $block instanceof Carpet => new CreativeInfo(null, CreativeGroup::GROUP_WOOL_CARPET),
            $block instanceof Clay => new CreativeInfo(null, CreativeGroup::GROUP_STAINED_CLAY),
            $block instanceof Rail => new CreativeInfo(null, CreativeGroup::GROUP_RAIL),
            $block instanceof GlazedTerracotta => new CreativeInfo(null, CreativeGroup::GROUP_GLAZED_TERRACOTTA),
            $block instanceof Flower => new CreativeInfo(null, CreativeGroup::GROUP_FLOWER),
            $block instanceof ConcretePowder => new CreativeInfo(null, CreativeGroup::GROUP_CONCRETE_POWDER),
            $block instanceof Concrete => new CreativeInfo(null, CreativeGroup::GROUP_CONCRETE),

            $block instanceof NetherWartPlant => new CreativeInfo(CreativeCategory::NATURE, null),
            default => new CreativeInfo(CreativeCategory::CONSTRUCTION),
        };
    }
}
