<?php

namespace Nexly\Blocks;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\BlockComponentIds;
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\EmbeddedVisualBlockComponent;
use Nexly\Blocks\Components\FlowerPottableBlockComponent;
use Nexly\Blocks\Components\GeometryBlockComponent;
use Nexly\Blocks\Components\ItemVisualBlockComponent;
use Nexly\Blocks\Components\LeashableBlockComponent;
use Nexly\Blocks\Components\MaterialInstancesBlockComponent as MaterialComponent;
use Nexly\Blocks\Components\OnInteractBlockComponent;
use Nexly\Blocks\Components\RandomOffsetBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\Types\BoxCollision;
use Nexly\Blocks\Components\Types\CullingType;
use Nexly\Blocks\Components\Types\Geometry;
use Nexly\Blocks\Components\Types\Material;
use Nexly\Blocks\Components\Types\NexlyGeometry;
use Nexly\Blocks\Components\Types\RangeOffset;
use Nexly\Blocks\Components\Types\RenderMethod;
use Nexly\Blocks\Components\Types\Target;
use Nexly\Blocks\Mappers\AgeableBlockMapper;
use Nexly\Blocks\Mappers\DoorBlockMapper;
use Nexly\Blocks\Mappers\FarmlandBlockMapper;
use Nexly\Blocks\Mappers\FenceBlockMapper;
use Nexly\Blocks\Mappers\FenceGateBlockMapper;
use Nexly\Blocks\Mappers\GlassPaneBlockMapper;
use Nexly\Blocks\Mappers\HeadBlockMapper;
use Nexly\Blocks\Mappers\LadderBlockMapper;
use Nexly\Blocks\Mappers\LeverBlockMapper;
use Nexly\Blocks\Mappers\SlabBlockMapper;
use Nexly\Blocks\Mappers\StairBlockMapper;
use Nexly\Blocks\Mappers\TrapdoorBlockMapper;
use Nexly\Blocks\Mappers\WallBlockMapper;
use Nexly\Blocks\Mappers\WoodBlockMapper;
use Nexly\Blocks\Permutations\BlockProperty as Property;
use Nexly\Blocks\Vanilla\HeadBlock;
use Nexly\Blocks\Vanilla\NexlyFence;
use Nexly\Blocks\Vanilla\NexlyGlassPane;
use Nexly\Helpers\DeserializerHelper;
use Nexly\Helpers\MolangHelper;
use Nexly\Helpers\PropertyHelper;
use Nexly\Helpers\SerializerHelper;
use Nexly\Helpers\StringsHelper;
use pocketmine\block\Block;
use pocketmine\block\Cactus;
use pocketmine\block\Crops;
use pocketmine\block\Door;
use pocketmine\block\Farmland;
use pocketmine\block\Fence;
use pocketmine\block\FenceGate;
use pocketmine\block\Flower;
use pocketmine\block\Furnace;
use pocketmine\block\Glass;
use pocketmine\block\GlassPane;
use pocketmine\block\GrassPath;
use pocketmine\block\Hopper;
use pocketmine\block\Ladder;
use pocketmine\block\Leaves;
use pocketmine\block\Lever;
use pocketmine\block\NetherWartPlant;
use pocketmine\block\Sapling;
use pocketmine\block\Slab;
use pocketmine\block\Slime;
use pocketmine\block\Stair;
use pocketmine\block\Trapdoor;
use pocketmine\block\utils\Ageable;
use pocketmine\block\utils\Colored;
use pocketmine\block\Wall;
use pocketmine\block\Wood;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\data\bedrock\block\convert\BlockStateDeserializerHelper as PMDeserializerHelper;
use pocketmine\data\bedrock\block\convert\BlockStateReader as Reader;
use pocketmine\data\bedrock\block\convert\BlockStateSerializerHelper as PMSerializerHelper;
use pocketmine\data\bedrock\block\convert\BlockStateWriter as Writer;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use RuntimeException;

class BlockCreator
{
    /**
     * Create permutations for ageable blocks (e.g., wheat, carrots, potatoes, nether_wart_plant).
     *
     * @param Builder $builder
     * @param Crops $block
     * @return void
     */
    public static function makeAgeable(Builder $builder, Ageable $block): void
    {
        $stringId = $builder->getStringId();
        $geometry = (match(true) {
            $block instanceof NetherWartPlant => NexlyGeometry::NETHER_WART,
            default => NexlyGeometry::CROP
        });

        $builder->mapSerializer(static fn (Ageable $block): Writer => SerializerHelper::encodeAgeable($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Block => DeserializerHelper::decodeAgeable(clone $block, $in));

        $builder->addProperty(new Property(StateNames::AGE, range(0, $block::MAX_AGE)));
        $builder->addComponent(new GeometryBlockComponent($geometry));
        $builder->setMapper(AgeableBlockMapper::class);
    }

    /**
     * Create permutations for Wood blocks.
     *
     * @param Builder $builder
     * @param Wood $block
     * @return void
     */
    public static function makeWood(Builder $builder, Wood $block): void
    {
        [$unstrippedId, $strippedId] = StringsHelper::wood($builder->getStringId());

        $builder->mapSerializer(static fn (Wood $block): Writer => PMSerializerHelper::encodeLog($block, $unstrippedId, $strippedId));
        $builder->mapDeserializer(static fn (Reader $in): Wood => PMDeserializerHelper::decodeLog(clone $block, $block->isStripped(), $in));

        $builder->addProperty(new Property(StateNames::PILLAR_AXIS, [StateValues::PILLAR_AXIS_X, StateValues::PILLAR_AXIS_Y, StateValues::PILLAR_AXIS_Z]));
        $builder->addComponent(new GeometryBlockComponent());
        $builder->setMapper(WoodBlockMapper::class);
    }

    /**
     * Create permutations for Leaves blocks.
     *
     * @param Builder $builder
     * @param Leaves $block
     * @return void
     */
    public static function makeLeaves(Builder $builder, Leaves $block): void
    {
        $stringId = $builder->getStringId();

        $builder->mapSerializer(static fn (Leaves $block): Writer => PMSerializerHelper::encodeLeaves($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Leaves => PMDeserializerHelper::decodeLeaves(clone $block, $in));

        $builder->addProperty(new Property(StateNames::PERSISTENT_BIT, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::UPDATE_BIT, PropertyHelper::BOOL));

        $builder->addComponent(new MaterialComponent([new Material(
            texture: $builder->getName(),
            renderMethod: RenderMethod::ALPHA_TEST,
            ambientOcclusion: 0.80,
            isotropic: true,
        )]));
    }

    /**
     * Create permutations for Grass Path blocks.
     *
     * @param Builder $builder
     * @param GrassPath $block
     * @return void
     */
    public static function makeGrassPath(Builder $builder, GrassPath $block): void
    {
        $materials = [
            new Material(
                texture: $builder->getName() . "_top",
                target: Target::UP,
                renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED
            ),
            new Material(
                texture: $builder->getName() . "_bottom",
                target: Target::DOWN,
                renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED
            ),
            new Material(
                texture: $builder->getName() . "_side",
                target: Target::ALL,
                renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED
            )
        ];

        $builder->addComponent(new GeometryBlockComponent(NexlyGeometry::FARMLAND));
        $builder->addComponent(new MaterialComponent($materials));
        $builder->addComponent(new SelectionBoxBlockComponent(true, [BoxCollision::FARMLAND()]));
        $builder->addComponent(new CollisionBoxBlockComponent(true, [BoxCollision::FARMLAND()]));
    }


    /**
     * Create permutations for Sapling blocks.
     *
     * @param Builder $builder
     * @param Sapling $block
     * @return void
     */
    public static function makeSapling(Builder $builder, Sapling $block): void
    {
        $stringId = $builder->getStringId();
        $materials = [new Material($builder->getName(), renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED, ambientOcclusion: 0.0, faceDimming: false)];

        $builder->mapSerializer(static fn (Sapling $block): Writer => PMSerializerHelper::encodeSapling($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Sapling => PMDeserializerHelper::decodeSapling(clone $block, $in));

        $builder->addProperty(new Property(StateNames::AGE_BIT, PropertyHelper::BOOL));
        $builder->addComponent(new SelectionBoxBlockComponent(true, [BoxCollision::FLOWER()]));
        $builder->addComponent(new GeometryBlockComponent(Geometry::CROSS));
        $builder->addComponent(new MaterialComponent($materials));
        $builder->addComponent(new FlowerPottableBlockComponent());
        $builder->addComponent(new EmbeddedVisualBlockComponent(new GeometryBlockComponent(Geometry::CROSS), new MaterialComponent($materials)));
    }

    /**
     * Create permutations for stair blocks (e.g., stone stairs, wooden stairs).
     *
     * @param Builder $builder
     * @param Stair $block
     * @return void
     */
    public static function makeStair(Builder $builder, Stair $block): void
    {
        $stringId = $builder->getStringId();
        /** @var MaterialComponent $material */
        $material = $builder->getComponent(BlockComponentIds::MATERIAL_INSTANCES);

        $builder->mapSerializer(static fn (Stair $block): Writer => SerializerHelper::encodeStair($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Stair => DeserializerHelper::decodeStair(clone $block, $in));

        $builder->addProperty(new Property(StateNames::DIRECTION, PropertyHelper::get("horizontalFacing5Minus")));
        $builder->addProperty(new Property(StateNames::MC_CORNER, PropertyHelper::MC_CORNER));
        $builder->addProperty(new Property(StateNames::UPSIDE_DOWN_BIT, PropertyHelper::BOOL));

        $ud = MolangHelper::state(StateNames::UPSIDE_DOWN_BIT);

        $notOuterRight = MolangHelper::stateNotEquals(StateNames::MC_CORNER, StateValues::MC_CORNER_OUTER_RIGHT);
        $notOuterLeft  = MolangHelper::stateNotEquals(StateNames::MC_CORNER, StateValues::MC_CORNER_OUTER_LEFT);
        $isInnerLeft   = MolangHelper::stateEquals(StateNames::MC_CORNER, StateValues::MC_CORNER_INNER_LEFT);
        $isInnerRight  = MolangHelper::stateEquals(StateNames::MC_CORNER, StateValues::MC_CORNER_INNER_RIGHT);

        $geometryComponent = (new GeometryBlockComponent(NexlyGeometry::STAIR))
            ->add("base", true)
            ->add("top_nw", MolangHelper::ternary($ud, $notOuterRight, $notOuterLeft))
            ->add("top_ne", MolangHelper::ternary($ud, $notOuterLeft, $notOuterRight))
            ->add("top_sw", MolangHelper::ternary($ud, $isInnerLeft, $isInnerRight))
            ->add("top_se", MolangHelper::ternary($ud, $isInnerRight, $isInnerLeft));

        $builder->addComponent($geometryComponent);
        $builder->addComponent(new ItemVisualBlockComponent($geometryComponent, $material));

        $builder->setMapper(StairBlockMapper::class);
    }

    /**
     * Create permutations for slab blocks (e.g., stone slab, wooden slab).
     *
     * @param Builder $builder
     * @param Slab $block
     * @return void
     */
    public static function makeSlab(Builder $builder, Slab $block): void
    {
        $stringId = $builder->getStringId();
        /** @var MaterialComponent $material */
        $material = $builder->getComponent(BlockComponentIds::MATERIAL_INSTANCES);

        $builder->mapSerializer(static fn (Slab $block): Writer => SerializerHelper::encodeSlab($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Slab => DeserializerHelper::decodeSlab(clone $block, $in));

        $builder->addProperty(new Property(StateNames::MC_VERTICAL_HALF, PropertyHelper::MC_VERTICAL_HALF));

        $builder->addComponent(new GeometryBlockComponent(NexlyGeometry::SLAB));
        $builder->addComponent(new ItemVisualBlockComponent(new GeometryBlockComponent(NexlyGeometry::SLAB . "_carried"), $material));

        $builder->setMapper(SlabBlockMapper::class);
    }

    /**
     * Create permutations for door blocks.
     *
     * @param Builder $builder
     * @param Door $block
     * @return void
     */
    public static function makeDoor(Builder $builder, Door $block): void
    {
        $stringId = $builder->getStringId();

        $builder->mapSerializer(static fn (Door $block) => PMSerializerHelper::encodeDoor($block, Writer::create($stringId)));
        $builder->mapDeserializer(static fn (Reader $in) => PMDeserializerHelper::decodeDoor(clone $block, $in));

        $builder->addProperty(new Property(StateNames::MC_CARDINAL_DIRECTION, PropertyHelper::MC_CARDINAL_DIRECTION));
        $builder->addProperty(new Property(StateNames::UPPER_BLOCK_BIT, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::DOOR_HINGE_BIT, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::OPEN_BIT, PropertyHelper::BOOL));

        $builder->addComponent(new OnInteractBlockComponent());
        $builder->addComponent(
            (new GeometryBlockComponent(NexlyGeometry::DOOR))
            ->add("open", MolangHelper::stateEquals(StateNames::OPEN_BIT, true))
            ->add("close", MolangHelper::stateEquals(StateNames::OPEN_BIT, false))
        );

        $builder->setMapper(DoorBlockMapper::class);
    }

    /**
     * Create permutations for fence blocks.
     *
     * @param Builder $builder
     * @param Fence $block
     * @return void
     */
    public static function makeFence(Builder $builder, Fence $block): void
    {
        if (!$block instanceof NexlyFence) {
            throw new RuntimeException("Fence blocks must extend " . NexlyFence::class . " to be registered.");
        }

        $stringId = $builder->getStringId();

        /** @var MaterialComponent $material */
        $material = $builder->getComponent(BlockComponentIds::MATERIAL_INSTANCES);

        $builder->mapSerializer(static fn (Fence $block): Writer => SerializerHelper::encodeFence($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Fence => DeserializerHelper::decodeFence(clone $block, $in));

        $builder->addProperty(new Property(StateNames::MC_CONNECTION_NORTH, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::MC_CONNECTION_SOUTH, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::MC_CONNECTION_WEST, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::MC_CONNECTION_EAST, PropertyHelper::BOOL));

        $builder->addComponent(new ItemVisualBlockComponent(new GeometryBlockComponent(NexlyGeometry::FENCE . "_carried"), $material));
        $builder->addComponent(
            (new GeometryBlockComponent(identifier: NexlyGeometry::FENCE, cullingType: CullingType::FENCE))
                ->add("north_rails", MolangHelper::stateEquals(StateNames::MC_CONNECTION_NORTH, true))
                ->add("south_rails", MolangHelper::stateEquals(StateNames::MC_CONNECTION_SOUTH, true))
                ->add("west_rails", MolangHelper::stateEquals(StateNames::MC_CONNECTION_WEST, true))
                ->add("east_rails", MolangHelper::stateEquals(StateNames::MC_CONNECTION_EAST, true))
        );
        $builder->addComponent(new LeashableBlockComponent(new Vector3(0, 12, 0)));

        $builder->setMapper(FenceBlockMapper::class);
    }

    /**
     * Create permutations for fence gate blocks.
     *
     * @param Builder $builder
     * @param FenceGate $block
     * @return void
     */
    public static function makeFenceGate(Builder $builder, FenceGate $block): void
    {
        $stringId = $builder->getStringId();
        /** @var MaterialComponent $material */
        $material = $builder->getComponent(BlockComponentIds::MATERIAL_INSTANCES);

        $builder->mapSerializer(static fn (FenceGate $block): Writer => PMSerializerHelper::encodeFenceGate($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): FenceGate => PMDeserializerHelper::decodeFenceGate(clone $block, $in));

        $builder->addProperty(new Property(StateNames::MC_CARDINAL_DIRECTION, PropertyHelper::MC_CARDINAL_DIRECTION));
        $builder->addProperty(new Property(StateNames::IN_WALL_BIT, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::OPEN_BIT, PropertyHelper::BOOL));

        $builder->addComponent(new OnInteractBlockComponent());
        $builder->addComponent(new ItemVisualBlockComponent(new GeometryBlockComponent(NexlyGeometry::FENCE_GATE . "_carried"), $material));
        $builder->addComponent(
            (new GeometryBlockComponent(NexlyGeometry::FENCE_GATE))
                ->add("open_doors", MolangHelper::stateEquals(StateNames::OPEN_BIT, true))
                ->add("closed_doors", MolangHelper::stateEquals(StateNames::OPEN_BIT, false))
        );

        $builder->setMapper(FenceGateBlockMapper::class);
    }

    /**
     * Create permutations for wall blocks.
     *
     * @param Builder $builder
     * @param Wall $block
     * @return void
     */
    public static function makeWall(Builder $builder, Wall $block): void
    {
        $stringId = $builder->getStringId();
        /** @var MaterialComponent $material */
        $material = $builder->getComponent(BlockComponentIds::MATERIAL_INSTANCES);

        $builder->mapSerializer(static fn (Wall $block): Writer => PMSerializerHelper::encodeWall($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Wall => PMDeserializerHelper::decodeWall(clone $block, $in));

        $builder->addProperty(new Property(StateNames::WALL_POST_BIT, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::WALL_CONNECTION_TYPE_NORTH, PropertyHelper::WALL_CONNECTION_TYPE));
        $builder->addProperty(new Property(StateNames::WALL_CONNECTION_TYPE_SOUTH, PropertyHelper::WALL_CONNECTION_TYPE));
        $builder->addProperty(new Property(StateNames::WALL_CONNECTION_TYPE_WEST, PropertyHelper::WALL_CONNECTION_TYPE));
        $builder->addProperty(new Property(StateNames::WALL_CONNECTION_TYPE_EAST, PropertyHelper::WALL_CONNECTION_TYPE));

        $none = StateValues::WALL_CONNECTION_TYPE_NORTH_NONE;

        $isN = MolangHelper::stateEquals(StateNames::WALL_CONNECTION_TYPE_NORTH, $none);
        $isS = MolangHelper::stateEquals(StateNames::WALL_CONNECTION_TYPE_SOUTH, $none);
        $isW = MolangHelper::stateEquals(StateNames::WALL_CONNECTION_TYPE_WEST, $none);
        $isE = MolangHelper::stateEquals(StateNames::WALL_CONNECTION_TYPE_EAST, $none);

        $builder->addComponent(new ItemVisualBlockComponent(new GeometryBlockComponent(NexlyGeometry::WALL . "_render"), $material));
        $builder->addComponent(
            (new GeometryBlockComponent(NexlyGeometry::WALL))
                ->add("n", $notN = MolangHelper::stateNotEquals(StateNames::WALL_CONNECTION_TYPE_NORTH, $none))
                ->add("s", $notS = MolangHelper::stateNotEquals(StateNames::WALL_CONNECTION_TYPE_SOUTH, $none))
                ->add("w", $notW = MolangHelper::stateNotEquals(StateNames::WALL_CONNECTION_TYPE_WEST, $none))
                ->add("e", $notE = MolangHelper::stateNotEquals(StateNames::WALL_CONNECTION_TYPE_EAST, $none))
                ->add("ns", $isNS = MolangHelper::and($notN, $notS, $isW, $isE))
                ->add("we", $isWE = MolangHelper::and($isN, $isS, $notW, $notE))
                ->add("mid", MolangHelper::or(
                    MolangHelper::stateEquals(StateNames::WALL_POST_BIT, true),
                    MolangHelper::not(MolangHelper::or($isNS, $isWE))
                ))
        );
        $builder->addComponent(new LeashableBlockComponent(new Vector3(0, 12, 0)));

        $builder->setMapper(WallBlockMapper::class);
    }

    /**
     * Create permutations for trapdoor blocks.
     *
     * @param Builder $builder
     * @param Trapdoor $block
     * @return void
     */
    public static function makeTrapdoor(Builder $builder, Trapdoor $block): void
    {
        $stringId = $builder->getStringId();
        /** @var MaterialComponent $material */
        $material = $builder->getComponent(BlockComponentIds::MATERIAL_INSTANCES);

        $builder->mapSerializer(static fn (Trapdoor $block): Writer => PMSerializerHelper::encodeTrapdoor($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Trapdoor => PMDeserializerHelper::decodeTrapdoor(clone $block, $in));

        $builder->addProperty(new Property(StateNames::DIRECTION, PropertyHelper::get("horizontalFacing5Minus")));
        $builder->addProperty(new Property(StateNames::UPSIDE_DOWN_BIT, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::OPEN_BIT, PropertyHelper::BOOL));

        $builder->addComponent(new OnInteractBlockComponent());

        $builder->addComponent($geometryComponent = (new GeometryBlockComponent(identifier: NexlyGeometry::TRAPDOOR, cullingType: CullingType::TRAPDOOR)));
        $builder->addComponent(new ItemVisualBlockComponent($geometryComponent, $material));

        $box = new BoxCollision(new Vector3(-8, 0, -8), new Vector3(16, 2.92, 16));
        $builder->addComponent(new CollisionBoxBlockComponent(true, [$box]));
        $builder->addComponent(new SelectionBoxBlockComponent(true, [$box]));

        $builder->setMapper(TrapdoorBlockMapper::class);
    }

    /**
     * Create permutations for hopper blocks.
     *
     * @param Builder $builder
     * @param Hopper $block
     * @return void
     */
    public static function makeHopper(Builder $builder, Hopper $block): void
    {
        $stringId = $builder->getStringId();

        $builder->mapSerializer(static fn (Hopper $block): Writer => SerializerHelper::encodeHopper($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Hopper => DeserializerHelper::decodeHopper(clone $block, $in));

        $builder->addProperty(new Property(StateNames::FACING_DIRECTION, PropertyHelper::get("facing")));
        $builder->addProperty(new Property(StateNames::TOGGLE_BIT, PropertyHelper::BOOL));

        $builder->addComponent(
            (new GeometryBlockComponent(NexlyGeometry::HOPPER))
                ->add("ground", MolangHelper::stateEquals(StateNames::FACING_DIRECTION, Facing::DOWN))
                ->add("north", MolangHelper::stateEquals(StateNames::FACING_DIRECTION, Facing::NORTH))
                ->add("south", MolangHelper::stateEquals(StateNames::FACING_DIRECTION, Facing::SOUTH))
                ->add("west", MolangHelper::stateEquals(StateNames::FACING_DIRECTION, Facing::WEST))
                ->add("east", MolangHelper::stateEquals(StateNames::FACING_DIRECTION, Facing::EAST))
        );

        $builder->addComponent(new MaterialComponent([
            new Material($builder->getName() . "_top", target: Target::UP, renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED),
            new Material($builder->getName() . "_inside", target: Target::DOWN, renderMethod: RenderMethod::ALPHA_TEST),
            new Material($builder->getName() . "_outside", target: Target::NORTH, renderMethod: RenderMethod::ALPHA_TEST),
            new Material($builder->getName() . "_outside", target: Target::SOUTH, renderMethod: RenderMethod::ALPHA_TEST),
            new Material($builder->getName() . "_outside", target: Target::WEST, renderMethod: RenderMethod::ALPHA_TEST),
            new Material($builder->getName() . "_outside", target: Target::EAST, renderMethod: RenderMethod::ALPHA_TEST)
        ]));
    }

    /**
     * Create permutations for furnace blocks.
     *
     * @param Builder $builder
     * @param Furnace $block
     * @return void
     */
    public static function makeFurnace(Builder $builder, Furnace $block): void
    {
        [$unlitId, $litId] = StringsHelper::wood($builder->getStringId());

        $builder->mapSerializer(static fn (Furnace $block): Writer => PMSerializerHelper::encodeFurnace($block, $unlitId, $litId));
        $builder->mapDeserializer(static fn (Reader $in): Furnace => DeserializerHelper::decodeFurnace(clone $block, $in));

        $builder->addProperty(new Property(StateNames::MC_CARDINAL_DIRECTION, PropertyHelper::MC_CARDINAL_DIRECTION));
    }

    /**
     * Create permutations for head blocks (e.g., skeleton skull, zombie head).
     *
     * @param Builder $builder
     * @param HeadBlock $block
     * @return void
     */
    public static function makeHead(Builder $builder, HeadBlock $block): void
    {
        $stringId = $builder->getStringId();

        $builder->mapSerializer(static fn (HeadBlock $block): Writer => SerializerHelper::encodeHeadBlock($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): HeadBlock => DeserializerHelper::decodeHeadBlock(clone $block, $in));

        $rotations = range($block::MIN_ROTATION, $block::MAX_ROTATION);

        $builder->addProperty(new Property(StateNames::FACING_DIRECTION, PropertyHelper::get("facingExceptDown")));
        $builder->addProperty(new Property(StateNames::ROTATION, $rotations));

        $geometryComponent = new GeometryBlockComponent(identifier: NexlyGeometry::HEAD_BLOCK);
        foreach ($rotations as $rot) {
            $geometryComponent->add("bone_$rot", MolangHelper::stateEquals(StateNames::ROTATION, $rot));
        }
        $builder->addComponent($geometryComponent);
        $builder->addComponent($material = new MaterialComponent([new Material($block->getTexture(), renderMethod: RenderMethod::BLEND)]));
        $builder->addComponent(new ItemVisualBlockComponent($geometryComponent, $material));

        $builder->setMapper(HeadBlockMapper::class);
    }

    /**
     * Create permutations for ladder blocks.
     *
     * @param Builder $builder
     * @param Ladder $block
     * @return void
     */
    public static function makeLadder(Builder $builder, Ladder $block): void
    {
        $stringId = $builder->getStringId();

        $builder->mapSerializer(static fn (Ladder $block): Writer => SerializerHelper::encodeLadder($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Ladder => DeserializerHelper::decodeLadder(clone $block, $in));

        $builder->addProperty(new Property(StateNames::FACING_DIRECTION, PropertyHelper::get("horizontalFacingClassic")));

        $builder->addComponent(new GeometryBlockComponent(NexlyGeometry::LADDER));

        $builder->setMapper(LadderBlockMapper::class);
    }

    /**
     * Create permutations for farmland blocks.
     *
     * @param Builder $builder
     * @param Farmland $block
     * @return void
     */
    public static function makeFarmland(Builder $builder, Farmland $block): void
    {
        $stringId = $builder->getStringId();

        $builder->mapSerializer(static fn (Farmland $block): Writer => SerializerHelper::encodeFarmland($block, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Farmland => DeserializerHelper::decodeFarmland(clone $block, $in));

        $builder->addProperty(new Property(StateNames::MOISTURIZED_AMOUNT, range(0, Farmland::MAX_WETNESS)));

        $builder->addComponent(new MaterialComponent([
            new Material(
                texture: $builder->getName() . "_side",
                target: Target::ALL,
                renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED
            )
        ]));

        $builder->addComponent(new GeometryBlockComponent(NexlyGeometry::FARMLAND));
        $builder->addComponent(new SelectionBoxBlockComponent(true, [BoxCollision::FARMLAND()]));
        $builder->addComponent(new CollisionBoxBlockComponent(true, [BoxCollision::FARMLAND()]));
        $builder->addComponent(new OnInteractBlockComponent());

        $builder->setMapper(FarmlandBlockMapper::class);
    }

    /**
     * Create permutations for flower blocks
     *
     * @param Builder $builder
     * @param Flower $block
     * @return void
     */
    public static function makeFlower(Builder $builder, Flower $block): void
    {
        $materials = [new Material($builder->getName(), renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED, ambientOcclusion: 0.0, faceDimming: false)];

        $builder->addComponent(new SelectionBoxBlockComponent(true, [BoxCollision::FLOWER()]));
        $builder->addComponent($geometryComponent = new GeometryBlockComponent(Geometry::CROSS));
        $builder->addComponent(new MaterialComponent($materials));
        $builder->addComponent(new FlowerPottableBlockComponent());
        $builder->addComponent(new EmbeddedVisualBlockComponent($geometryComponent, new MaterialComponent($materials)));
        $builder->addComponent(new RandomOffsetBlockComponent(new BlockPosition(0, 0, 0), new RangeOffset(new Vector3(-4, 1, -4), new Vector3(4, 0, 4))));
    }

    /**
     * Create permutations for glass pane blocks
     *
     * @param Builder $builder
     * @param GlassPane $block
     * @return void
     */
    public static function makeGlassPane(Builder $builder, GlassPane $block): void
    {
        if (!$block instanceof NexlyGlassPane) {
            throw new RuntimeException("Glass pane blocks must extend " . NexlyGlassPane::class . " to be registered.");
        }

        $stringId = $builder->getStringId();
        $baseColor = $block instanceof Colored ? $block->getColor() : null;

        $builder->mapSerializer(static fn (GlassPane $state): Writer => SerializerHelper::encodeGlassPane($state, new Writer(
            StringsHelper::recolor($stringId, $baseColor, $state instanceof Colored ? $state->getColor() : null)
        )));
        $builder->mapDeserializer(static fn (Reader $in): GlassPane => DeserializerHelper::decodeGlassPane(clone $block, $in));

        $builder->addProperty(new Property(StateNames::MC_CONNECTION_NORTH, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::MC_CONNECTION_SOUTH, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::MC_CONNECTION_WEST, PropertyHelper::BOOL));
        $builder->addProperty(new Property(StateNames::MC_CONNECTION_EAST, PropertyHelper::BOOL));

        $n = MolangHelper::stateEquals(StateNames::MC_CONNECTION_NORTH, true);
        $s = MolangHelper::stateEquals(StateNames::MC_CONNECTION_SOUTH, true);
        $w = MolangHelper::stateEquals(StateNames::MC_CONNECTION_WEST, true);
        $e = MolangHelper::stateEquals(StateNames::MC_CONNECTION_EAST, true);

        $notN = MolangHelper::stateEquals(StateNames::MC_CONNECTION_NORTH, false);
        $notS = MolangHelper::stateEquals(StateNames::MC_CONNECTION_SOUTH, false);
        $notW = MolangHelper::stateEquals(StateNames::MC_CONNECTION_WEST, false);
        $notE = MolangHelper::stateEquals(StateNames::MC_CONNECTION_EAST, false);

        $straightNS = MolangHelper::and($n, $s);
        $straightEW = MolangHelper::and($e, $w);

        $onlyN = MolangHelper::and($n, $notS, $notE, $notW);
        $onlyS = MolangHelper::and($s, $notN, $notE, $notW);
        $onlyE = MolangHelper::and($e, $notN, $notS, $notW);
        $onlyW = MolangHelper::and($w, $notN, $notS, $notE);

        $noConnections = MolangHelper::and($notN, $notS, $notE, $notW);
        $hasNS = MolangHelper::or($n, $s);
        $hasEW = MolangHelper::or($e, $w);

        $isCorner = MolangHelper::and($hasNS, $hasEW, MolangHelper::not($straightNS), MolangHelper::not($straightEW));
        $showBone = MolangHelper::or($noConnections, $isCorner);

        $builder->addComponent(
            $geometryComponent = (new GeometryBlockComponent(NexlyGeometry::GLASS_PANE))
                ->add("bone", $showBone)
                ->add("straight_ns", $straightNS)
                ->add("straight_ew", $straightEW)
                ->add("end_n", $onlyN)
                ->add("end_s", $onlyS)
                ->add("end_e", $onlyE)
                ->add("end_w", $onlyW)
                ->add("n", MolangHelper::and($n, MolangHelper::not($straightNS), MolangHelper::not($onlyN)))
                ->add("s", MolangHelper::and($s, MolangHelper::not($straightNS), MolangHelper::not($onlyS)))
                ->add("e", MolangHelper::and($e, MolangHelper::not($straightEW), MolangHelper::not($onlyE)))
                ->add("w", MolangHelper::and($w, MolangHelper::not($straightEW), MolangHelper::not($onlyW)))
        );
        $builder->addComponent($material = new MaterialComponent([new Material($builder->getName(), renderMethod: RenderMethod::BLEND)]));
        $builder->addComponent(new ItemVisualBlockComponent(new GeometryBlockComponent(NexlyGeometry::GLASS_PANE . "_render"), $material));
        $builder->addComponent(new EmbeddedVisualBlockComponent($geometryComponent, $material));
        $builder->addComponent(new FlowerPottableBlockComponent());

        $builder->setMapper(GlassPaneBlockMapper::class);
    }

    /**
     * Create permutations for lever blocks.
     *
     * @param Builder $builder
     * @param Lever $block
     * @return void
     */
    public static function makeLever(Builder $builder, Lever $block): void
    {
        $stringId = $builder->getStringId();

        $builder->mapSerializer(static fn (Lever $state): Writer => SerializerHelper::encodeLever($state, new Writer($stringId)));
        $builder->mapDeserializer(static fn (Reader $in): Lever => DeserializerHelper::decodeLever(clone $block, $in));

        $builder->addProperty(new Property(StateNames::LEVER_DIRECTION, PropertyHelper::LEVER_DIRECTION));
        $builder->addProperty(new Property(StateNames::OPEN_BIT, PropertyHelper::BOOL));

        $builder->addComponent(
            (new GeometryBlockComponent(NexlyGeometry::LEVER))
            ->add("open", MolangHelper::stateEquals(StateNames::OPEN_BIT, true))
            ->add("close", MolangHelper::stateEquals(StateNames::OPEN_BIT, false))
        );
        $builder->addComponent(new CollisionBoxBlockComponent(false));
        $builder->addComponent(new OnInteractBlockComponent());

        $builder->setMapper(LeverBlockMapper::class);
    }

    /**
     * Create permutations for slime blocks.
     *
     * @param Builder $builder
     * @param Slime $block
     * @return void
     */
    public static function makeSlime(Builder $builder, Slime $block): void
    {
        $builder->addComponent(new GeometryBlockComponent(NexlyGeometry::SLIME));
        $builder->addComponent(new MaterialComponent([new Material($builder->getName(), renderMethod: RenderMethod::BLEND)]));
    }

    /**
     * Create permutations for cactus blocks.
     *
     * @param Builder $builder
     * @param Cactus $block
     * @return void
     */
    public static function makeCactus(Builder $builder, Cactus $block): void
    {
        $builder->addComponent(new GeometryBlockComponent(NexlyGeometry::CACTUS));
        $builder->addComponent(new MaterialComponent([
            new Material($builder->getName() . "_top", target: Target::UP, renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED),
            new Material($builder->getName() . "_bottom", target: Target::DOWN, renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED),
            new Material($builder->getName() . "_side", target: Target::ALL, renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED),
        ]));
        $builder->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-7.0, 0.0, -7.0), new Vector3(14.0, 16.0, 14.0))]));
        $builder->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-7.0, 0.0, -7.0), new Vector3(14.0, 16.0, 14.0))]));
    }

    /**
     * @param Builder $builder
     * @param Glass $block
     * @return void
     */
    public static function makeGlass(Builder $builder, Glass $block): void
    {
        $stringId = $builder->getStringId();
        $baseColor = $block instanceof Colored ? $block->getColor() : null;

        $builder->mapSerializer(static fn (Glass $state): Writer => new Writer(
            StringsHelper::recolor($stringId, $baseColor, $state instanceof Colored ? $state->getColor() : null)
        ));
        $builder->mapDeserializer(static fn (Reader $in): Glass => clone $block);

        $builder->addComponent(new MaterialComponent([new Material($builder->getName(), renderMethod: RenderMethod::BLEND)]));
        $builder->addComponent(new GeometryBlockComponent(identifier: NexlyGeometry::GLASS, cullingType: CullingType::GLASS));
    }

    /**
     * Create permutations for colored blocks.
     *
     * @param Builder $builder
     * @param Block $block
     * @return void
     */
    public static function makeSimpleColored(Builder $builder, Block $block): void
    {
        if (!$block instanceof Colored) {
            throw new RuntimeException("The block must implement the Colored interface to use makeSimpleColored.");
        }

        $stringId = $builder->getStringId();
        $baseColor = $block->getColor();

        $builder->mapSerializer(static fn (Colored $state): Writer => new Writer(
            StringsHelper::recolor($stringId, $baseColor, $state->getColor())
        ));
        $builder->mapDeserializer(static fn (Reader $in): Block => clone $block);
    }
}
