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

namespace Nexly\Events;

use pocketmine\event\Cancellable;
use pocketmine\network\PacketHandlingException;

abstract class Event
{
	public function trigger() : void
	{
		$handlers = NexlyEventManager::getInstance()->getHandlersFor($this);

		try {
			foreach ($handlers as $k => $handler) {
				$ownerRef = $handler->getInstanceRef();
				if ($ownerRef !== null && $ownerRef->get() === null) {
					continue;
				}

				if ($this instanceof Cancellable && $this->isCancelled() && !$handler->isHandleCancelled()) {
					continue;
				}

				($handler->getClosure())($this);
			}
		} catch (\Exception $e) {
			throw PacketHandlingException::wrap($e, "Exception occurred when handling " . $this::class);
		}
	}
}
