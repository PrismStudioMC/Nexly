<?php

/*
 *  _   _           _
 * | \ | | _____  _| |_   _
 * |  \| |/ _ \ \/ / | | | |
 * | |\  |  __/>  <| | |_| |
 * |_| \_|\___/_/\_\_|\__, |
 *                    |___/
 *
 * Nexly - A modern PocketMine-MP framework for custom blocks and items.
 *
 * This project is free software: you can redistribute it and/or modify
 * it under the terms of the Apache License, Version 2.0 as published by
 * the Apache Software Foundation.
 *
 * @author Zwuiix-cmd
 * @link   https://github.com/Zwuiix-cmd/Nexly
 *
 *
 */

namespace Nexly\Recipes\Types;

use Attribute;
use Nexly\Recipes\MinecraftShape;
use pocketmine\crafting\ExactRecipeIngredient;
use pocketmine\crafting\ShapedRecipe as ShapedRecipePM;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use function array_map;
use function is_string;

#[Attribute(Attribute::TARGET_CLASS)]
class ShapedRecipe implements Recipe
{
	/**
	 * @param MinecraftShape|string[] $shape
	 * @param Item|string[]           $ingredients
	 * @param Item|string[]           $outputs
	 */
	public function __construct(
		protected MinecraftShape|array $shape,
		protected array       $ingredients,
		protected array       $outputs
	) {
		if ($this->shape instanceof MinecraftShape) {
			$this->shape = $this->shape->toArray();
		}
	}

	public function getShape() : array
	{
		return $this->shape;
	}

	public function getIngredients() : array
	{
		return $this->ingredients;
	}

	/**
	 * @return string[]
	 */
	public function getOutputs() : array
	{
		return $this->outputs;
	}

	/**
	 * Builds and returns the ShapedRecipePM instance.
	 */
	public function build() : ShapedRecipePM
	{
		return new ShapedRecipePM(
			$this->shape,
			array_map(fn ($ingredient) => new ExactRecipeIngredient(is_string($ingredient) ? StringToItemParser::getInstance()->parse($ingredient) : $ingredient), $this->ingredients),
			array_map(fn ($output) => is_string($output) ? StringToItemParser::getInstance()->parse($output) : $output, $this->outputs),
		);
	}
}
