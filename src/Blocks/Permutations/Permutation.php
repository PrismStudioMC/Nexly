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

namespace Nexly\Blocks\Permutations;

use Nexly\Blocks\Components\BlockComponent;
use pocketmine\nbt\tag\CompoundTag;

class Permutation
{
	/*** @var BlockComponent[] */
	protected array $components = [];

	public function __construct(
		protected string $condition
	) {
	}

	public static function create(string $condition = "") : Permutation
	{
		return new self($condition);
	}

	public function getCondition() : string
	{
		return $this->condition;
	}

	public function setCondition(string $condition) : self
	{
		$this->condition = $condition;
		return $this;
	}

	public function getComponents() : array
	{
		return $this->components;
	}

	/**
	 * @return $this
	 */
	public function addComponent(BlockComponent $component) : self
	{
		$this->components[$component->getName()] = $component;
		return $this;
	}

	/**
	 * Returns the permutation in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		$components = CompoundTag::create();
		foreach ($this->components as $name => $component) {
			$components->setTag($name, $component->toNBT());
		}

		return CompoundTag::create()
			->setString("condition", $this->condition)
			->setTag("components", $components);
	}
}
