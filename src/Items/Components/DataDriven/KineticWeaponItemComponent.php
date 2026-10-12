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
use Nexly\Items\Components\DataDriven\KineticWeaponKineticEffectConditionsItemComponent as DamageConditions;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;

/**
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/itemreference/examples/itemcomponents/minecraft_kinetic_weapon?view=minecraft-bedrock-stable
 * @since 1.21.120
 * @internal
 */
#[Attribute(Attribute::TARGET_CLASS)]
class KineticWeaponItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected float $hitboxMargin = 0.0,
		protected int $min = 0,
		protected int $max = 3,
		protected float             $damageModifier = 0.0,
		protected float             $damageMultiplier = 1.0,
		protected int               $delay = 0,
		protected ?DamageConditions $damageConditions = null,
		protected ?DamageConditions $dismoutConditions = null,
		protected ?DamageConditions $knockbackConditions = null,
	) {
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::KINETIC_WEAPON->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		$nbt = CompoundTag::create();
		if ($this->damageConditions !== null) {
			$nbt = $nbt->setTag("damage_conditions", $this->damageConditions->toNBT());
		}

		if ($this->dismoutConditions !== null) {
			$nbt = $nbt->setTag("dismount_conditions", $this->dismoutConditions->toNBT());
		}

		if ($this->knockbackConditions !== null) {
			$nbt = $nbt->setTag("knockback_conditions", $this->knockbackConditions->toNBT());
		}

		return $nbt
			->setTag("hitbox_margin", new FloatTag($this->hitboxMargin))
			->setTag(
				"reach",
				CompoundTag::create()
				->setTag("min", new IntTag($this->min))
				->setTag("max", new IntTag($this->max))
			)
			->setTag("damage_modifier", new FloatTag($this->damageModifier))
			->setTag("damage_multiplier", new FloatTag($this->damageMultiplier))
			->setTag("delay", new IntTag($this->delay));
	}
}
