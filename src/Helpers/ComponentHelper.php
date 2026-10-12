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
use pocketmine\block\tile\Container;
use pocketmine\block\tile\Tile;
use pocketmine\block\tile\TileFactory;
use pocketmine\item\VanillaItems as Items;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\world\World;
use ReflectionClass;
use function get_class;

final class ComponentHelper
{
	/** @var array<string, array{hasTile: bool, slotCount: int|null}> */
	private static array $tileCache = [];

	/** @var array<string, bool> */
	private static array $interactCache = [];

	public static function hasTile(Block $block, ?int &$containerSlotCount = null) : bool
	{
		$class = get_class($block);

		if (isset(self::$tileCache[$class])) {
			$containerSlotCount = self::$tileCache[$class]['slotCount'];
			return self::$tileCache[$class]['hasTile'];
		}

		$containerSlotCount = null;
		$hasTile = false;
		$tileClass = $block->getIdInfo()->getTileClass();

		if ($tileClass !== null) {
			if (!TileFactory::getInstance()->isRegistered($tileClass)) {
				throw new \InvalidArgumentException("Tile class '{$tileClass}' is not registered");
			}

			try {
				$world = (new ReflectionClass(World::class))->newInstanceWithoutConstructor();
				/** @var Tile $tileInstance */
				$tileInstance = new $tileClass($world, Vector3::zero());

				if ($tileInstance instanceof Container) {
					$containerSlotCount = $tileInstance->getInventory()->getSize();
				}
				$hasTile = true;
			} catch (\Throwable $e) {
				// ignore it
			}
		}

		self::$tileCache[$class] = [
			'hasTile' => $hasTile,
			'slotCount' => $containerSlotCount
		];

		return $hasTile;
	}

	public static function hasInteract(Block $block) : bool
	{
		$class = get_class($block);

		if (isset(self::$interactCache[$class])) {
			return self::$interactCache[$class];
		}

		$hasInteract = false;
		$returnedItems = [];

		try {
			if ($block->onInteract(Items::AIR(), Facing::UP, Vector3::zero(), null, $returnedItems)) {
				$hasInteract = true;
			}
		} catch (\Throwable $e) {
			$hasInteract = true;
		}

		self::$interactCache[$class] = $hasInteract;
		return $hasInteract;
	}
}
