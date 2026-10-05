<?php

namespace Nexly\Mappings;

use Nexly\Items\ItemBuilder;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\SingletonTrait;
use ReflectionException;

class ItemMappings
{
    use SingletonTrait;

    /**
     * @var array<string, array> Mapping of item string IDs to their numeric IDs.
     */
    private array $mappings = [];

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
     * Registers a new item mapping.
     *
     * @param ItemBuilder $builder
     * @param ItemTypeEntry $entry
     */
    public function registerMapping(ItemBuilder $builder, ItemTypeEntry $entry): void
    {
        if (isset($this->mappings[$entry->getStringId()])) {
            throw new \InvalidArgumentException("Item mapping for " . $entry->getStringId() . " is already registered");
        }

        $this->mappings[$entry->getStringId()] = ["builder" => $builder, "entry" => $entry];

        try {
            $this->registerEntry($entry);
        } catch (ReflectionException $e) {
            throw new \RuntimeException("Failed to register item entry: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Retrieves the mapping for a given item string ID.
     *
     * @param string $stringId
     * @return array|null
     */
    public function getMapping(string $stringId): ?array
    {
        return $this->mappings[$stringId] ?? null;
    }

    /**
     * Registers a new ItemTypeEntry to the TypeConverter's item type dictionary.
     *
     * @param ItemTypeEntry $entry
     * @return void
     * @throws ReflectionException
     */
    public static function registerEntry(ItemTypeEntry $entry): void
    {
        $stringId = $entry->getStringId();
        $numericId = $entry->getNumericId();

        $dictionary = TypeConverter::getInstance()->getItemTypeDictionary();
        $reflection = new \ReflectionClass($dictionary);

        $intToString = $reflection->getProperty("intToStringIdMap");
        /** @var int[] $value */
        $value = $intToString->getValue($dictionary);
        $intToString->setValue($dictionary, $value + [$numericId => $stringId]);

        $stringToInt = $reflection->getProperty("stringToIntMap");
        /** @var int[] $value */
        $value = $stringToInt->getValue($dictionary);
        $stringToInt->setValue($dictionary, $value + [$stringId => $numericId]);

        $itemTypes = $reflection->getProperty("itemTypes");
        $value = $itemTypes->getValue($dictionary);
        $value[] = $entry;
        $itemTypes->setValue($dictionary, $value);
    }
}
