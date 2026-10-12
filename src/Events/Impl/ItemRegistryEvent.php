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

namespace Nexly\Events\Impl;

use Nexly\Events\Event;
use Nexly\Items\Creative\CreativeInfo;
use Nexly\Items\NexlyItems;
use pocketmine\item\Item;

class ItemRegistryEvent extends Event
{
	protected int $count = 0;

	public function register(string $stringId, Item $item, CreativeInfo $creativeInfo = null) : self
	{
		NexlyItems::register($stringId, $item, $creativeInfo);
		$this->count++;
		return $this;
	}

	public function getCount() : int
	{
		return $this->count;
	}
}
