<?php

namespace Nexly\Blocks;

use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\network\mcpe\convert\BlockStateDictionaryEntry;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\SingletonTrait;
use ReflectionProperty;

final class BlockPalette
{
    use SingletonTrait;

    /** @var BlockStateDictionaryEntry[] */
    private array $states = [];

    public function __construct()
    {
    }

    /**
     * @return BlockStateDictionaryEntry[]
     */
    public function getStates(): array
    {
        return $this->states;
    }

    /**
     * Inserts the provided state in to the correct position of the palette.
     */
    public function insertState(BlockStateDictionaryEntry $entry): void
    {
        if (($name = $entry->getStateName()) === "") {
            throw new \RuntimeException("Block state must contain a StringTag called 'name'");
        }
        $this->states[] = $entry;
    }

    /**
     * Inserts the provided state in to the correct position of the palette.
     */
    public function insertStates(array $entries): void
    {
        foreach ($entries as $entry) {
            if (($entry->getStateName()) === "") {
                throw new \RuntimeException("Block state must contain a StringTag called 'name'");
            }
        }

        foreach ($entries as $entry) {
            $this->states[] = $entry;
        }
    }

    /**
     * Sorts the states using the fnv164 hash of their names.
     *
     * @param array $states
     * @param array|null $sortedStates
     * @param array|null $stateDataToStateIdLookup
     * @return void
     */
    private function sort(array $states, ?array &$sortedStates, ?array &$stateDataToStateIdLookup): void
    {
        if ($stateDataToStateIdLookup === null) {
            $stateDataToStateIdLookup = [];
        }
        if ($sortedStates === null) {
            $sortedStates = [];
        }

        $names = array_keys($states);
        // As of 1.18.30, blocks are sorted using a fnv164 hash of their names.
        usort($names, static fn (string $a, string $b) => strcmp(hash("fnv164", $a), hash("fnv164", $b)));
        $sortedStates = [];
        $stateId = 0;
        $stateDataToStateIdLookup = [];
        foreach ($names as $_ => $name) {
            // With the sorted list of names, we can now go back and add all the states for each block in the correct order.
            foreach ($states[$name] as $__ => $state) {
                $sortedStates[$stateId] = $state;
                if (count($states[$name]) === 1) {
                    $stateDataToStateIdLookup[$name] = $stateId;
                } else {
                    $stateDataToStateIdLookup[$name][$state->getRawStateProperties()] = $stateId;
                }
                $stateId++;
            }
        }
    }

    /**
     * Sorts the block palette to match the order of the Java edition.
     *
     * @return void
     */
    public function apply(): void
    {
        $translator = $instance = TypeConverter::getInstance()->getBlockTranslator();
        $dictionary = $instance->getBlockStateDictionary();

        $bedrockKnownStates = new ReflectionProperty($dictionary, "states");
        $stateDataToStateIdLookup = new ReflectionProperty($dictionary, "stateDataToStateIdLookup");
        $idMetaToStateIdLookupCache = new ReflectionProperty($dictionary, "idMetaToStateIdLookupCache");
        $fallbackStateId = new ReflectionProperty($instance, "fallbackStateId");
        $networkIdCache = new ReflectionProperty($instance, "networkIdCache");
        $states = [];

        foreach ($dictionary->getStates() as $state) {
            $states[$state->getStateName()][] = $state;
        }

        // To sort the block palette we first have to split the palette up in to groups of states. We only want to sort
        // using the name of the block, and keeping the order of the existing states.
        foreach ($this->getStates() as $state) {
            $states[$state->getStateName()][] = $state;
        }
        $sortedStates = [];
        $stateDataToStateIdLookupValue = [];
        $this->sort($states, $sortedStates, $stateDataToStateIdLookupValue);
        $dictionary = $translator->getBlockStateDictionary();
        $bedrockKnownStates->setValue($dictionary, $sortedStates);
        $stateDataToStateIdLookup->setValue($dictionary, $stateDataToStateIdLookupValue);
        $idMetaToStateIdLookupCache->setValue($dictionary, null); //set this to null so pm can create a new cache
        $networkIdCache->setValue($translator, []); //set this to empty-array so pm can create a new cache
        $fallbackStateId->setValue(
            $translator,
            $stateDataToStateIdLookupValue[BlockTypeNames::INFO_UPDATE] ??
            throw new AssumptionFailedError(BlockTypeNames::INFO_UPDATE . " should always exist")
        );
    }
}
