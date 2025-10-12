<?php

namespace Nexly\Recipes\Types;

use Attribute;
use Nexly\Recipes\MinecraftShape;
use pocketmine\crafting\ExactRecipeIngredient;
use pocketmine\crafting\ShapedRecipe as ShapedRecipePM;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;

#[Attribute(Attribute::TARGET_CLASS)]
class ShapedRecipe implements Recipe
{
    /**
     * @param MinecraftShape|string[] $shape
     * @param Item|string[] $ingredients
     * @param Item|string[] $outputs
     */
    public function __construct(
        private MinecraftShape|array $shape,
        private readonly array       $ingredients,
        private readonly array       $outputs
    ) {
        if ($this->shape instanceof MinecraftShape) {
            $this->shape = $this->shape->toArray();
        }
    }

    /**
     * @return array
     */
    public function getShape(): array
    {
        return $this->shape;
    }

    /**
     * @return array
     */
    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    /**
     * @return string[]
     */
    public function getOutputs(): array
    {
        return $this->outputs;
    }

    /**
     * Builds and returns the ShapedRecipePM instance.
     *
     * @return ShapedRecipePM
     */
    public function build(): ShapedRecipePM
    {
        return new ShapedRecipePM(
            $this->shape,
            array_map(fn ($ingredient) => new ExactRecipeIngredient(is_string($ingredient) ? StringToItemParser::getInstance()->parse($ingredient) : $ingredient), $this->ingredients),
            array_map(fn ($output) => is_string($output) ? StringToItemParser::getInstance()->parse($output) : $output, $this->outputs),
        );
    }
}
