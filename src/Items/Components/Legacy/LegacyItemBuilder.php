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

use Nexly\Items\ItemBuilder;
use Nexly\Items\ItemVersion;
use pocketmine\block\BlockTypeTags;
use pocketmine\block\Crops;
use pocketmine\block\Flower;
use pocketmine\block\NetherWartPlant;
use pocketmine\block\Sapling;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\FoodSource;
use pocketmine\item\ConsumableItem;
use pocketmine\item\Durable;
use pocketmine\item\Tool;
use pocketmine\nbt\tag\CompoundTag;

class LegacyItemBuilder extends ItemBuilder
{
	/** @var LegacyItemComponent[] */
	protected array $components = [];

	public static function create() : LegacyItemBuilder
	{
		return new self();
	}

	/**
	 * Get the item version for legacy items.
	 */
	public static function getVersion() : ItemVersion
	{
		return ItemVersion::LEGACY;
	}

	/**
	 * @return LegacyItemComponent[]
	 */
	public function getComponents() : array
	{
		return $this->components;
	}

	/**
	 * Check if a component exists by its name.
	 */
	public function hasComponent(string $name) : bool
	{
		return isset($this->components[$name]);
	}

	/**
	 * Add a LegacyComponent to the builder.
	 *
	 * @return $this
	 */
	public function addComponent(LegacyItemComponent $component) : self
	{
		$this->components[$component::getName()] = $component;
		return $this;
	}

	/**
	 * Remove a component by its name.
	 */
	public function removeComponent(string $name) : void
	{
		unset($this->components[$name]);
	}

	/**
	 * Create a LegacyItemBuilder from an Item instance.
	 */
	public function toNBT() : CompoundTag
	{
		$componentsTag = CompoundTag::create();
		foreach ($this->components as $component) {
			$componentsTag->setTag($component::getName(), $component->toNBT());
		}

		return CompoundTag::create()
			->setTag("components", $componentsTag);
	}

	/**
	 * Load components from the item instance.
	 *
	 * @return $this
	 */
	public function loadFromItems() : self
	{
		$item = $this->getItem();
		$this->addComponent(new MaxStackSizeComponent($item->getMaxStackSize()));
		$this->addComponent(new StackedByDataComponent(false));
		$this->addComponent(new HandEquippedComponent($item instanceof Tool));

		if ($item instanceof Durable) {
			$this->addComponent(new MaxDamageComponent($item->getMaxDurability()));
		}

		if ($item instanceof ConsumableItem) {
			if ($item instanceof FoodSource) {
				$this->addComponent(new FoodComponent(
					$item->getFoodRestore(),
					$item->getFoodRestore() > 0 ? $item->getSaturationRestore() / ($item->getFoodRestore() * 2) : 0.0,
					!$item->requiresHunger(),
					cooldownType: $item->getCooldownTag() ?? $this->getStringId(),
					cooldownTick: $item->getCooldownTicks(),
				));
			} else {
				$this->addComponent(new FoodComponent(
					0.0,
					0.0,
					true,
					cooldownType: $item->getCooldownTag() ?? $this->getStringId(),
					cooldownTick: $item->getCooldownTicks(),
				));
			}

			$this->addComponent(new UseDurationComponent(32));
		}

		$block = $item->getBlock();
		if ($block instanceof Crops) {
			$this->addComponent(SeedComponent::fromBlocks($block, VanillaBlocks::FARMLAND()));
		} elseif ($block instanceof NetherWartPlant) {
			$this->addComponent(SeedComponent::fromBlocks($block, VanillaBlocks::SOUL_SAND()));
		} elseif ($block instanceof Flower || $block instanceof Sapling) {
			$this->addComponent(SeedComponent::fromTags($block, [BlockTypeTags::DIRT, BlockTypeTags::MUD, BlockTypeTags::NYLIUM]));
		}
		return $this;
	}
}
