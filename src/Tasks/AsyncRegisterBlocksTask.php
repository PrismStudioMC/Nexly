<?php

namespace Nexly\Tasks;

use Nexly\Blocks\BlockPalette;
use Nexly\Blocks\Components\AsyncBlockComponent;
use Nexly\Blocks\NexlyBlocks;
use Nexly\Blocks\Permutations\BlockProperty;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Blocks\Traits\BlockTrait;
use pmmp\thread\ThreadSafeArray;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\network\mcpe\protocol\serializer\NetworkNbtSerializer;
use pocketmine\scheduler\AsyncTask;

class AsyncRegisterBlocksTask extends AsyncTask
{
    protected ThreadSafeArray $numericIds;
    protected ThreadSafeArray $func;
    protected ThreadSafeArray $properties;
    protected ThreadSafeArray $permutations;
    protected ThreadSafeArray $components;
    protected ThreadSafeArray $traits;
    protected ThreadSafeArray $serializer;
    protected ThreadSafeArray $deserializer;

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
                ->mapSerializer($this->serializer[$stringId])
                ->mapDeserializer($this->deserializer[$stringId]);

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
                if (!is_string($identifier)) {
                    throw new \InvalidArgumentException("Invalid identifier for trait " . $identifier);
                }

                $nbt = $trait["nbt"] ?? null;
                if (!is_string($nbt)) {
                    throw new \InvalidArgumentException("Invalid nbt for trait " . $identifier);
                }

                $builder->addTrait(BlockTrait::fromNBT(
                    $identifier,
                    (new NetworkNbtSerializer())->read(base64_decode($nbt))->getTag()
                ));
            }

            $builder->register(false, false, true);
        }

        // Sort the block palette
        BlockPalette::getInstance()->apply();
    }
}
