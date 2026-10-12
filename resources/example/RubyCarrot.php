<?php

namespace Nexly\Exemple;

use Nexly\Blocks\LegacyItemBlockTrait;
use pocketmine\block\Carrot;
use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\item\Item;

class RubyCarrot extends Carrot
{
    use LegacyItemBlockTrait;

    public function getDropsForCompatibleTool(Item $item) : array{
        return [
            $this->asItem()->setCount($this->age >= self::MAX_AGE ? FortuneDropHelper::binomial($item, 1) : 1)
        ];
    }
}