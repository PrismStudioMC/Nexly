<?php

namespace Nexly\Blocks;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

trait LegacyItemBlockTrait
{
    /**
     * @return Item
     */
    public function asItem(): Item
    {
        return new class (new ItemIdentifier(-$this->getTypeId()), $this->getName(), clone $this) extends Item {
            public function __construct(
                ItemIdentifier $identifier,
                string                         $name,
                private readonly Block $block,
            ) {
                parent::__construct($identifier, $name);
            }

            /**
             * @param int|null $clickedFace
             * @return Block
             */
            public function getBlock(?int $clickedFace = null): Block
            {
                return (clone $this->block);
            }
        };
    }
}
