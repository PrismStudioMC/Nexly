<?php

namespace Nexly\Mappings;

use Nexly\Blocks\BlockMapping;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;
use pocketmine\utils\SingletonTrait;

class BlockMappings
{
    use SingletonTrait;

    /**
     * @var array<string, BlockMapping> Mapping of block string IDs to their BlockMapping instances.
     */
    private array $mappings = [];
    private array $entries = [];

    /**
     * @return int
     */
    public function nextRuntimeId(): int
    {
        return 10000 + count($this->entries);
    }

    /**
     * @return array
     */
    public function getMappings(): array
    {
        return $this->mappings;
    }

    /**
     * @param array $mappings
     */
    public function setMappings(array $mappings): void
    {
        $this->mappings = $mappings;
    }

    /**
     * @return array
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * @param array $entries
     */
    public function setEntries(array $entries): void
    {
        $this->entries = $entries;
    }

    /**
     * Registers a new block mapping.
     *
     * @param BlockMapping $mapping
     */
    public function registerMapping(BlockMapping $mapping): void
    {
        if (isset($this->mappings[$mapping->getStringId()])) {
            throw new \InvalidArgumentException("Item mapping for " . $mapping->getStringId() . " is already registered");
        }

        $this->mappings[$mapping->getStringId()] = $mapping;
        $this->registerEntry($mapping->getEntry());
    }

    /**
     * Retrieves the mapping for a given block string ID.
     *
     * @param string $stringId
     * @return BlockMapping|null
     */
    public function getMapping(string $stringId): ?BlockMapping
    {
        return $this->mappings[$stringId] ?? null;
    }

    /**
     * Registers a block palette entry.
     *
     * @param BlockPaletteEntry $entry
     * @return void
     */
    public function registerEntry(BlockPaletteEntry $entry): void
    {
        $this->entries[] = $entry;
    }
}
