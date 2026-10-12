<?php

namespace Nexly\Exemple;

use Nexly\Mappings\BlockMappings;
use pocketmine\block\Block;
use pocketmine\block\NetherWartPlant;
use pocketmine\block\utils\FortuneDropHelper;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier as IID;
use pocketmine\item\ItemTypeIds;

class RubyNetherWartPlant extends NetherWartPlant
{
    public function getDropsForCompatibleTool(Item $item): array
    {
        return [
            $this->asItem()->setCount($this->age >= self::MAX_AGE ? FortuneDropHelper::binomial($item, 1) : 1)
        ];
    }

    /**
     * @return Item
     */
    public function asItem(): Item
    {
        return new class(new IID(ItemTypeIds::fromBlockTypeId($this->getTypeId())), "Emerald Nether Wart") extends Item {
            /**
             * @param int|null $clickedFace
             * @return Block
             */
            public function getBlock(?int $clickedFace = null): Block
            {
                return BlockMappings::getInstance()->getMapping("nexly:ruby_nether_wart")?->getBlock() ?? VanillaBlocks::AIR();
            }
        };
    }
}