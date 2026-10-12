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

use Nexly\Blocks\BlockBuilder;
use Nexly\Blocks\NexlyBlocks;
use Nexly\Events\Event;
use Nexly\Items\Creative\CreativeInfo;

class BlockRegistryEvent extends Event
{
	protected int $count = 0;

	public function register(string $stringId, \Closure $block, ?CreativeInfo $creativeInfo = null) : BlockBuilder
	{
		$this->count++;
		return NexlyBlocks::register($stringId, $block, $creativeInfo);
	}

	public function build(string $stringId, \Closure $block, ?CreativeInfo $creativeInfo = null) : BlockBuilder
	{
		$this->count++;
		return NexlyBlocks::buildComplex($stringId, $block, $creativeInfo);
	}

	public function getCount() : int
	{
		return $this->count;
	}
}
