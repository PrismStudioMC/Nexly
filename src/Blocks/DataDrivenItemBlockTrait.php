<?php

namespace Nexly\Blocks;

use Nexly\Items\Components\DataDriven\DataDriven;
use Nexly\Items\Components\DataDriven\Types\IgnoreBlockVisual;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

trait DataDrivenItemBlockTrait
{
    /**
     * @return Item
     */
    public function asItem(): Item
    {
        return new #[DataDriven] #[IgnoreBlockVisual] class (new ItemIdentifier(-$this->getTypeId()), $this->getName(), clone $this) extends Item {
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
