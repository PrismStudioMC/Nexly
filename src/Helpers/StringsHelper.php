<?php

namespace Nexly\Helpers;

use pocketmine\block\utils\DyeColor;

class StringsHelper
{
    /**
     * @param string $stringId
     * @return array
     */
    public static function wood(string $stringId): array
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

    /**
     * @param string $stringId
     * @return array
     */
    public static function furnace(string $stringId): array
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

    /**
     * @param string $stringId
     * @param DyeColor|null $from
     * @param DyeColor|null $to
     * @return string
     */
    public static function recolor(string $stringId, ?DyeColor $from = null, ?DyeColor $to = null): string
    {
        if ($from !== null && $to !== null) {
            return str_replace(strtolower($from->name), strtolower($to->name), $stringId);
        }

        return $stringId;
    }
}
