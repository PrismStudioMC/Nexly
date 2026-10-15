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

namespace Nexly\Helpers;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\inventory\CreativeInventory;
use pocketmine\inventory\CreativeInventoryEntry;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use ReflectionProperty;
use function sprintf;

final class OverrideHelper
{
	public static function findVanillaBlockMemberName(Block $block) : string
	{
		foreach (VanillaBlocks::getAll() as $member => $object) {
			if ($object->getTypeId() === $block->getTypeId()) {
				return $member;
			}
		}

		throw new \InvalidArgumentException("Failed to find vanilla block member name for block ID: " . $block->getTypeId());
	}

	public static function injectVanillaBlockMember(string $member, Block $block) : void
	{
		$ref = new ReflectionProperty(VanillaBlocks::class, "members");
		$ref->setValue($ref->getValue() + [$member => $block]);
	}

	public static function validateAndGetVanillaItem(string $stringId, Item $newItem) : Item
	{
		$vanillaItem = StringToItemParser::getInstance()->parse($stringId);
		if ($vanillaItem === null) {
			throw new \InvalidArgumentException("Failed to parse vanilla item for override with string ID: '$stringId'");
		}

		if (!$newItem instanceof ($vanillaItem::class)) {
			throw new \InvalidArgumentException(sprintf(
				"The override item must be an instance of %s, got %s.",
				$vanillaItem::class,
				$newItem::class
			));
		}

		if ($newItem->getTypeId() !== $vanillaItem->getTypeId()) {
			throw new \InvalidArgumentException(sprintf(
				"Type ID mismatch for override '%s': expected %d, got %d. Both items must share the same type ID.",
				$stringId,
				$vanillaItem->getTypeId(),
				$newItem->getTypeId()
			));
		}

		return $vanillaItem;
	}

	public static function findVanillaItemMemberName(Item $item) : string
	{
		foreach (VanillaItems::getAll() as $member => $object) {
			if ($object->getTypeId() == $item->getTypeId()) {
				return $member;
			}
		}

		throw new \InvalidArgumentException("Failed to find vanilla item member name for item: " . $item->getName());
	}

	public static function injectVanillaItemMember(string $member, Item $item) : void
	{
		$ref = new ReflectionProperty(VanillaItems::class, "members");
		$ref->setValue($ref->getValue() + [$member => $item]);
	}

	public static function getCreativeIndex(Item $item) : int
	{
		$index = CreativeInventory::getInstance()->getItemIndex($item);
		if ($index < 0) {
			throw new \InvalidArgumentException("Failed to get creative index for item: " . $item->getTypeId());
		}

		return $index;
	}

	public static function replaceCreativeItemEntry(int $index, Item $newItem) : void
	{
		$creativeInventory = CreativeInventory::getInstance();
		$ref = new ReflectionProperty(CreativeInventory::class, "creative");
		/** @var array<int, CreativeInventoryEntry> $entries */
		$entries = $ref->getValue($creativeInventory);

		if (!isset($entries[$index])) {
			throw new \InvalidArgumentException("No creative inventory entry found at index $index.");
		}

		$entry = $entries[$index];
		if (!$entry instanceof CreativeInventoryEntry) {
			throw new \InvalidArgumentException("The entry at index $index is not a valid CreativeInventoryEntry instance.");
		}

		$entries[$index] = new CreativeInventoryEntry($newItem, $entry->getCategory(), $entry->getGroup());
		$ref->setValue($creativeInventory, $entries);
	}
}
