<?php

namespace Nexly\Recipes\Types;

use Attribute;
use pocketmine\crafting\ExactRecipeIngredient;
use pocketmine\crafting\ShapelessRecipe as ShapelessRecipePM;
use pocketmine\crafting\ShapelessRecipeType;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;

#[Attribute(Attribute::TARGET_CLASS)]
class ShapelessRecipe implements Recipe
{
    /**
     * @param ShapelessRecipeType $type
     * @param Item|string[] $ingredients
     * @param Item|string[] $outputs
     */
    public function __construct(
        private readonly ShapelessRecipeType $type,
        private readonly array               $ingredients,
        private readonly array               $outputs
    ) {
    }

    /**
     * @return ShapelessRecipeType
     */
    public function getType(): ShapelessRecipeType
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    /**
     * @return array
     */
    public function getOutputs(): array
    {
        return $this->outputs;
    }

    /**
     * Builds and returns the ShapedRecipePM instance.
     *
     * @return ShapelessRecipePM
     */
    public function build(): ShapelessRecipePM
    {
        return new ShapelessRecipePM(
            array_map(fn ($ingredient) => new ExactRecipeIngredient(is_string($ingredient) ? StringToItemParser::getInstance()->parse($ingredient) : $ingredient), $this->ingredients),
            array_map(fn ($output) => is_string($output) ? StringToItemParser::getInstance()->parse($output) : $output, $this->outputs),
            $this->type
        );
    }
}
