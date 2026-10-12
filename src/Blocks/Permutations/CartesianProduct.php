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

namespace Nexly\Blocks\Permutations;

use function array_map;
use function array_product;
use function count;
use function current;
use function next;
use function reset;

class CartesianProduct
{
	/**
	 * Returns an 2-dimensional array containing all possible combinations of the provided arrays using the cartesian
	 * product (https://en.wikipedia.org/wiki/Cartesian_product).
	 */
	public static function get(array $arrays) : array
	{
		$result = [];
		$count = count($arrays) - 1;
		$combinations = array_product(array_map(static fn (array $array) => count($array), $arrays));
		for ($i = 0; $i < $combinations; $i++) {
			$result[] = array_map(static fn (array $array) => current($array), $arrays);
			for ($j = $count; $j >= 0; $j--) {
				if (next($arrays[$j])) {
					break;
				}
				reset($arrays[$j]);
			}
		}
		return $result;
	}
}
