<?php

namespace Nexly\Tasks;

use Engine\Blocks\SchemaManager;
use Nexly\Blocks\BlockPalette;
use Nexly\Blocks\NexlyBlocks;
use pmmp\thread\ThreadSafeArray;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\scheduler\AsyncTask;

final class AsyncRegisterBlocksTask extends AsyncTask
{
    private ThreadSafeArray $blockFuncs;
    private ThreadSafeArray $serializer;
    private ThreadSafeArray $deserializer;

    /**
     * @param Closure[] $blocks
     * @phpstan-param array<string, array{(Closure(int): Block), (Closure(BlockStateWriter): Block), (Closure(Block): BlockStateReader)}> $blocks
     */
    public function __construct(array $blocks)
    {
        $this->blockFuncs = new ThreadSafeArray();
        $this->serializer = new ThreadSafeArray();
        $this->deserializer = new ThreadSafeArray();

        foreach ($blocks as $identifier => [$blockFunc, $serializer, $deserializer]) {
            $this->blockFuncs[$identifier] = $blockFunc;
            $this->serializer[$identifier] = $serializer;
            $this->deserializer[$identifier] = $deserializer;
        }
    }

    public function onRun(): void
    {
        foreach ($this->blockFuncs as $identifier => $blockFunc) {
            // We do not care about the model or creative inventory data in other threads since it is unused outside of
            // the main thread.
            NexlyBlocks::buildComplex($identifier, $blockFunc)
                ->setSerializer($this->serializer[$identifier])
                ->setDeserializer($this->deserializer[$identifier])
            ->register(false);
        }

        // Sort the block palette
        BlockPalette::getInstance()->apply();
    }
}
