<?php

namespace Nexly\Events;

use Nexly\Events\Attribute\HandleCancelled;
use Nexly\Events\Attribute\HandlePriority;
use pocketmine\event\EventPriority;
use pocketmine\utils\SingletonTrait;
use ReflectionException;
use ReflectionFunction;
use WeakReference;

class NexlyEventManager
{
    use SingletonTrait;

    private const PRIORITY_ORDER = [
        EventPriority::LOWEST,
        EventPriority::LOW,
        EventPriority::NORMAL,
        EventPriority::HIGH,
        EventPriority::HIGHEST,
        EventPriority::MONITOR,
    ];

    /** @var EventReflected[] */
    private array $handlerCaches = [];

    /**
     * Register an event listener.
     *
     * @param string $event
     * @param callable $callback
     * @param object|null $instance
     * @return void
     */
    public function listen(string $event, callable $callback, object $instance = null): void
    {
        if (!\class_exists($event) || !\is_subclass_of($event, Event::class)) {
            throw new \InvalidArgumentException("Event class must exist and be a subclass of " . Event::class . " (got: {$event})");
        }

        $callable = $callback;
        if ($instance !== null && \is_string($callback)) {
            if (!\method_exists($instance, $callback)) {
                throw new \InvalidArgumentException("Method {$callback} does not exist on " . $instance::class);
            }
            $callable = [$instance, $callback];
        }

        if (!\is_callable($callable)) {
            throw new \InvalidArgumentException("Callback is not callable");
        }

        $closure = $callable(...);
        $rf = new ReflectionFunction($closure);

        if ($rf->isStatic() && $instance !== null) {
            throw new \InvalidArgumentException("Cannot bind instance to static method");
        }
        if (!$rf->isStatic() && $instance === null) {
            throw new \InvalidArgumentException("Cannot use non-static method without instance");
        }

        $this->handlerCaches[$event] ??= [];

        $handleCancelled = false;
        $priority = EventPriority::NORMAL;

        foreach ($rf->getAttributes() as $attribute) {
            $attrInstance = $attribute->newInstance();
            if ($attrInstance instanceof HandlePriority) {
                $priority = $attrInstance->getPriority();
            } elseif ($attrInstance instanceof HandleCancelled) {
                $handleCancelled = true;
            }
        }

        $this->handlerCaches[$event] ??= [];
        $this->handlerCaches[$event][$priority] ??= [];
        $this->handlerCaches[$event][$priority][] = new EventReflected(
            $closure,
            $rf,
            $rf->isStatic(),
            $rf->isStatic() ? null : WeakReference::create($instance),
            $rf->isStatic() ? null : spl_object_id($instance),
            $priority,
            $handleCancelled,
        );
    }

    /**
     * Remove event listener(s). If both $callback and $instance are null, all listeners for the event will be removed.
     *
     * @param string $event
     * @param callable|null $callback
     * @param object|null $instance
     * @return void
     * @throws ReflectionException
     */
    public function deafen(string $event, callable $callback = null, object $instance = null): void
    {
        if (!\class_exists($event) || !\is_subclass_of($event, Event::class)) {
            throw new \InvalidArgumentException("Event class must exist and be a subclass of " . Event::class . " (got: {$event})");
        }
        if (!isset($this->handlerCaches[$event])) {
            return;
        }

        if ($callback === null && $instance === null) {
            unset($this->handlerCaches[$event]);
            return;
        }

        if ($callback === null && $instance !== null) {
            $iid = spl_object_id($instance);
            foreach (self::PRIORITY_ORDER as $p) {
                if (!isset($this->handlerCaches[$event][$p])) {
                    continue;
                }
                $this->handlerCaches[$event][$p] = array_values(array_filter(
                    $this->handlerCaches[$event][$p],
                    static fn (EventReflected $er) => $er->getInstanceId() !== $iid
                ));
            }
            return;
        }

        $callable = $callback;
        if ($instance !== null && \is_string($callback)) {
            if (!\method_exists($instance, $callback)) {
                throw new \InvalidArgumentException("Method {$callback} does not exist on " . $instance::class);
            }
            $callable = [$instance, $callback];
        }
        if (!\is_callable($callable)) {
            throw new \InvalidArgumentException("Callback is not callable");
        }

        $tClosure = $callable(...);
        $tRf = new ReflectionFunction($tClosure);
        $targetFile = $tRf->getFileName();
        $targetStart = $tRf->getStartLine();
        $targetEnd = $tRf->getEndLine();
        $iidFilter = $instance ? spl_object_id($instance) : null;

        foreach (self::PRIORITY_ORDER as $p) {
            if (!isset($this->handlerCaches[$event][$p])) {
                continue;
            }

            $this->handlerCaches[$event][$p] = array_values(array_filter(
                $this->handlerCaches[$event][$p],
                static function (EventReflected $er) use ($targetFile, $targetStart, $targetEnd, $iidFilter): bool {
                    if ($iidFilter !== null && $er->getInstanceId() !== $iidFilter) {
                        return true;
                    }
                    $erf = $er->getRef();
                    return !(
                        $erf->getFileName() === $targetFile
                        && $erf->getStartLine() === $targetStart
                        && $erf->getEndLine() === $targetEnd
                    );
                }
            ));
        }
    }

    /**
     * Get handlers for a specific event instance, ordered by priority (LOWEST → … → MONITOR).
     * Returns a flat array of EventReference.
     *
     * @return EventReflected[]
     */
    public function getHandlersFor(object $event): array
    {
        if (!$event instanceof Event) {
            throw new \InvalidArgumentException("Event must be an instance of " . Event::class);
        }
        $class = $event::class;
        if ($class === Event::class) {
            throw new \InvalidArgumentException("Cannot get handlers for abstract " . Event::class);
        }

        $byP = $this->handlerCaches[$class] ?? [];
        $flat = [];
        foreach (self::PRIORITY_ORDER as $p) {
            if (!empty($byP[$p])) {
                array_push($flat, ...$byP[$p]);
            }
        }
        return $flat;
    }
}
