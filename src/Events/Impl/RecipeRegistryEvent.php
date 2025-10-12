<?php

namespace Nexly\Events\Impl;

use Nexly\Events\Event;
use Nexly\Recipes\MinecraftShape;
use Nexly\Recipes\NexlyRecipes;
use Nexly\Recipes\Types\ShapedRecipe;
use Nexly\Recipes\Types\ShapelessRecipe;
use pocketmine\crafting\ShapelessRecipeType;

class RecipeRegistryEvent extends Event
{
    private int $shaped = 0;
    private int $shapeless = 0;

    /**
     * Registers a shaped recipe.
     *
     * @param MinecraftShape|string[] $shape
     * @param array $ingredients
     * @param array $outputs
     * @return self
     */
    public function registerShaped(MinecraftShape|array $shape, array $ingredients, array $outputs): self
    {
        NexlyRecipes::getInstance()->addRecipe(fn () => new ShapedRecipe($shape, $ingredients, $outputs));
        $this->shaped++;
        return $this;
    }

    /**
     * Registers a shapeless recipe.
     *
     * @param array $ingredients
     * @param array $outputs
     * @param ShapelessRecipeType $type
     * @return $this
     */
    public function registerShapeless(array $ingredients, array $outputs, ShapelessRecipeType $type = ShapelessRecipeType::CRAFTING): self
    {
        NexlyRecipes::getInstance()->addRecipe(fn () => new ShapelessRecipe($type, $ingredients, $outputs));
        $this->shapeless++;
        return $this;
    }

    /**
     * @return int
     */
    public function getShaped(): int
    {
        return $this->shaped;
    }

    /**
     * @return int
     */
    public function getShapeless(): int
    {
        return $this->shapeless;
    }
}
