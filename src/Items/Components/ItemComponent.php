<?php

namespace Nexly\Items\Components;

use pocketmine\nbt\tag\Tag;

abstract class ItemComponent
{
    abstract public static function getName(): string;
    abstract public function toNBT(): Tag;
}
