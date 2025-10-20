<?php

namespace Nexly\Tasks;

use Nexly\Blocks\BlockPalette;
use Nexly\Blocks\Components\AsyncBlockComponent;
use Nexly\Blocks\NexlyBlocks;
use Nexly\Blocks\Permutations\BlockProperty;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Blocks\Traits\MinecraftTrait;
use Nexly\Blocks\Traits\State;
use Nexly\Blocks\Traits\TraitIds;
use pmmp\thread\ThreadSafeArray;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\scheduler\AsyncTask;

final class AsyncRegisterBlocksTask extends AsyncTask
{
    private ThreadSafeArray $numericIds;
    private ThreadSafeArray $func;
    private ThreadSafeArray $properties;
    private ThreadSafeArray $permutations;
    private ThreadSafeArray $components;
    private ThreadSafeArray $traits;
    private ThreadSafeArray $serializer;
    private ThreadSafeArray $deserializer;

    /**
     * @param Closure[] $blocks
     * @phpstan-param array<string, array{(Closure(int): Block), (Closure(BlockStateWriter): Block), (Closure(Block): BlockStateReader)}> $blocks
     */
    public function __construct(array $blocks)
    {
        $this->numericIds = new ThreadSafeArray();
        $this->func = new ThreadSafeArray();
        $this->properties = new ThreadSafeArray();
        $this->permutations = new ThreadSafeArray();
        $this->components = new ThreadSafeArray();
        $this->traits = new ThreadSafeArray();
        $this->serializer = new ThreadSafeArray();
        $this->deserializer = new ThreadSafeArray();

        foreach ($blocks as $stringId => [$numericId, $func, $properties, $permutations, $components, $traits, $serializer, $deserializer]) {
            $this->numericIds[$stringId] = $numericId;
            $this->func[$stringId] = $func;
            $this->properties[$stringId] = $properties;
            $this->permutations[$stringId] = $permutations;
            $this->components[$stringId] = $components;
            $this->traits[$stringId] = $traits;
            $this->serializer[$stringId] = $serializer;
            $this->deserializer[$stringId] = $deserializer;
        }
    }

    public function onRun(): void
    {
        foreach ($this->func as $stringId => $blockFunc) {
            // We do not care about the model or creative inventory data in other threads since it is unused outside of
            // the main thread.
            $builder = NexlyBlocks::buildComplex($stringId, $blockFunc)
                ->setNumericId($this->numericIds[$stringId])
                ->setSerializer($this->serializer[$stringId])
                ->setDeserializer($this->deserializer[$stringId]);

            foreach (json_decode($this->properties[$stringId], true) as $property) {
                $builder->addProperty(new BlockProperty($property["name"], $property["values"]));
            }

            foreach (json_decode($this->permutations[$stringId], true) as $permutation) {
                $p = Permutation::create($permutation["condition"]);

                foreach ($permutation["components"] as $component) {
                    $p->addComponent(new AsyncBlockComponent($component["name"], $component["nbt"]));
                }

                $builder->addPermutation($p);
            }

            foreach (json_decode($this->components[$stringId], true) as $component) {
                $builder->addComponent(new AsyncBlockComponent($component["name"], $component["nbt"]));
            }

            foreach (json_decode($this->traits[$stringId], true) as $trait) {
                $identifier = $trait["identifier"] ?? null;
                if(!is_string($identifier)) {
                    throw new \InvalidArgumentException("Invalid identifier for trait " . $identifier);
                }

                $rotationOffset = $trait["rotationOffset"] ?? null;
                if(!is_numeric($rotationOffset)) {
                    throw new \InvalidArgumentException("Invalid rotationOffset for trait " . $rotationOffset);
                }

                $state = $trait["state"] ?? null;
                if(!is_array($state)) {
                    throw new \InvalidArgumentException("Invalid state for trait " . $state);
                }

                $cardinal = $state["cardinal"] ?? null;
                if(!is_bool($cardinal)) {
                    throw new \InvalidArgumentException("Invalid cardinal for trait " . $cardinal);
                }

                $facing = $state["facing"] ?? null;
                if(!is_bool($facing)) {
                    throw new \InvalidArgumentException("Invalid facing for trait " . $facing);
                }

                $builder->addTrait(new MinecraftTrait(TraitIds::fromString($identifier), $rotationOffset, new State($cardinal, $facing)));
            }

            $builder->register(false, false);
        }

        // Sort the block palette
        BlockPalette::getInstance()->apply();
    }
}
