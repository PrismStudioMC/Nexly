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

namespace Nexly\Events\Attribute;

use Attribute;
use pocketmine\event\EventPriority as PMPriority;

#[Attribute(Attribute::TARGET_METHOD)]
class HandlePriority
{
	public function __construct(
		protected int|string $priority = PMPriority::NORMAL
	) {
	}

	public function getPriority() : int|string
	{
		return $this->priority;
	}
}
