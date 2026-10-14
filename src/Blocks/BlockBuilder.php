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

use Closure;
use Generator;
use Nexly\Blocks\Components\BlockComponent;
use Nexly\Blocks\Components\BlockComponentIds;
use Nexly\Blocks\Components\BlockEntityBlockComponent;
use Nexly\Blocks\Components\BreathabilityBlockComponent;
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\ConnectionRuleComponent;
use Nexly\Blocks\Components\CraftingTableBlockComponent;
use Nexly\Blocks\Components\DestructibleByExplosionBlockComponent;
use Nexly\Blocks\Components\DestructibleByMiningBlockComponent;
use Nexly\Blocks\Components\DisplayNameBlockComponent;
use Nexly\Blocks\Components\EntityFallOnBlockComponent;
use Nexly\Blocks\Components\FlammableBlockComponent;
use Nexly\Blocks\Components\FrictionBlockComponent;
use Nexly\Blocks\Components\GeometryBlockComponent;
use Nexly\Blocks\Components\InstrumentSoundBlockComponent;
use Nexly\Blocks\Components\ItemVisualBlockComponent;
use Nexly\Blocks\Components\LightDampeningBlockComponent;
use Nexly\Blocks\Components\LightEmissionBlockComponent;
use Nexly\Blocks\Components\MapColorBlockComponent;
use Nexly\Blocks\Components\MaterialInstancesBlockComponent;
use Nexly\Blocks\Components\MovableBlockComponent;
use Nexly\Blocks\Components\OnInteractBlockComponent;
use Nexly\Blocks\Components\OnPlayerPlacingBlockComponent;
use Nexly\Blocks\Components\PrecipitationInteractionsBlockComponent;
use Nexly\Blocks\Components\RedstoneConductivityBlockComponent;
use Nexly\Blocks\Components\RedstoneProducerBlockComponent;
use Nexly\Blocks\Components\ReplaceableBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\SupportBlockComponent;
use Nexly\Blocks\Components\TickBlockComponent;
use Nexly\Blocks\Components\Types\BreathabilityType;
use Nexly\Blocks\Components\Types\Material;
use Nexly\Blocks\Components\Types\MaterialType;
use Nexly\Blocks\Components\Types\MovementType;
use Nexly\Blocks\Components\Types\NoteInstrument;
use Nexly\Blocks\Components\Types\PrecipitationType;
use Nexly\Blocks\Components\Types\RenderMethod;
use Nexly\Blocks\Components\Types\StickyType;
use Nexly\Blocks\Components\Types\SupportShape;
use Nexly\Blocks\Components\Types\TintMethod;
use Nexly\Blocks\Components\Types\UseDefaultVisual;
use Nexly\Blocks\Mappers\BlockMapper;
use Nexly\Blocks\Permutations\BlockProperty;
use Nexly\Blocks\Permutations\CartesianProduct;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Blocks\Traits\BlockTrait;
use Nexly\Blocks\Vanilla\HeadBlock;
use Nexly\Events\Impl\BlockLoaderEvent;
use Nexly\Helpers\ComponentHelper;
use Nexly\Items\Components\DataDriven\DataDrivenItemBuilder;
use Nexly\Items\Components\DataDriven\DataDrivenItemComponent;
use Nexly\Items\Components\DataDriven\Property\PropertyItemComponent;
use Nexly\Items\Components\Legacy\LegacyItemBuilder;
use Nexly\Items\Components\Legacy\LegacyItemComponent;
use Nexly\Items\Creative\CreativeInfo;
use Nexly\Items\Creative\NexlyCreative;
use Nexly\Items\ItemBuilder;
use Nexly\Items\ItemVersion;
use Nexly\Mappings\BlockMappings;
use Nexly\Mappings\ItemMappings;
use Nexly\Recipes\NexlyRecipes;
use Nexly\Recipes\Types\Recipe;
use pocketmine\block\block;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\Cactus;
use pocketmine\block\CraftingTable;
use pocketmine\block\Door;
use pocketmine\block\Farmland;
use pocketmine\block\Fence;
use pocketmine\block\FenceGate;
use pocketmine\block\Flowable;
use pocketmine\block\Flower;
use pocketmine\block\Furnace;
use pocketmine\block\Glass;
use pocketmine\block\GlassPane;
use pocketmine\block\Grass;
use pocketmine\block\GrassPath;
use pocketmine\block\Hopper;
use pocketmine\block\Ladder;
use pocketmine\block\Leaves;
use pocketmine\block\Lever;
use pocketmine\block\Opaque;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\block\Sapling;
use pocketmine\block\Slab;
use pocketmine\block\Slime;
use pocketmine\block\Stair;
use pocketmine\block\Transparent;
use pocketmine\block\Trapdoor;
use pocketmine\block\utils\Ageable;
use pocketmine\block\utils\AnalogRedstoneSignalEmitter;
use pocketmine\block\utils\Colored;
use pocketmine\block\utils\PoweredByRedstone;
use pocketmine\block\Wall;
use pocketmine\block\Wood;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\data\bedrock\item\BlockItemIdMap;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\data\bedrock\item\upgrade\LegacyItemIdToStringIdMap;
use pocketmine\item\ItemBlock;
use pocketmine\item\StringToItemParser;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\BlockStateDictionaryEntry;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\Server;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\world\format\io\GlobalBlockStateHandlers as BlockStateHandlers;
use pocketmine\world\format\io\GlobalItemDataHandlers as ItemDataHandlers;
use ReflectionClass;
use function array_map;
use function base64_encode;
use function count;
use function explode;
use function in_array;
use function is_a;
use function is_bool;
use function is_float;
use function is_int;
use function is_null;
use function is_string;
use function json_encode;
use function max;
use function str_contains;
use function strtolower;
use const JSON_THROW_ON_ERROR;
use const PHP_INT_MAX;

class BlockBuilder
{
	protected string $stringId;
	protected ?int $numericId = null;
	protected Closure $block;
	protected ?Closure $serializer = null;
	protected ?Closure $deserializer = null;
	protected ?CreativeInfo $creativeInfo = null;
	protected string $material = MaterialType::Dirt;

	/** @var array<string> */
	protected array $tags = [];

	/** @var array<BlockComponent> */
	protected array $components = [];
	/** @var array<Permutation> */
	protected array $permutations = [];
	protected ?string $mapper = null;
	/** @var array<BlockTrait> */
	protected array $traits = [];
	/** @var array<BlockProperty> */
	protected array $properties = [];

	public static function create() : BlockBuilder
	{
		return new self();
	}

	/**
	 * Get the string identifier for the block.
	 */
	public function getStringId() : string
	{
		return $this->stringId;
	}

	/**
	 * Set the string identifier for the block.
	 *
	 * @return $this
	 */
	public function setStringId(string $stringId) : self
	{
		if (str_contains("minecraft:", $stringId)) {
			throw new \InvalidArgumentException("Custom block string ID cannot contain the 'minecraft:' namespace.");
		}

		$this->stringId = $stringId;
		return $this;
	}

	/**
	 * Get the numeric ID for the block, if set.
	 */
	public function getNumericId() : int
	{
		return $this->numericId ??= BlockTypeIds::newId();
	}

	/**
	 * Set the numeric ID for the block.
	 *
	 * @return $this
	 */
	public function setNumericId(?int $numericId) : self
	{
		$this->numericId = $numericId;
		return $this;
	}

	public function getTags() : array
	{
		return $this->tags;
	}

	public function setTags(array $tags) : self
	{
		$this->tags = $tags;
		return $this;
	}

	public function addTag(string $tag) : void
	{
		if (!in_array($tag, $this->tags, true)) {
			$this->tags[] = $tag;
		}
	}

	public function getBlock() : Closure
	{
		return $this->block;
	}

	/**
	 * Set the item instance or a closure that returns an item instance.
	 *
	 * @return $this
	 */
	public function setBlock(Closure $block) : self
	{
		$this->block = $block;
		return $this;
	}

	/**
	 * Set a custom serializer for the block.
	 *
	 * @return $this
	 */
	public function mapSerializer(?Closure $serializer) : self
	{
		if ($serializer === null) {
			$this->serializer = null;
			return $this;
		}

		$ref = new \ReflectionFunction($serializer);

		if (!$ref->isAnonymous()) {
			throw new \InvalidArgumentException("Serializer must be an anonymous Closure.");
		}

		if (!$ref->isStatic()) {
			throw new \InvalidArgumentException("Serializer must be a static Closure.");
		}

		$returnType = $ref->getReturnType();
		if ($returnType !== null && $returnType->getName() !== BlockStateWriter::class) {
			throw new \InvalidArgumentException("Serializer return type must be a " . BlockStateWriter::class . ".");
		}

		$this->serializer = $serializer;
		return $this;
	}

	/**
	 * Set a custom deserializer for the block.
	 *
	 * @return $this
	 */
	public function mapDeserializer(?Closure $deserializer) : self
	{
		if ($deserializer === null) {
			$this->deserializer = null;
			return $this;
		}

		$ref = new \ReflectionFunction($deserializer);

		if (!$ref->isAnonymous()) {
			throw new \InvalidArgumentException("Deserializer must be an anonymous Closure.");
		}

		if (!$ref->isStatic()) {
			throw new \InvalidArgumentException("Deserializer must be a static Closure.");
		}

		if ($ref->getNumberOfParameters() < 1) {
			throw new \InvalidArgumentException("Deserializer must accept a BlockStateReader as its first parameter.");
		}

		$firstParamType = $ref->getParameters()[0]->getType();
		if ($firstParamType !== null && $firstParamType->getName() !== BlockStateReader::class) {
			throw new \InvalidArgumentException("The first parameter of the deserializer must be typed as " . BlockStateReader::class . ".");
		}

		$returnType = $ref->getReturnType();
		if ($returnType !== null) {
			$returnTypeName = $returnType->getName();
			if (!is_a($returnTypeName, block::class, true)) {
				throw new \InvalidArgumentException("Deserializer return type must be an instance of " . block::class . ", got " . $returnTypeName . ".");
			}
		}

		$this->deserializer = $deserializer;
		return $this;
	}

	/**
	 * Get the CreativeInfo attribute for the block, if set.
	 */
	public function getCreativeInfo() : ?CreativeInfo
	{
		return $this->creativeInfo;
	}

	/**
	 * Set the CreativeInfo attribute for the block.
	 *
	 * @return $this
	 */
	public function setCreativeInfo(?CreativeInfo $creativeInfo) : self
	{
		$this->creativeInfo = $creativeInfo;
		return $this;
	}

	public function getMaterial() : string
	{
		return $this->material;
	}

	public function setMaterial(string $material) : self
	{
		$this->material = $material;
		return $this;
	}

	/**
	 * Add a BlockComponent to the builder.
	 */
	public function getComponents() : array
	{
		return $this->components;
	}

	/**
	 * Get a BlockComponent by its name.
	 */
	public function getComponent(string $component) : ?BlockComponent
	{
		return $this->components[$component] ?? null;
	}

	/**
	 * Add a BlockComponent to the builder.
	 */
	public function hasComponent(string $component) : bool
	{
		return isset($this->components[$component]);
	}

	/**
	 * Add a BlockComponent to the builder.
	 *
	 * @return $this
	 */
	public function addComponent(BlockComponent $component) : self
	{
		$this->components[$component->getName()] = $component;
		return $this;
	}

	/**
	 * Remove a component by its name.
	 */
	public function removeComponent(string $name) : self
	{
		unset($this->components[$name]);
		return $this;
	}

	public function getPermutations() : array
	{
		return $this->permutations;
	}

	/**
	 * Add a permutation to the builder.
	 *
	 * @return $this
	 */
	public function addPermutation(Permutation $permutation) : self
	{
		$this->permutations[] = $permutation;
		return $this;
	}

	public function setMapper(string $class) : BlockBuilder
	{
		$this->mapper = $class;
		return $this;
	}

	public function getTraits() : array
	{
		return $this->traits;
	}

	/**
	 * Add a trait to the builder.
	 *
	 * @return $this
	 */
	public function addTrait(BlockTrait $trait) : self
	{
		$this->traits[] = $trait;
		return $this;
	}

	public function getProperties() : array
	{
		return $this->properties;
	}

	/**
	 * Add a property to the builder.
	 *
	 * @return $this
	 */
	public function addProperty(BlockProperty $property) : self
	{
		$this->properties[] = $property;
		return $this;
	}

	/**
	 * @internal
	 */
	public function getName() : string
	{
		$itemName = $this->getStringId();
		if (str_contains($itemName, ":")) {
			[, $itemName] = explode(":", $itemName, 2);
		}

		return $itemName;
	}

	protected function getItemFrom(block $block) : ItemBuilder
	{
		$item = $block->asItem();
		if ($item->isNull()) {
			throw new \RuntimeException("The block must have a valid item form to be registered.");
		}

		$itemVersion = ItemVersion::fromItem($item);
		$builder = match ($itemVersion) {
			ItemVersion::LEGACY => LegacyItemBuilder::create(),
			ItemVersion::DATA_DRIVEN => DataDrivenItemBuilder::create(),
			default => throw new AssumptionFailedError("Unsupported item itemVersion."),
		};
		$builder->setStringId($this->getStringId());
		$builder->setNumericId($item->getTypeId());
		$builder->setItem($item);

		if ($builder instanceof LegacyItemBuilder) {
			$builder->loadFromItems();
		} elseif ($builder instanceof DataDrivenItemBuilder) {
			$builder->loadProperties();
			$builder->loadComponents();
		}

		$reflection = new ReflectionClass($item);
		$attributes = $reflection->getAttributes();

		foreach ($attributes as $attribute) {
			$instance = $attribute->newInstance();

			if ($builder instanceof DataDrivenItemBuilder) {
				if ($instance instanceof PropertyItemComponent) {
					$builder->addProperty($instance);
				} elseif ($instance instanceof DataDrivenItemComponent) {
					$builder->addComponent($instance);
				}
			}

			if ($builder instanceof LegacyItemBuilder) {
				if ($instance instanceof LegacyItemComponent) {
					$builder->addComponent($instance);
				}
			}
		}

		return $builder;
	}

	/**
	 * Create a LegacyItemBuilder from an Item instance.
	 */
	public function toNBT() : CompoundTag
	{
		$components = CompoundTag::create();
		foreach ($this->components as $k => $component) {
			$components->setTag($component->getName(), $component->toNBT());
		}

		$properties = [];
		foreach ($this->properties as $property) {
			foreach ($this->traits as $trait) {
				if (in_array($property->getName(), $trait->getState()->getNames(), true)) {
					continue 2;
				}
			}

			$properties[] = $property->toNBT();
		}

		$traits = CompoundTag::create();
		foreach ($this->traits as $trait) {
			$traits->setTag($trait->getId(), $trait->toNBT());
		}

		return CompoundTag::create()
			->setTag("components", $components)
			->setTag("traits", $traits)
			->setTag("permutations", new ListTag(array_map(fn (Permutation $permutation) => $permutation->toNBT(), $this->permutations), NBT::TAG_Compound))
			->setTag("properties", new ListTag($properties, NBT::TAG_Compound))
			->setTag(
				"menu_category",
				CompoundTag::create()
				->setTag("category", new StringTag(strtolower($this->getCreativeInfo()?->getCategory()?->name ?? "none")))
				->setTag("group", new StringTag(strtolower($this->getCreativeInfo()?->getGroup()?->value ?? "none")))
				->setTag("is_hidden_in_commands", new ByteTag($this->getCreativeInfo()?->isHidden() ?? false))
			)
			->setTag("blockTags", new ListTag(array_map(fn (string $tag) => new StringTag($tag), $this->tags), NBT::TAG_String))
			->setTag(
				"vanilla_block_data",
				CompoundTag::create()
				->setTag("block_id", new IntTag(BlockMappings::getInstance()->nextRuntimeId()))
				->setTag("material", new StringTag($this->material))
			)
			->setTag("molangVersion", new IntTag(13));
	}

	/**
	 * Register the item with the provided string identifier and callbacks.
	 *
	 * @return $this
	 */
	public function register(bool $creative = true, bool $autoload = true, bool $async = false) : self
	{
		if (!isset($this->block)) {
			throw new \RuntimeException("Block instance is not set. Use setBlock() to set it before registering.");
		}

		$stringId = $this->getStringId();
		$numericId = $this->getNumericId();

		$ref = new \ReflectionFunction($this->getBlock());
		if (!$ref->isStatic()) {
			throw new \RuntimeException("Block method is not static.");
		}

		$parameters = $ref->getParameters();
		if (count($parameters) !== 1) {
			throw new \RuntimeException("Block method has only one parameter.");
		}

		$block = $ref->invoke($numericId);
		if (!$block instanceof block) {
			throw new \RuntimeException("The block closure must return an instance of " . block::class);
		}

		$this->loadComponents($block, $autoload, $async);
		if ($this->mapper !== null) {
			/** @var BlockMapper $mapper */
			$mapper = new ($this->mapper)();
			$mapper->apply($this, $block, $this->properties);
		}

		$entries = [];
		foreach ($this->getBlockStateDictionaryEntry() as $entry) {
			$entries[] = $entry;
			BlockStateHandlers::getUpgrader()->getBlockIdMetaUpgrader()->addIdMetaToStateMapping($entry->getStateName(), $entry->getMeta(), $entry->generateStateData());
		}

		BlockPalette::getInstance()->insertStates($entries);
		BlockMappings::getInstance()->registerMapping(new BlockMapping($this, new BlockPaletteEntry($stringId, new CacheableNbt($this->toNBT()))));
		RuntimeBlockStateRegistry::getInstance()->register($block);

		$serializer = $this->serializer ??= static fn () => new BlockStateWriter($stringId);
		$deserializer = $this->deserializer ??= static fn (BlockStateReader $in) => clone $block;

		if (!($handler = BlockStateHandlers::getSerializer())->isRegistered($block)) {
			$handler->map($block, $serializer);
		}
		BlockStateHandlers::getDeserializer()->map($stringId, $deserializer);

		AsyncInitialization::addAsyncBlock($stringId, [
			$this->numericId,
			$this->block,
			json_encode(array_map(fn (BlockProperty $property) => [
				"name" => $property->getName(),
				"values" => $property->getValues()
			], $this->properties), JSON_THROW_ON_ERROR),
			json_encode(array_map(fn (Permutation $permutation) => [
				"condition" => $permutation->getCondition(),
				"components" => array_map(fn (BlockComponent $component) => [
					"name" => $component->getName(),
					"nbt" => base64_encode((new CacheableNbt($component->toNBT()))->getEncodedNbt())
				], $permutation->getComponents())
			], $this->permutations), JSON_THROW_ON_ERROR),
			json_encode(array_map(fn (BlockComponent $component) => [
				"name" => $component->getName(),
				"nbt" => base64_encode((new CacheableNbt($component->toNBT()))->getEncodedNbt())
			], $this->getComponents()), JSON_THROW_ON_ERROR),
			json_encode(array_map(fn (BlockTrait $trait) => [
				"identifier" => $trait->getId(),
				"nbt" => base64_encode((new CacheableNbt($trait->toNBT()))->getEncodedNbt()),
			], $this->traits), JSON_THROW_ON_ERROR),
			$serializer,
			$deserializer,
		]);

		$item = $block->asItem();
		if ($item instanceof ItemBlock) {
			ItemMappings::registerEntry(new ItemTypeEntry(
				$stringId,
				$block->getTypeId(),
				false,
				ItemVersion::NONE->getValue(),
				new CacheableNbt(CompoundTag::create())
			));
		} else {
			$this->registerItem($block);
		}
		$this->registerBlockItem($block);

		$creativeInfo = $this->getCreativeInfo() ?? NexlyCreative::detectCreativeInfoFrom($block->asItem());
		$reflection = new ReflectionClass($block->asItem());

		$attributes = $reflection->getAttributes();
		if (count($attributes) > 0) {
			foreach ($attributes as $attribute) {
				$instance = $attribute->newInstance();
				if ($instance instanceof CreativeInfo) {
					$creativeInfo = $instance;
				} elseif ($instance instanceof Recipe) {
					NexlyRecipes::getInstance()->addRecipe(fn () => $attribute->newInstance());
				}
			}
		}

		if ($creative && !$creativeInfo->isHidden()) {
			NexlyCreative::add($block->asItem(), $creativeInfo?->getCategory(), $creativeInfo?->getGroup());
		}
		return $this;
	}

	public function loadComponents(block $block, bool $autoload, bool $async = false) : void
	{
		if ($autoload) {
			$this->addComponent(new BreathabilityBlockComponent($block->isTransparent() ? BreathabilityType::AIR : BreathabilityType::SOLID));
			$this->addComponent(new CollisionBoxBlockComponent(!empty($block->getCollisionBoxes())));
			$this->addComponent(new DestructibleByExplosionBlockComponent($block->getBreakInfo()->getBlastResistance()));
			$this->addComponent(new DestructibleByMiningBlockComponent(/*$block->getBreakInfo()->getHardness() * 3.33334*/PHP_INT_MAX));
			$this->addComponent(new DisplayNameBlockComponent("tile." . $this->getStringId() . ".name"));
			$this->addComponent(new FrictionBlockComponent(max(0, 1 - $block->getFrictionFactor())));
			$this->addComponent(new LightEmissionBlockComponent($block->getLightLevel()));
			$this->addComponent(new LightDampeningBlockComponent(max(0, 1 - $block->getLightFilter())));
			$this->addComponent(new EntityFallOnBlockComponent(3));
			$this->addComponent(new InstrumentSoundBlockComponent(NoteInstrument::from($block)));
			$this->addComponent(new MovableBlockComponent(MovementType::from($block), StickyType::from($block)));
			$this->addComponent(new PrecipitationInteractionsBlockComponent(match(true) {
				$block instanceof Flowable => PrecipitationType::OBSTRUCT_RAIN_ACCUMULATE_SNOW,
				$block->isSolid(), $block instanceof Transparent => PrecipitationType::OBRAIN,
				default => PrecipitationType::NONE
			}));
			$this->addComponent(new MaterialInstancesBlockComponent([new Material($this->getName(), renderMethod: match(true) {
				$block->isTransparent() => RenderMethod::ALPHA_TEST_SINGLE_SIDED,
				$block instanceof Opaque => RenderMethod::OPAQUE,
				default => RenderMethod::BLEND,
			})]));

			if ($block->getFlammability() > 0) {
				$this->addComponent(new FlammableBlockComponent($block->getFlameEncouragement()));
			}
			if ($block->canBeReplaced()) {
				$this->addComponent(new ReplaceableBlockComponent());
			}
			if ($block->ticksRandomly()) {
				$this->addComponent(new TickBlockComponent());
			}

			if ($block instanceof PoweredByRedstone) {
				$this->addComponent(new RedstoneConductivityBlockComponent(redstoneConductor: true));
			}
			if ($block instanceof AnalogRedstoneSignalEmitter) {
				$this->addComponent(new RedstoneProducerBlockComponent($block->getOutputSignalStrength()));
			}
			if ($block instanceof Flowable) {
				$this->addComponent(new ConnectionRuleComponent());
			}
			if ($block instanceof CraftingTable) {
				$this->addComponent(new CraftingTableBlockComponent(["crafting_table"], 3));
			}
			if ($block instanceof Grass) {
				$this->addComponent(new MapColorBlockComponent("#7CBA46", TintMethod::from($this)));
			} elseif ($block instanceof Colored) {
				$color = $block->getColor()->getRgbValue();
				$this->addComponent(new MapColorBlockComponent([$color->getR(), $color->getG(), $color->getB()], TintMethod::from($block)));
			}

			$containerSlotCount = null;
			if (ComponentHelper::hasTile($block, $containerSlotCount)) {
				$this->addComponent(new BlockEntityBlockComponent(true, $containerSlotCount));
			}
			if (ComponentHelper::hasInteract($block)) {
				$this->addComponent(new OnInteractBlockComponent());
			}

			$shape = SupportShape::from($block);
			if ($shape !== null) {
				$this->addComponent(new SupportBlockComponent($shape));
			}

			$this->addComponent(new SelectionBoxBlockComponent(true));
			$this->addComponent(new OnPlayerPlacingBlockComponent());
		}

		$ev = new BlockLoaderEvent($this, $block);
		$ev->trigger();
		if (!$async) {
			if (!$ev->isAffected() && $autoload) {
				$this->detectDefaultComponent($block); // Detect known block types and apply default components
			}
		}

		$reflection = new ReflectionClass($block);
		$attributes = $reflection->getAttributes();
		if (count($attributes) > 0) {
			foreach ($attributes as $attribute) {
				$instance = $attribute->newInstance();
				if ($instance instanceof BlockComponent) {
					$this->addComponent($instance);
				}
				if ($instance instanceof UseDefaultVisual) {
					$geometry = $this->getComponent(BlockComponentIds::GEOMETRY);
					$material = $this->getComponent(BlockComponentIds::MATERIAL_INSTANCES);
					$this->addComponent(new ItemVisualBlockComponent(is_null($instance->getGeometry()) ? $geometry : new GeometryBlockComponent($instance->getGeometry()), $material));
				}
			}
		}
	}

	/**
	 * Register the block item for the provided block.
	 */
	protected function registerItem(block $block) : void
	{
		$builder = $this->getItemFrom($block);
		$stringId = $this->getStringId();

		try {
			ItemDataHandlers::getDeserializer()->map($stringId, fn (SavedItemData $data) => clone $builder->getItem());
			ItemDataHandlers::getSerializer()->map($builder->getItem(), fn () => new SavedItemData($stringId));
		} catch (\Throwable) {
			Server::getInstance()->getLogger()->warning("Nexly BlockBuilder: Failed to register item serializer/deserializer for block item '{$stringId}'");
		}

		ItemMappings::getInstance()->registerMapping($builder, new ItemTypeEntry(
			$stringId,
			255 - $builder->getNumericId(),
			$builder->getVersion()->equals(ItemVersion::DATA_DRIVEN),
			$builder->getVersion()->getValue(),
			new CacheableNbt($builder->toNBT())
		));
	}

	/**
	 * Register the block item for the provided block.
	 */
	protected function registerBlockItem(block $block) : void
	{
		$name = $this->getName();
		$stringId = $this->getStringId();

		StringToItemParser::getInstance()->registerBlock($name, fn () => clone $block);
		LegacyItemIdToStringIdMap::getInstance()->add($name, 255 - $this->getNumericId());

		$blockItemIdMap = BlockItemIdMap::getInstance();
		$reflection = new ReflectionClass($blockItemIdMap);

		$itemToBlockId = $reflection->getProperty("itemToBlockId");
		/** @var string[] $value */
		$value = $itemToBlockId->getValue($blockItemIdMap);
		$itemToBlockId->setValue($blockItemIdMap, $value + [$stringId => $stringId]);
	}

	protected function getBlockStateDictionaryEntry() : Generator
	{
		if (empty($this->properties)) {
			return yield new BlockStateDictionaryEntry($this->getStringId(), [], 0);
		}

		$listBlockPropertyName = array_map(fn (BlockProperty $property) => $property->getName(), $this->properties);
		$data_ = array_map(fn (BlockProperty $property) => $property->getValues(), $this->properties);
		foreach (CartesianProduct::get($data_) as $meta => $property) {
			$states = [];
			foreach ($property as $i => $data) {
				$states[$listBlockPropertyName[$i]] = match (true) {
					is_bool($data) => new ByteTag($data),
					is_string($data) => new StringTag($data),
					is_int($data) => new IntTag($data),
					is_float($data) => new FloatTag($data),
					default => throw new \RuntimeException("Invalid block property data type"),
				};
			}

			yield new BlockStateDictionaryEntry($this->getStringId(), $states, $meta);
		}
	}

	protected function detectDefaultComponent(block $block) : void
	{
		match (true) {
			$block instanceof Ageable => BlockCreator::makeAgeable($this, $block),

			$block instanceof Wood => BlockCreator::makeWood($this, $block),
			$block instanceof Leaves => BlockCreator::makeLeaves($this, $block),
			$block instanceof GrassPath => BlockCreator::makeGrassPath($this, $block),
			$block instanceof Sapling => BlockCreator::makeSapling($this, $block),
			$block instanceof Stair => BlockCreator::makeStair($this, $block),
			$block instanceof Slab => BlockCreator::makeSlab($this, $block),
			$block instanceof Door => BlockCreator::makeDoor($this, $block),
			$block instanceof Fence => BlockCreator::makeFence($this, $block),
			$block instanceof FenceGate => BlockCreator::makeFenceGate($this, $block),
			$block instanceof Wall => BlockCreator::makeWall($this, $block),
			$block instanceof Trapdoor => BlockCreator::makeTrapdoor($this, $block),
			$block instanceof Hopper => BlockCreator::makeHopper($this, $block),
			$block instanceof Furnace => BlockCreator::makeFurnace($this, $block),
			$block instanceof HeadBlock => BlockCreator::makeHead($this, $block),
			$block instanceof Ladder => BlockCreator::makeLadder($this, $block),
			$block instanceof Farmland => BlockCreator::makeFarmland($this, $block),
			$block instanceof Flower => BlockCreator::makeFlower($this, $block),
			$block instanceof Glass => BlockCreator::makeGlass($this, $block),
			$block instanceof GlassPane => BlockCreator::makeGlassPane($this, $block),
			$block instanceof Lever => BlockCreator::makeLever($this, $block),
			$block instanceof Slime => BlockCreator::makeSlime($this, $block),
			$block instanceof Cactus => BlockCreator::makeCactus($this, $block),
			$block instanceof Colored => BlockCreator::makeSimpleColored($this, $block),
			default => null,
		};
	}
}
