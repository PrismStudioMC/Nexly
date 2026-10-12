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
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class RecordItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected string $sound,
		protected int $duration = 20,
		protected int $signal = 1
	) {
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::RECORD->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("sound_event", new StringTag($this->sound))
			->setTag("duration", new IntTag($this->duration))
			->setTag("comparator_signal", new IntTag($this->signal));
	}
}
