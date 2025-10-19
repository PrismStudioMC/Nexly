<?php

namespace Nexly\Blocks;

use Closure;
use Generator;
use Nexly\Blocks\Components\BlockComponent;
use Nexly\Blocks\Components\BlockComponentIds;
use Nexly\Blocks\Components\BreathabilityBlockComponent;
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\CustomComponentsBlockComponent;
use Nexly\Blocks\Components\DestructibleByExplosionBlockComponent;
use Nexly\Blocks\Components\DestructibleByMiningBlockComponent;
use Nexly\Blocks\Components\DisplayNameBlockComponent;
use Nexly\Blocks\Components\FrictionBlockComponent;
use Nexly\Blocks\Components\LightEmissionBlockComponent;
use Nexly\Blocks\Components\MaterialInstancesBlockComponent;
use Nexly\Blocks\Components\OnPlayerPlacingBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\Types\BreathabilityType;
use Nexly\Blocks\Components\Types\Material;
use Nexly\Blocks\Components\Types\MaterialRenderMethod;
use Nexly\Blocks\Components\Types\MaterialType;
use Nexly\Blocks\Permutations\BlockProperty;
use Nexly\Blocks\Permutations\CartesianProduct;
use Nexly\Blocks\Permutations\NexlyPermutations;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Blocks\Traits\MinecraftTrait;
use Nexly\Blocks\Vanilla\HeadBlock;
use Nexly\Events\Impl\BlockLoaderEvent;
use Nexly\Items\Components\DataDriven\DataDrivenItemBuilder;
use Nexly\Items\Components\Legacy\LegacyItemBuilder;
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
use pocketmine\block\Crops;
use pocketmine\block\Door;
use pocketmine\block\Farmland;
use pocketmine\block\Fence;
use pocketmine\block\FenceGate;
use pocketmine\block\Flower;
use pocketmine\block\GlassPane;
use pocketmine\block\Hopper;
use pocketmine\block\Ladder;
use pocketmine\block\Liquid;
use pocketmine\block\NetherWartPlant;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\block\Slab;
use pocketmine\block\tile\Container;
use pocketmine\block\Trapdoor;
use pocketmine\block\Wall;
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

use function Opis\Closure\init;

class BlockBuilder
{
    private string $stringId;
    private ?int $numericId = null;
    private Closure $block;
    private ?Closure $serializer = null;
    private ?Closure $deserializer = null;
    private ?CreativeInfo $creativeInfo = null;
    private MaterialType $material = MaterialType::Dirt;

    /** @var array<BlockComponent> */
    private array $components = [];
    /** @var array<Permutation> */
    private array $permutations = [];
    /** @var array<MinecraftTrait> */
    private array $traits = [];
    /** @var array<BlockProperty> */
    private array $properties = [];

    /**
     * @return BlockBuilder
     */
    public static function create(): BlockBuilder
    {
        return new self();
    }

    /**
     * Get the string identifier for the block.
     *
     * @return string
     */
    public function getStringId(): string
    {
        return $this->stringId;
    }

    /**
     * Set the string identifier for the block.
     *
     * @param string $stringId
     * @return $this
     */
    public function setStringId(string $stringId): self
    {
        if (str_contains("minecraft:", $stringId)) {
            throw new \InvalidArgumentException("Custom block string ID cannot contain the 'minecraft:' namespace.");
        }

        $this->stringId = $stringId;
        return $this;
    }

    /**
     * Get the numeric ID for the block, if set.
     *
     * @return int
     */
    public function getNumericId(): int
    {
        return $this->numericId ??= BlockTypeIds::newId();
    }

    /**
     * Set the numeric ID for the block.
     *
     * @param int|null $numericId
     * @return $this
     */
    public function setNumericId(?int $numericId): self
    {
        $this->numericId = $numericId;
        return $this;
    }

    /**
     * @return Closure
     */
    public function getBlock(): Closure
    {
        return $this->block;
    }

    /**
     * Set the item instance or a closure that returns an item instance.
     *
     * @param Closure $block
     * @return $this
     */
    public function setBlock(Closure $block): self
    {
        $this->block = $block;
        return $this;
    }

    /**
     * Get the custom serializer for the block.
     *
     * @return Closure|null
     */
    public function getSerializer(): ?Closure
    {
        return $this->serializer;
    }

    /**
     * Set a custom serializer for the block.
     *
     * @param Closure|null $serializer
     * @return $this
     */
    public function setSerializer(?Closure $serializer): self
    {
        $this->serializer = $serializer;
        return $this;
    }

    /**
     * Get the custom deserializer for the block.
     *
     * @return Closure|null
     */
    public function getDeserializer(): ?Closure
    {
        return $this->deserializer;
    }

    /**
     * Set a custom deserializer for the block.
     *
     * @param Closure|null $deserializer
     * @return $this
     */
    public function setDeserializer(?Closure $deserializer): self
    {
        $this->deserializer = $deserializer;
        return $this;
    }

    /**
     * Get the CreativeInfo attribute for the block, if set.
     *
     * @return CreativeInfo|null
     */
    public function getCreativeInfo(): ?CreativeInfo
    {
        return $this->creativeInfo;
    }

    /**
     * Set the CreativeInfo attribute for the block.
     *
     * @param CreativeInfo|null $creativeInfo
     * @return $this
     */
    public function setCreativeInfo(?CreativeInfo $creativeInfo): self
    {
        $this->creativeInfo = $creativeInfo;
        return $this;
    }

    /**
     * @return MaterialType
     */
    public function getMaterial(): MaterialType
    {
        return $this->material;
    }

    /**
     * @param MaterialType $material
     */
    public function setMaterial(MaterialType $material): void
    {
        $this->material = $material;
    }

    /**
     * Add a BlockComponent to the builder.
     *
     * @return array
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * Get a BlockComponent by its name.
     *
     * @param BlockComponentIds $name
     * @return BlockComponent|null
     */
    public function getComponent(BlockComponentIds|string $name): ?BlockComponent
    {
        return $this->components[$name?->getValue() ?? $name] ?? null;
    }

    /**
     * Add a BlockComponent to the builder.
     *
     * @param string $name
     * @return bool
     */
    public function hasComponent(string $name): bool
    {
        return isset($this->components[$name]);
    }

    /**
     * Add a BlockComponent to the builder.
     *
     * @param BlockComponent $component
     * @return $this
     */
    public function addComponent(BlockComponent $component): self
    {
        $this->components[$component->getName()] = $component;
        return $this;
    }

    /**
     * Remove a component by its name.
     *
     * @param string $name
     * @return void
     */
    public function removeComponent(string $name): void
    {
        unset($this->components[$name]);
    }

    /**
     * @return array
     */
    public function getPermutations(): array
    {
        return $this->permutations;
    }

    /**
     * Add a permutation to the builder.
     *
     * @param Permutation $permutation
     * @return $this
     */
    public function addPermutation(Permutation $permutation): self
    {
        $this->permutations[] = $permutation;
        return $this;
    }

    /**
     * @return array
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * Add a trait to the builder.
     *
     * @param MinecraftTrait $trait
     * @return $this
     */
    public function addTrait(MinecraftTrait $trait): self
    {
        $this->traits[] = $trait;
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Add a property to the builder.
     *
     * @param BlockProperty $property
     * @return $this
     */
    public function addProperty(BlockProperty $property): self
    {
        $this->properties[] = $property;
        return $this;
    }

    /**
     * @return string
     * @internal
     */
    public function getName(): string
    {
        $itemName = $this->getStringId();
        if (str_contains($itemName, ":")) {
            [, $itemName] = explode(":", $itemName, 2);
        }

        return $itemName;
    }

    /**
     * @param block $block
     * @return ItemBuilder
     */
    private function getItemFrom(Block $block): ItemBuilder
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

        return $builder;
    }

    /**
     * Create a LegacyItemBuilder from an Item instance.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $components = CompoundTag::create();
        foreach ($this->components as $k => $component) {
            $components->setTag($component->getName(), $component->toNBT());
        }
        return CompoundTag::create()
            ->setTag("components", $components)
            ->setTag("permutations", new ListTag(array_map(fn (Permutation $permutation) => $permutation->toNBT(), $this->permutations), NBT::TAG_Compound))
            ->setTag("properties", new ListTag(array_reverse(array_map(fn (BlockProperty $property) => $property->toNBT(), $this->properties)), NBT::TAG_Compound))
            ->setTag(
                "menu_category",
                CompoundTag::create()
                ->setTag("category", new StringTag(strtolower($this->getCreativeInfo()?->getCategory()?->name ?? "none")))
                ->setTag("group", new StringTag(strtolower($this->getCreativeInfo()?->getGroup()?->value ?? "none")))
                ->setTag("is_hidden_in_commands", new ByteTag($this->getCreativeInfo()?->isHidden() ?? false))
            )
            ->setTag("traits", new ListTag(array_map(fn (MinecraftTrait $trait) => $trait->toNBT(), $this->traits), NBT::TAG_Compound))
            ->setTag(
                "vanilla_block_data",
                CompoundTag::create()
                ->setTag("block_id", new IntTag(BlockMappings::getInstance()->nextRuntimeId()))
                ->setTag("material", new StringTag($this->material->value))
            )
            ->setTag("molangVersion", new IntTag(12));
    }

    /**
     * Register the item with the provided string identifier and callbacks.
     *
     * @param bool $creative
     * @param bool $autoload
     * @return $this
     */
    public function register(bool $creative = true, bool $autoload = true, Closure $init = null): self
    {
        if (!isset($this->block)) {
            throw new \RuntimeException("Block instance is not set. Use setBlock() to set it before registering.");
        }

        $stringId = $this->getStringId();
        $numericId = $this->getNumericId();

        $block = ($this->getBlock())($numericId);
        if (!$block instanceof Block) {
            throw new \RuntimeException("The block closure must return an instance of " . Block::class);
        }

        $this->loadComponents($block, $autoload);

        $serializer = $this->serializer ??= static fn () => new BlockStateWriter($stringId);
        $deserializer = $this->deserializer ??= static fn (BlockStateReader $in) => clone $block;

        $entries = [];
        foreach ($this->getBlockStateDictionaryEntry() as $entry) {
            $entries[] = $entry;
            BlockStateHandlers::getUpgrader()->getBlockIdMetaUpgrader()->addIdMetaToStateMapping($entry->getStateName(), $entry->getMeta(), $entry->generateStateData());
        }

        BlockPalette::getInstance()->insertStates($entries);

        RuntimeBlockStateRegistry::getInstance()->register($block);
        BlockStateHandlers::getSerializer()->map($block, $serializer);
        BlockStateHandlers::getDeserializer()->map($stringId, $deserializer);
        BlockMappings::getInstance()->registerMapping(new BlockMapping($this, new BlockPaletteEntry($stringId, new CacheableNbt($this->toNBT()))));
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
        $reflection = new \ReflectionClass($block->asItem());

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

        if ($creative) {
            NexlyCreative::add($block->asItem(), $creativeInfo?->getCategory(), $creativeInfo?->getGroup());
        }
        return $this;
    }

    /**
     * @param block $block
     * @param bool $autoload
     * @return void
     */
    public function loadComponents(Block $block, bool $autoload): void
    {
        if ($autoload) {
            $this->addComponent(new BreathabilityBlockComponent($block->isTransparent() ? BreathabilityType::AIR : BreathabilityType::SOLID));
            $this->addComponent(new CollisionBoxBlockComponent(!empty($block->getCollisionBoxes())));
            $this->addComponent(new DestructibleByExplosionBlockComponent($block->getBreakInfo()->getBlastResistance()));
            $this->addComponent(new DestructibleByMiningBlockComponent($block->getBreakInfo()->getHardness() * 3.33334));
            $this->addComponent(new DisplayNameBlockComponent("tile." . $this->getStringId() . ".name"));
            $this->addComponent(new FrictionBlockComponent(max(0, 1 - $block->getFrictionFactor())));
            $this->addComponent(new LightEmissionBlockComponent($block->getLightLevel()));
            //$this->addComponent(new LiquidDetectionComponent(false)); // TODO: PMMP Implement Liquid Layer
            $this->addComponent(new MaterialInstancesBlockComponent([new Material($this->getName(), renderMethod: $block->isTransparent() ? MaterialRenderMethod::ALPHA_TEST : MaterialRenderMethod::OPAQUE)]));
            $this->addComponent(new OnPlayerPlacingBlockComponent());

            $tile = $block->getIdInfo()->getTileClass();
            if ($tile !== null && is_a($tile, Container::class, true)) {
                $this->addComponent(new CustomComponentsBlockComponent());
            }

            $this->addComponent(new SelectionBoxBlockComponent(true));
            $this->detectDefaultComponent($block); // Detect known block types and apply default components
        }

        $ev = new BlockLoaderEvent($this, $block);
        $ev->trigger();

        $reflection = new ReflectionClass($block);
        $attributes = $reflection->getAttributes();
        if (count($attributes) > 0) {
            foreach ($attributes as $attribute) {
                $instance = $attribute->newInstance();
                if ($instance instanceof BlockComponent) {
                    $this->addComponent($instance);
                }
            }
        }
    }

    /**
     * Register the block item for the provided block.
     *
     * @param block $block
     * @return void
     * @throws \ReflectionException
     */
    private function registerItem(Block $block): void
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
            $builder->getNumericId(),
            $builder->getVersion()->equals(ItemVersion::DATA_DRIVEN),
            $builder->getVersion()->getValue(),
            new CacheableNbt($builder->toNBT())
        ));
    }

    /**
     * Register the block item for the provided block.
     *
     * @param Block $block
     * @return void
     */
    private function registerBlockItem(Block $block): void
    {
        $name = $this->getName();
        $stringId = $this->getStringId();

        StringToItemParser::getInstance()->registerBlock($name, fn () => clone $block);
        LegacyItemIdToStringIdMap::getInstance()->add($name, $this->getNumericId());

        $blockItemIdMap = BlockItemIdMap::getInstance();
        $reflection = new ReflectionClass($blockItemIdMap);

        $itemToBlockId = $reflection->getProperty("itemToBlockId");
        /** @var string[] $value */
        $value = $itemToBlockId->getValue($blockItemIdMap);
        $itemToBlockId->setValue($blockItemIdMap, $value + [$stringId => $stringId]);
    }

    /**
     * @return Generator
     */
    private function getBlockStateDictionaryEntry(): Generator
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

    /**
     * @param block $block
     * @return void
     */
    private function detectDefaultComponent(Block $block): void
    {
        match (true) {
            $block instanceof Crops => NexlyPermutations::makeCrop($this, $block),
            $block instanceof NetherWartPlant => NexlyPermutations::makeNetherPlant($this, $block),
            $block instanceof Slab => NexlyPermutations::makeSlab($this, $block),
            $block instanceof Door => NexlyPermutations::makeDoor($this, $block),
            $block instanceof Fence => NexlyPermutations::makeFence($this, $block),
            $block instanceof FenceGate => NexlyPermutations::makeFenceGate($this, $block),
            $block instanceof Wall => NexlyPermutations::makeWall($this, $block),
            $block instanceof Trapdoor => NexlyPermutations::makeTrapdoor($this, $block),
            $block instanceof Hopper => NexlyPermutations::makeHopper($this, $block),
            $block instanceof HeadBlock => NexlyPermutations::makeHead($this, $block),
            $block instanceof Ladder => NexlyPermutations::makeLadder($this, $block),
            $block instanceof Farmland => NexlyPermutations::makeFarmland($this, $block),
            $block instanceof Flower => NexlyPermutations::makeFlower($this, $block),
            $block instanceof GlassPane => NexlyPermutations::makeGlassPane($this, $block),
            default => null,
        };
    }
}
