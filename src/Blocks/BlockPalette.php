<?php

/*
 *  _   _           _
 * | \ | | _____  _| |_   _
 * |  \| |/ _ \ \/ / | | | |
 * | |\  |  __/>  <| | |_| |
 * |_| \_|\___/_/\_\_|\__, |
 *                    |___/
 *
 * Nexly - A modern PocketMine-MP framework for custom blocks and items.
 *
 * This project is free software: you can redistribute it and/or modify
 * it under the terms of the Apache License, Version 2.0 as published by
 * the Apache Software Foundation.
 *
 * @author Zwuiix-cmd
 * @link   https://github.com/Zwuiix-cmd/Nexly
 *
 *
 */

namespace Nexly\Blocks;

use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\nbt\LittleEndianNbtSerializer;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\TreeRoot;
use pocketmine\network\mcpe\convert\BlockStateDictionaryEntry;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\Utils;
use ReflectionProperty;
use function count;
use function hash;
use function hexdec;
use function intval;
use function ksort;

class BlockPalette
{
	use SingletonTrait;

	/** @var BlockStateDictionaryEntry[] */
	protected array $states = [];

	public function __construct()
	{
	}

	/**
	 * @return BlockStateDictionaryEntry[]
	 */
	public function getStates() : array
	{
		return $this->states;
	}

	/**
	 * Inserts the provided state in to the correct position of the palette.
	 */
	public function insertState(BlockStateDictionaryEntry $entry) : void
	{
		if (($name = $entry->getStateName()) === "") {
			throw new \RuntimeException("Block state must contain a StringTag called 'name'");
		}
		$this->states[] = $entry;
	}

	/**
	 * Inserts the provided state in to the correct position of the palette.
	 */
	public function insertStates(array $entries) : void
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
	 */
	protected function sort(array $states, ?array &$sortedStates, ?array &$stateDataToStateIdLookup) : void
	{
		if ($stateDataToStateIdLookup === null) {
			$stateDataToStateIdLookup = [];
		}
		if ($sortedStates === null) {
			$sortedStates = [];
		}

		foreach ($states as $name => $blockStates) {
			$numberState = count($blockStates);

			foreach ($blockStates as $_ => $blockState) {
				$data = BlockStateDictionaryEntry::decodeStateProperties($blockState->getRawStateProperties());
				ksort($data);

				$test = CompoundTag::create();
				foreach (Utils::stringifyKeys($data) as $key => $state) {
					$test->setTag($key, $state);
				}

				$tag = CompoundTag::create()
					->setString("name", $blockState->getStateName())
					->setTag("states", $test);

				$stateId = self::fnv1a32Nbt($tag);
				if ($numberState === 1) {
					$stateDataToStateIdLookup[$name] = $stateId;
				} else {
					$stateDataToStateIdLookup[$name][$blockState->getRawStateProperties()] = $stateId;
				}

				$sortedStates[$stateId] = $blockState;
			}
		}
	}

	public static function fnv1a32Nbt(CompoundTag $tag) : int
	{
		if ($tag->getString("name", "") === "minecraft:unknown") {
			return -2;
		}

		$nbtStream = new LittleEndianNbtSerializer();
		$binaryNBT = $nbtStream->write(new TreeRoot($tag));

		return self::fnv1a32($binaryNBT);
	}

	protected static function fnv1a32(string $str) : int
	{
		$hashHex = hash('fnv1a32', $str);
		$hashInt = intval(hexdec($hashHex));
		if ($hashInt > 0x7FFFFFFF) {
			$hashInt -= 0x100000000;
		}

		return $hashInt;
	}

	/**
	 * Sorts the block palette to match the order of the Java edition.
	 */
	public function apply() : void
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
