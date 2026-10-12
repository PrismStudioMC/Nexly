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

use function array_filter;
use function array_map;
use function count;
use function implode;
use function is_bool;
use function is_float;
use function is_string;
use function reset;
use function sprintf;
use function str_replace;

class MolangHelper
{
	private function __construct()
	{
	}

	public static function value(mixed $value) : string
	{
		if (is_bool($value)) {
			return $value ? "1" : "0";
		}
		if (is_string($value)) {
			$escaped = str_replace("'", "\'", $value);
			return "'{$escaped}'";
		}
		if (is_float($value)) {
			return sprintf('%F', $value);
		}
		return (string) $value;
	}

	public static function query(string $queryName, mixed ...$args) : string
	{
		if (count($args) === 0) {
			return "q.{$queryName}";
		}

		$formattedArgs = array_map(fn ($arg) => self::value($arg), $args);
		return "q.{$queryName}(" . implode(", ", $formattedArgs) . ")";
	}

	public static function variable(string $variableName) : string
	{
		return "v.{$variableName}";
	}

	public static function state(string $stateName) : string
	{
		return self::query('block_state', $stateName);
	}

	public static function stateEquals(string $stateName, mixed $value) : string
	{
		return self::state($stateName) . " == " . self::value($value);
	}

	public static function stateNotEquals(string $stateName, mixed $value) : string
	{
		return self::state($stateName) . " != " . self::value($value);
	}

	public static function blockProperty(string $propertyName) : string
	{
		return self::query('block_property', $propertyName);
	}

	public static function anyTag(string ...$tags) : string
	{
		return self::query('any_tag', ...$tags);
	}

	public static function allTags(string ...$tags) : string
	{
		return self::query('all_tags', ...$tags);
	}

	public static function isItemNameAny(string ...$names) : string
	{
		return self::query('is_item_name_any', ...$names);
	}

	public static function gt(string $expression, mixed $value) : string
	{
		return "{$expression} > " . self::value($value);
	}

	public static function gte(string $expression, mixed $value) : string
	{
		return "{$expression} >= " . self::value($value);
	}

	public static function lt(string $expression, mixed $value) : string
	{
		return "{$expression} < " . self::value($value);
	}

	public static function lte(string $expression, mixed $value) : string
	{
		return "{$expression} <= " . self::value($value);
	}

	public static function and(string ...$expressions) : string
	{
		$filtered = array_filter($expressions, fn (string $expr) => $expr !== "");
		if (count($filtered) === 0) {
			return "";
		}
		if (count($filtered) === 1) {
			return reset($filtered);
		}

		return "(" . implode(") && (", $filtered) . ")";
	}

	public static function or(string ...$expressions) : string
	{
		$filtered = array_filter($expressions, fn (string $expr) => $expr !== "");
		if (count($filtered) === 0) {
			return "";
		}
		if (count($filtered) === 1) {
			return reset($filtered);
		}

		return "(" . implode(") || (", $filtered) . ")";
	}

	public static function not(string $expression) : string
	{
		if ($expression === "") {
			return "";
		}
		return "!(" . $expression . ")";
	}

	public static function ternary(string $condition, string|int|float $ifTrue, string|int|float $ifFalse) : string
	{
		return "({$condition}) ? ({$ifTrue}) : ({$ifFalse})";
	}

	public static function math(string $mathFunc, mixed ...$args) : string
	{
		$formattedArgs = array_map(fn ($arg) => self::value($arg), $args);
		return "math.{$mathFunc}(" . implode(", ", $formattedArgs) . ")";
	}

	public static function abs(mixed $value) : string
	{
		return self::math('abs', $value);
	}

	public static function clamp(mixed $value, mixed $min, mixed $max) : string
	{
		return self::math('clamp', $value, $min, $max);
	}

	public static function min(mixed ...$values) : string
	{
		return self::math('min', ...$values);
	}

	public static function max(mixed ...$values) : string
	{
		return self::math('max', ...$values);
	}

	public static function round(mixed $value) : string
	{
		return self::math('round', $value);
	}

	public static function floor(mixed $value) : string
	{
		return self::math('floor', $value);
	}

	public static function ceil(mixed $value) : string
	{
		return self::math('ceil', $value);
	}

	public static function mod(mixed $value, mixed $denominator) : string
	{
		return self::math('mod', $value, $denominator);
	}

	public static function pow(mixed $base, mixed $exponent) : string
	{
		return self::math('pow', $base, $exponent);
	}

	public static function random(mixed $min, mixed $max) : string
	{
		return self::math('random', $min, $max);
	}

	public static function randomInteger(mixed $min, mixed $max) : string
	{
		return self::math('random_integer', $min, $max);
	}
}
