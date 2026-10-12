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

namespace Nexly\Blocks;

use Nexly\Items\Components\DataDriven\DataDriven;
use Nexly\Items\Components\DataDriven\Types\ItemBlockVisual;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

trait DataDrivenItemBlockTrait
{
	public function asItem() : Item
	{
		return new #[DataDriven] #[ItemBlockVisual] class (new ItemIdentifier(-$this->getTypeId()), $this->getName(), clone $this) extends Item {
			public function __construct(
				ItemIdentifier $identifier,
				string                         $name,
				protected Block $block,
			) {
				parent::__construct($identifier, $name);
			}

			public function getBlock(?int $clickedFace = null) : Block
			{
				return (clone $this->block);
			}
		};
	}
}
