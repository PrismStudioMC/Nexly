<?php

namespace Nexly\Helpers;

class MolangHelper
{
    private function __construct()
    {
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function value(mixed $value): string
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

    /**
     * @param string $queryName
     * @param mixed ...$args
     * @return string
     */
    public static function query(string $queryName, mixed ...$args): string
    {
        if (count($args) === 0) {
            return "q.{$queryName}";
        }

        $formattedArgs = array_map(fn ($arg) => self::value($arg), $args);
        return "q.{$queryName}(" . implode(", ", $formattedArgs) . ")";
    }

    /**
     * @param string $variableName
     * @return string
     */
    public static function variable(string $variableName): string
    {
        return "v.{$variableName}";
    }

    /**
     * @param string $stateName
     * @return string
     */
    public static function state(string $stateName): string
    {
        return self::query('block_state', $stateName);
    }

    /**
     * @param string $stateName
     * @param mixed $value
     * @return string
     */
    public static function stateEquals(string $stateName, mixed $value): string
    {
        return self::state($stateName) . " == " . self::value($value);
    }

    /**
     * @param string $stateName
     * @param mixed $value
     * @return string
     */
    public static function stateNotEquals(string $stateName, mixed $value): string
    {
        return self::state($stateName) . " != " . self::value($value);
    }

    /**
     * @param string $propertyName
     * @return string
     */
    public static function blockProperty(string $propertyName): string
    {
        return self::query('block_property', $propertyName);
    }

    /**
     * @param string ...$tags
     * @return string
     */
    public static function anyTag(string ...$tags): string
    {
        return self::query('any_tag', ...$tags);
    }

    /**
     * @param string ...$tags
     * @return string
     */
    public static function allTags(string ...$tags): string
    {
        return self::query('all_tags', ...$tags);
    }

    /**
     * @param string ...$names
     * @return string
     */
    public static function isItemNameAny(string ...$names): string
    {
        return self::query('is_item_name_any', ...$names);
    }

    /**
     * @param string $expression
     * @param mixed $value
     * @return string
     */
    public static function gt(string $expression, mixed $value): string
    {
        return "{$expression} > " . self::value($value);
    }

    /**
     * @param string $expression
     * @param mixed $value
     * @return string
     */
    public static function gte(string $expression, mixed $value): string
    {
        return "{$expression} >= " . self::value($value);
    }

    /**
     * @param string $expression
     * @param mixed $value
     * @return string
     */
    public static function lt(string $expression, mixed $value): string
    {
        return "{$expression} < " . self::value($value);
    }

    /**
     * @param string $expression
     * @param mixed $value
     * @return string
     */
    public static function lte(string $expression, mixed $value): string
    {
        return "{$expression} <= " . self::value($value);
    }

    /**
     * @param string ...$expressions
     * @return string
     */
    public static function and(string ...$expressions): string
    {
        $filtered = array_filter($expressions, fn (string $expr) => $expr !== "");
        if (count($filtered) === 0) return "";
        if (count($filtered) === 1) return reset($filtered);

        return "(" . implode(") && (", $filtered) . ")";
    }

    /**
     * @param string ...$expressions
     * @return string
     */
    public static function or(string ...$expressions): string
    {
        $filtered = array_filter($expressions, fn (string $expr) => $expr !== "");
        if (count($filtered) === 0) return "";
        if (count($filtered) === 1) return reset($filtered);

        return "(" . implode(") || (", $filtered) . ")";
    }

    /**
     * @param string $expression
     * @return string
     */
    public static function not(string $expression): string
    {
        if ($expression === "") return "";
        return "!(" . $expression . ")";
    }

    /**
     * @param string $condition
     * @param string|int|float $ifTrue
     * @param string|int|float $ifFalse
     * @return string
     */
    public static function ternary(string $condition, string|int|float $ifTrue, string|int|float $ifFalse): string
    {
        return "({$condition}) ? ({$ifTrue}) : ({$ifFalse})";
    }

    /**
     * @param string $mathFunc
     * @param mixed ...$args
     * @return string
     */
    public static function math(string $mathFunc, mixed ...$args): string
    {
        $formattedArgs = array_map(fn ($arg) => self::value($arg), $args);
        return "math.{$mathFunc}(" . implode(", ", $formattedArgs) . ")";
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function abs(mixed $value): string
    {
        return self::math('abs', $value);
    }

    /**
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     * @return string
     */
    public static function clamp(mixed $value, mixed $min, mixed $max): string
    {
        return self::math('clamp', $value, $min, $max);
    }

    /**
     * @param mixed ...$values
     * @return string
     */
    public static function min(mixed ...$values): string
    {
        return self::math('min', ...$values);
    }

    /**
     * @param mixed ...$values
     * @return string
     */
    public static function max(mixed ...$values): string
    {
        return self::math('max', ...$values);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function round(mixed $value): string
    {
        return self::math('round', $value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function floor(mixed $value): string
    {
        return self::math('floor', $value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function ceil(mixed $value): string
    {
        return self::math('ceil', $value);
    }

    /**
     * @param mixed $value
     * @param mixed $denominator
     * @return string
     */
    public static function mod(mixed $value, mixed $denominator): string
    {
        return self::math('mod', $value, $denominator);
    }

    /**
     * @param mixed $base
     * @param mixed $exponent
     * @return string
     */
    public static function pow(mixed $base, mixed $exponent): string
    {
        return self::math('pow', $base, $exponent);
    }

    /**
     * @param mixed $min
     * @param mixed $max
     * @return string
     */
    public static function random(mixed $min, mixed $max): string
    {
        return self::math('random', $min, $max);
    }

    /**
     * @param mixed $min
     * @param mixed $max
     * @return string
     */
    public static function randomInteger(mixed $min, mixed $max): string
    {
        return self::math('random_integer', $min, $max);
    }
}