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

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\ItemAmmunition;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use function array_map;

#[Attribute(Attribute::TARGET_CLASS)]
class ShooterItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected array $ammunitions = [],
		protected bool  $chargeOnDraw = false,
		protected float $maxDrawDuration = 0.0,
		protected bool  $scalePowerByDrawDuration = true
	) {
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::SHOOTER->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("charge_on_draw", new ByteTag($this->chargeOnDraw))
			->setTag("max_draw_duration", new FloatTag($this->maxDrawDuration))
			->setTag("scale_power_by_draw_duration", new ByteTag($this->scalePowerByDrawDuration))
			->setTag("ammunition", new ListTag(array_map(fn (ItemAmmunition $ammunition) => $ammunition->toNBT(), $this->ammunitions), NBT::TAG_Compound));
	}
}
