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

namespace Nexly\Blocks\Vanilla;

use pocketmine\block\Block;
use pocketmine\block\GlassPane;
use pocketmine\block\Thin;
use pocketmine\block\utils\SupportType;
use pocketmine\block\Wall;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\math\Facing;
use function array_fill_keys;
use function array_keys;
use function array_values;

/**
 * Class NexlyGlassPane
 *
 * A custom glass pane block that extends the base GlassPane class and includes additional functionality
 * for reading state from the world, recalculating connections, and handling nearby block changes.
 *
 * @package Nexly\Blocks\Vanilla
 *
 * Minecraft does not allow us to reproduce identical glass panes.
 * In certain patterns, you will be able to pass through them because we cannot create collision boxes other than squares/rectangles.
 * @deprecated
 */
class NexlyGlassPane extends GlassPane
{
	/**
	 * Reads the state of the block from the world and updates its connections accordingly.
	 */
	public function readStateFromWorld() : Block
	{
		return $this;
	}

	/**
	 * Recalculates the connections of this fence to adjacent blocks.
	 */
	protected function recalculateConnections() : bool
	{
		$changed = 0;

		foreach (Facing::HORIZONTAL as $facing) {
			$block = $this->getSide($facing);
			if ($block instanceof Thin || $block instanceof Wall || $block->getSupportType(Facing::opposite($facing)) === SupportType::FULL) {
				if (!isset($this->connections[$facing])) {
					$this->connections[$facing] = true;
					$changed++;
				}
			} elseif (isset($this->connections[$facing])) {
				unset($this->connections[$facing]);
				$changed++;
			}
		}
		return $changed > 0;
	}

	/**
	 * Describes the block's state focusing solely on its connections.
	 */
	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void
	{
		$faces = array_keys($this->connections);
		$w->horizontalFacingFlags($faces);
		$this->connections = array_fill_keys(array_values($faces), true);
	}

	/**
	 * Called when a nearby block changes, triggering a recalculation of connections.
	 */
	public function onNearbyBlockChange() : void
	{
		if ($this->recalculateConnections()) {
			$this->position->getWorld()->setBlock($this->position, $this);
		}
	}
}
