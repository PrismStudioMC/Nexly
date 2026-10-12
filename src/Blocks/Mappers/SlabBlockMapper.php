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
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\GeometryBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\TransformationBlockComponent;
use Nexly\Blocks\Components\Types\BoxCollision;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\math\Vector3;

class SlabBlockMapper extends BlockMapper
{
	public function map(Builder $builder, Block $block, array $states) : Permutation
	{
		$half = $states[StateNames::MC_VERTICAL_HALF];
		$permutation = Permutation::create(MolangHelper::stateEquals(StateNames::MC_VERTICAL_HALF, $half));

		if ($half === "double") {
			$permutation->addComponent(new GeometryBlockComponent());
			$permutation->addComponent(new CollisionBoxBlockComponent(true));
			$permutation->addComponent(new SelectionBoxBlockComponent(true));
		} else {
			$permutation->addComponent(new CollisionBoxBlockComponent(true, [BoxCollision::SLAB()]));
			$permutation->addComponent(new SelectionBoxBlockComponent(true, [BoxCollision::SLAB()]));

			$yTranslation = $half === StateValues::MC_VERTICAL_HALF_TOP ? 0.25 : -0.25;
			$permutation->addComponent(new TransformationBlockComponent(translation: new Vector3(0, $yTranslation, 0)));
		}

		return $permutation;
	}
}
