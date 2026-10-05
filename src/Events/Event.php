<?php

namespace Nexly\Events;

use pocketmine\event\Cancellable;
use pocketmine\network\PacketHandlingException;

abstract class Event
{
    public function trigger(): void
    {
        $handlers = NexlyEventManager::getInstance()->getHandlersFor($this);

        try {
            foreach ($handlers as $k => $handler) {
                $ownerRef = $handler->getInstanceRef();
                if ($ownerRef !== null && $ownerRef->get() === null) {
                    continue;
                }

                if ($this instanceof Cancellable && $this->isCancelled() && !$handler->isHandleCancelled()) {
                    continue;
                }

                ($handler->getClosure())($this);
            }
        } catch (\Exception $e) {
            throw PacketHandlingException::wrap($e, "Exception occurred when handling " . $this::class);
        }
    }
}
