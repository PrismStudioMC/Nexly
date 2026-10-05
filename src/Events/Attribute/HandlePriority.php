<?php

namespace Nexly\Events\Attribute;

use Attribute;
use pocketmine\event\EventPriority as PMPriority;

#[Attribute(Attribute::TARGET_METHOD)]
class HandlePriority
{
    /**
     * @param int|string $priority
     */
    public function __construct(
        protected int|string $priority = PMPriority::NORMAL
    ) {
    }

    /**
     * @return int|string
     */
    public function getPriority(): int|string
    {
        return $this->priority;
    }
}
