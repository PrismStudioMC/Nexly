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

namespace Nexly\Blocks\Mappers;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Permutations\Permutation;
use pocketmine\block\Block;
use function array_shift;

abstract class BlockMapper
{
	public function __construct()
	{
	}

	public function apply(Builder $builder, Block $block, array $properties, array $currentStates = []) : void
	{
		if (empty($properties)) {
			$builder->addPermutation($this->map($builder, $block, $currentStates));
			return;
		}

		$property = array_shift($properties);
		$propertyName = $property->getName();

		foreach ($property->getValues() as $value) {
			$newStates = $currentStates;
			$newStates[$propertyName] = $value;

			$this->apply($builder, $block, $properties, $newStates);
		}
	}

	abstract public function map(Builder $builder, Block $block, array $states) : Permutation;
}
