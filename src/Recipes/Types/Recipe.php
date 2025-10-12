<?php

namespace Nexly\Recipes\Types;

use pocketmine\crafting\CraftingRecipe;

interface Recipe
{
    public function build(): CraftingRecipe;
}
