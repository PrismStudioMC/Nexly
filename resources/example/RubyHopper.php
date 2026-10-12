<?php

namespace Nexly\Exemple;

use Nexly\Mappings\BlockMappings;
use pocketmine\block\Block;
use pocketmine\block\Hopper;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier as IID;
use pocketmine\item\ItemTypeIds;

class RubyHopper extends Hopper
{
    /**
     * Override to provide custom item representation.
     *
     * @return Item
     */
    public function asItem(): Item
    {
        return new class(new IID(ItemTypeIds::fromBlockTypeId($this->getTypeId())), "Ruby Hopper") extends Item {
            /**
             * @param int|null $clickedFace
             * @return Block
             */
            public function getBlock(?int $clickedFace = null): Block
            {
                return BlockMappings::getInstance()->getMapping("nexly:ruby_hopper")?->getBlock() ?? VanillaBlocks::AIR();
            }
        };
    }
}