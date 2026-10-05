<?php

namespace Nexly\Events\Impl;

use Nexly\Events\Event;
use Nexly\Items\Creative\CreativeInfo;
use Nexly\Items\NexlyItems;
use pocketmine\item\Item;

class ItemRegistryEvent extends Event
{
    private int $count = 0;

    /**
     * @param string $stringId
     * @param Item $item
     * @param CreativeInfo|null $creativeInfo
     * @return self
     */
    public function register(string $stringId, Item $item, CreativeInfo $creativeInfo = null): self
    {
        NexlyItems::register($stringId, $item, $creativeInfo);
        $this->count++;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
