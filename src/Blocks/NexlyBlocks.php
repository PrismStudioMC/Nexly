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

namespace Nexly\Blocks;

use Closure;
use Nexly\Items\Creative\CreativeInfo;

class NexlyBlocks
{
	/**
	 * Registers a new block with the given string ID, block creation closure, and optional creative info.
	 */
	public static function register(string $stringId, Closure $block, ?CreativeInfo $creativeInfo = null, bool $addToCreative = true) : BlockBuilder
	{
		return self::buildComplex($stringId, $block, $creativeInfo)->register($addToCreative);
	}

	/**
	 * Builds a complex block with the given string ID, block creation closure, and optional creative info.
	 */
	public static function buildComplex(string $stringId, Closure $block, ?CreativeInfo $creativeInfo = null) : BlockBuilder
	{
		return BlockBuilder::create()
			->setStringId($stringId)
			->setBlock($block)
			->setCreativeInfo($creativeInfo);
	}
}
