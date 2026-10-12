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

namespace Nexly\Items\Components\Legacy\Types;

use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\nbt\tag\CompoundTag;

class LegacyEffect
{
	/**
	 * LegacyEffect constructor.
	 */
	public function __construct(
		protected int    $id,
		protected string $name,
		protected string $descriptionId,
		protected int    $duration = -1,
		protected int    $amplifier = 0,
		protected float $chance = 100.0
	) {
	}

	/**
	 * Create a LegacyEffect from an EffectInstance.
	 */
	public static function from(EffectInstance $effectInstance) : self
	{
		return new self(
			EffectIdMap::getInstance()->toId($effectInstance->getType()),
			$effectInstance->getType()->getName(),
			"",
			$effectInstance->getDuration(),
			$effectInstance->getAmplifier(),
			100.0
		);
	}

	public function getId() : int
	{
		return $this->id;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getDescriptionId() : string
	{
		return $this->descriptionId;
	}

	public function getDuration() : int
	{
		return $this->duration;
	}

	public function getAmplifier() : int
	{
		return $this->amplifier;
	}

	public function getChance() : float
	{
		return $this->chance;
	}

	/**
	 * Convert the effect to a CompoundTag for NBT representation.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setInt("id", $this->getId())
			->setString("name", $this->getName())
			->setString("description_id", $this->getDescriptionId())
			->setInt("duration", $this->getDuration())
			->setInt("amplifier", $this->getAmplifier())
			->setFloat("chance", $this->getChance());
	}
}
