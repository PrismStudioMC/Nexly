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
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use function round;

/**
 * @deprecated
 */
#[Attribute(Attribute::TARGET_CLASS)]
class RenderOffsetsItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected int $resolution = 16,
	) {
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::RENDER_OFFSETS->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		$mainHand_fp = round(0.039 * 16 / $this->resolution, 8);
		$offhand_fp = round(0.065 * 16 / $this->resolution, 8);
		$mainHand_tp = $offhand_tp = round(0.0965 * 16 / $this->resolution, 8);

		$scale = function (float $s) : ListTag {
			return new ListTag([
				new FloatTag($s),
				new FloatTag($s),
				new FloatTag($s),
			]);
		};

		return CompoundTag::create()
			->setTag(
				"main_hand",
				CompoundTag::create()
				->setTag(
					"first_person",
					CompoundTag::create()
					->setTag("scale", $scale($mainHand_fp))
				)
				->setTag(
					"third_person",
					CompoundTag::create()
					->setTag("scale", $scale($mainHand_tp))
				)
			)
			->setTag(
				"off_hand",
				CompoundTag::create()
				->setTag(
					"first_person",
					CompoundTag::create()
					->setTag("scale", $scale($offhand_fp))
				)
				->setTag(
					"third_person",
					CompoundTag::create()
					->setTag("scale", $scale($offhand_tp))
				)
			);
	}
}
