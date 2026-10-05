<?php

namespace Nexly\Blocks;

/**
 * @internal
 * @deprecated
 */
class AsyncInitialization
{
    /** @var array  */
    private static array $blocks = [];

    /**
     * Adds an asynchronous block definition.
     *
     * @param string $stringId
     * @param array $data
     * @return void
     */
    public static function addAsyncBlock(string $stringId, array $data): void
    {
        self::$blocks[$stringId] = $data;
    }

    /**
     * @return array
     */
    public static function getBlocks(): array
    {
        return self::$blocks;
    }
}
