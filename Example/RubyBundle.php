<?php

namespace Nexly\Exemple;

use Nexly\Items\Components\DataDriven\BundleInteractionItemComponent;
use Nexly\Items\Components\DataDriven\DataDriven;
use Nexly\Items\Components\DataDriven\Property\HoverTextColorProperty;
use Nexly\Items\Components\DataDriven\StorageItemComponent;
use Nexly\Items\Components\DataDriven\StorageWeightLimitItemComponent;
use Nexly\Items\Components\DataDriven\StorageWeightModifierItemComponent;
use Nexly\Items\Creative\CreativeGroup;
use Nexly\Items\Creative\CreativeInfo;
use Nexly\Recipes\Recipe;
use Nexly\Recipes\RecipeBuilder;
use Nexly\Recipes\Types\RecipeType;
use Nexly\Recipes\Types\ShapelessRecipe;
use pocketmine\crafting\ShapelessRecipeType;
use pocketmine\inventory\CreativeCategory;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

#[DataDriven]
#[StorageItemComponent(64)]
#[StorageWeightLimitItemComponent(64)]
#[StorageWeightModifierItemComponent(4)]
#[BundleInteractionItemComponent(12)]
#[HoverTextColorProperty(TextFormat::GREEN)]
#[CreativeInfo(CreativeCategory::EQUIPMENT, CreativeGroup::GROUP_BUNDLE)]

#[ShapelessRecipe(ShapelessRecipeType::CRAFTING, ["ruby", "emerald"], ["ruby_bundle"])]
class RubyBundle extends Item
{
    /**
     * @return int
     */
    public function getMaxStackSize(): int
    {
        return 1;
    }
}