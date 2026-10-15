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

namespace Nexly\Items;

use Nexly\Helpers\OverrideHelper as Helper;
use Nexly\Items\Components\DataDriven\DataDrivenItemBuilder;
use Nexly\Items\Components\DataDriven\DataDrivenItemComponent;
use Nexly\Items\Components\DataDriven\Property\PropertyItemComponent;
use Nexly\Items\Components\Legacy\LegacyItemBuilder;
use Nexly\Items\Components\Legacy\LegacyItemComponent;
use Nexly\Items\Creative\CreativeInfo;
use pocketmine\item\Item;
use function str_contains;
use function str_starts_with;

class NexlyItems
{
	/**
	 * Register a custom item with a string identifier.
	 */
	public static function register(string $stringId, Item $item, CreativeInfo $creativeInfo = null) : void
	{
		if (str_contains("minecraft:", $stringId)) {
			throw new \InvalidArgumentException("Custom item string ID cannot contain the 'minecraft:' namespace.");
		}

		$builder = self::builder($stringId, $item, $creativeInfo);
		$builder->register();
	}

	public static function override(string $stringId, Item $item, ?\Closure $serializer = null, ?\Closure $deserializer = null) : void
	{
		if (!str_starts_with($stringId, "minecraft:")) {
			throw new \InvalidArgumentException("The override string ID '$stringId' must start with the 'minecraft:' namespace.");
		}

		$old = Helper::validateAndGetVanillaItem($stringId, $item);

		$builder = self::builder($stringId, $item, CreativeInfo::hidden())
			->setSerializer($serializer)
			->setDeserializer($deserializer);
		$builder->register();

		$member = Helper::findVanillaItemMemberName($old);
		Helper::injectVanillaItemMember($member, $item);

		$creativeIndex = Helper::getCreativeIndex($old);
		Helper::replaceCreativeItemEntry($creativeIndex, $item);
	}

	public static function builder(string $stringId, Item $item, CreativeInfo $creativeInfo = null) : ItemBuilder
	{
		$version = ItemVersion::fromItem($item);
		if ($version->equals(ItemVersion::LEGACY)) {
			$builder = self::registerLegacy($stringId, $item, $creativeInfo);
		} else {
			$builder = self::registerDataDriven($stringId, $item, $creativeInfo);
		}

		return $builder;
	}

	/**
	 * Register a custom item with a string identifier.
	 */
	public static function registerLegacy(string $stringId, Item $item, CreativeInfo $creativeInfo = null, bool $autoload = true) : LegacyItemBuilder
	{
		$version = ItemVersion::fromItem($item);
		if ($version !== ItemVersion::LEGACY) {
			throw new \InvalidArgumentException("Item must be a legacy item.");
		}

		$builder = LegacyItemBuilder::create();
		$builder->setStringId($stringId);
		$builder->setNumericId($item->getTypeId());
		$builder->setItem($item);
		$builder->setCreativeInfo($creativeInfo);

		if ($autoload) {
			$builder->loadFromItems();
		}

		$reflection = new \ReflectionClass($item);
		$attributes = $reflection->getAttributes();

		foreach ($attributes as $attribute) {
			$instance = $attribute->newInstance();
			if ($instance instanceof LegacyItemComponent) {
				$builder->addComponent($instance);
			}
		}

		return $builder;
	}

	/**
	 * Register a custom item with a string identifier.
	 */
	public static function registerDataDriven(string $stringId, Item $item, CreativeInfo $creativeInfo = null, bool $initProperties = true, bool $initComponents = true) : DataDrivenItemBuilder
	{
		$version = ItemVersion::fromItem($item);
		if ($version !== ItemVersion::DATA_DRIVEN) {
			throw new \InvalidArgumentException("Item must be a data driven item.");
		}

		$builder = DataDrivenItemBuilder::create();
		$builder->setStringId($stringId);
		$builder->setNumericId($item->getTypeId());
		$builder->setItem($item);
		$builder->setCreativeInfo($creativeInfo);

		if ($initProperties) {
			$builder->loadProperties();
		}
		if ($initComponents) {
			$builder->loadComponents();
		}

		$reflection = new \ReflectionClass($item);
		$attributes = $reflection->getAttributes();

		foreach ($attributes as $attribute) {
			$instance = $attribute->newInstance();
			if ($instance instanceof PropertyItemComponent) {
				$builder->addProperty($instance);
			} elseif ($instance instanceof DataDrivenItemComponent) {
				$builder->addComponent($instance);
			}
		}

		return $builder;
	}
}
