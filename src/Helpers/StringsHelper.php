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

use pocketmine\block\utils\DyeColor;
use function count;
use function explode;
use function str_contains;
use function str_replace;
use function strrpos;
use function strtolower;
use function substr_replace;

class StringsHelper
{
	public static function wood(string $stringId) : array
	{
		if (str_contains($stringId, '_stripped_')) {
			$strippedId = $stringId;
			$unstrippedId = str_replace('_stripped_', '_', $stringId);
			return [$unstrippedId, $strippedId];
		}

		$unstrippedId = $stringId;
		$lastUnderscorePos = strrpos($stringId, '_');
		if ($lastUnderscorePos !== false) {
			$strippedId = substr_replace($stringId, '_stripped_', $lastUnderscorePos, 1);
		} else {
			$strippedId = $stringId . "_stripped";
		}

		return [$unstrippedId, $strippedId];
	}

	public static function furnace(string $stringId) : array
	{
		if (str_contains($stringId, 'lit_')) {
			$litId = $stringId;
			$unlitId = str_replace('lit_', '', $stringId);
			return [$unlitId, $litId];
		}

		$unlitId = $stringId;

		$parts = explode(':', $stringId);
		$namespace = $parts[0];
		$name = $parts[1] ?? '';

		$nameParts = explode('_', $name, 2);

		if (count($nameParts) === 2) {
			$litId = $namespace . ':' . $nameParts[0] . '_lit_' . $nameParts[1];
		} else {
			$litId = $namespace . ':lit_' . $name;
		}

		return [$unlitId, $litId];
	}

	public static function recolor(string $stringId, ?DyeColor $from = null, ?DyeColor $to = null) : string
	{
		if ($from !== null && $to !== null) {
			return str_replace(strtolower($from->name), strtolower($to->name), $stringId);
		}

		return $stringId;
	}
}
