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

namespace Nexly\Items\Components\Legacy;

use Attribute;
use Nexly\Items\Components\Legacy\Types\LegacyEffect;
use Nexly\Items\Components\Legacy\Types\LegacyItemCooldownType;
use Nexly\Items\Components\Legacy\Types\LegacyItemUseActionType;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use function array_map;
use function is_string;

#[Attribute(Attribute::TARGET_CLASS)]
class FoodComponent extends LegacyItemComponent
{
	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return LegacyComponentIds::FOOD->getValue();
	}

	/**
	 * @param float[]        $onUseRange
	 * @param LegacyEffect[] $effects
	 * @param int[]          $removeEffects
	 */
	public function __construct(
		protected int                     $nutrition,
		protected float                   $saturationModifier,
		protected bool                    $canAlwaysEat = false,
		protected LegacyItemCooldownType|\BackedEnum|string  $cooldownType = LegacyItemCooldownType::NONE,
		protected int                     $cooldownTick = 0,
		protected string                           $usingConvertsTo = "air",
		protected LegacyItemUseActionType $useActionType = LegacyItemUseActionType::NONE,
		protected array                   $onUseRange = [],
		protected array                   $effects = [],
		protected array                   $removeEffects = [],
	) {
	}

	/**
	 * Set the item that this food converts to when used.
	 */
	public function setUsingConvertsTo(Item $item) : void
	{
		[$rId] = ($converter = TypeConverter::getInstance())->getItemTranslator()->toNetworkId($item);
		if ($rId == null) {
			throw new \InvalidArgumentException("Item does not have a valid network ID");
		}

		$this->usingConvertsTo = $converter->getItemTypeDictionary()->fromIntId($rId);
	}

	/**
	 * Get the maximum damage value.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("nutrition", new IntTag($this->nutrition))
			->setTag("saturation_modifier", new FloatTag($this->saturationModifier))
			->setTag("can_always_eat", new ByteTag($this->canAlwaysEat))
			->setTag("cooldown_type", new StringTag(is_string($this->cooldownType) ? $this->cooldownType : $this->cooldownType->value))
			->setTag("cooldown_time", new IntTag($this->cooldownTick))
			->setTag("using_converts_to", CompoundTag::create()->setTag("name", new StringTag($this->usingConvertsTo)))
			->setTag("on_use_action", new IntTag($this->useActionType->getValue()))
			->setTag("on_use_range", new ListTag(array_map(fn (float $value) => new FloatTag($value), $this->onUseRange), NBT::TAG_Float))
			->setTag("effects", new ListTag(array_map(fn (LegacyEffect $effect) => $effect->toNBT(), $this->effects), NBT::TAG_Compound))
			->setTag("remove_effects", new ListTag(array_map(fn (int $value) => new IntTag($value), $this->removeEffects), NBT::TAG_Int));
	}
}
