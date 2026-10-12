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

namespace Nexly\Events\Impl;

use Nexly\Events\Event;
use Nexly\Recipes\MinecraftShape;
use Nexly\Recipes\NexlyRecipes;
use Nexly\Recipes\Types\ShapedRecipe;
use Nexly\Recipes\Types\ShapelessRecipe;
use pocketmine\crafting\ShapelessRecipeType;

class RecipeRegistryEvent extends Event
{
	protected int $shaped = 0;
	protected int $shapeless = 0;

	/**
	 * Registers a shaped recipe.
	 *
	 * @param MinecraftShape|string[] $shape
	 */
	public function registerShaped(MinecraftShape|array $shape, array $ingredients, array $outputs) : self
	{
		NexlyRecipes::getInstance()->addRecipe(fn () => new ShapedRecipe($shape, $ingredients, $outputs));
		$this->shaped++;
		return $this;
	}

	/**
	 * Registers a shapeless recipe.
	 *
	 * @return $this
	 */
	public function registerShapeless(array $ingredients, array $outputs, ShapelessRecipeType $type = ShapelessRecipeType::CRAFTING) : self
	{
		NexlyRecipes::getInstance()->addRecipe(fn () => new ShapelessRecipe($type, $ingredients, $outputs));
		$this->shapeless++;
		return $this;
	}

	public function getShaped() : int
	{
		return $this->shaped;
	}

	public function getShapeless() : int
	{
		return $this->shapeless;
	}
}
