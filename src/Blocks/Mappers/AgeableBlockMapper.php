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
use Nexly\Blocks\Components\MaterialInstancesBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\Types\Material;
use Nexly\Blocks\Components\Types\RenderMethod;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;

class AgeableBlockMapper extends BlockMapper
{
	public function map(Builder $builder, Block $block, array $states) : Permutation
	{
		$age = $states[StateNames::AGE];

		return Permutation::create(MolangHelper::stateEquals(StateNames::AGE, $age))
			->addComponent(SelectionBoxBlockComponent::fromCrops($block, $age))
			->addComponent(new MaterialInstancesBlockComponent([
				new Material($builder->getName() . "_{$age}", renderMethod: RenderMethod::ALPHA_TEST)
			]));
	}
}
