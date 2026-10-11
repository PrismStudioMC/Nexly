<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

class NWayVisualRotation
{
    /**
     * @param array<string, string>|list<array{AxisRotation|string, string}> $axes
     */
    public function __construct(
        protected array $axes
    ) {
        $normalized = [];

        foreach ($axes as $axis => $state) {
            if (is_int($axis) && is_array($state)) {
                [$axis, $state] = $state;
            }

            $key = $axis instanceof AxisRotation ? $axis->value : (string) $axis;
            $normalized[$key] = (string) $state;
        }

        $this->axes = $normalized;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create();
        foreach ($this->axes as $axis => $state) {
            $nbt->setTag($axis, new StringTag($state));
        }
        return $nbt;
    }
}
