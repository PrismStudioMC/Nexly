<?php

namespace Nexly\Blocks\Permutations;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\BlockComponentIds;
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\GeometryBlockComponent;
use Nexly\Blocks\Components\ItemVisualBlockComponent;
use Nexly\Blocks\Components\MaterialInstancesBlockComponent;
use Nexly\Blocks\Components\OnInteractBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\TransformationBlockComponent;
use Nexly\Blocks\Components\Types\BlockCollision;
use Nexly\Blocks\Components\Types\ExtendedGeometry;
use Nexly\Blocks\Components\Types\Material;
use Nexly\Blocks\Components\Types\MaterialRenderMethod;
use Nexly\Blocks\Components\Types\MaterialTarget;
use Nexly\Blocks\Permutations\Impl\FencePermutation;
use Nexly\Blocks\Permutations\Impl\WallPermutation;
use Nexly\Blocks\Vanilla\HeadBlock;
use Nexly\Blocks\Vanilla\NexlyFence;
use pocketmine\block\Crops;
use pocketmine\block\Fence;
use pocketmine\block\FenceGate;
use pocketmine\block\Hopper;
use pocketmine\block\NetherWartPlant;
use pocketmine\block\Slab;
use pocketmine\block\Trapdoor;
use pocketmine\block\utils\SlabType;
use pocketmine\block\utils\WallConnectionType;
use pocketmine\block\Wall;
use pocketmine\data\bedrock\block\BlockStateNames;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\data\bedrock\block\convert\BlockStateDeserializerHelper as DeserializerHelper;
use pocketmine\data\bedrock\block\convert\BlockStateReader as Reader;
use pocketmine\data\bedrock\block\convert\BlockStateSerializerHelper as SerializerHelper;
use pocketmine\data\bedrock\block\convert\BlockStateWriter as Writer;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use ReflectionClass;

final class NexlyPermutations
{
    /**
     * Create permutations for crop blocks (e.g., wheat, carrots, potatoes).
     *
     * @param Builder $builder
     * @param Crops $block
     * @return void
     */
    public static function makeCrop(Builder $builder, Crops $block): void
    {
        $stringId = $builder->getStringId();
        $builder->setSerializer(static fn (Crops $block) => SerializerHelper::encodeCrops($block, new Writer($stringId)));
        $builder->setDeserializer(static fn (Reader $in) => DeserializerHelper::decodeCrops(clone $block, $in));
        $builder->addProperty(new BlockProperty(StateNames::GROWTH, $ages = range(0, $block::MAX_AGE)));
        $builder->addComponent(new GeometryBlockComponent(ExtendedGeometry::CROP->toString()));
        foreach ($ages as $age) {
            $builder->addPermutation(Permutation::create("query.block_state('" . StateNames::GROWTH . "') == {$age}")
                ->addComponent(SelectionBoxBlockComponent::fromCrops($block, $age))
                ->addComponent(new MaterialInstancesBlockComponent([new Material($builder->getName() . "_{$age}", renderMethod: MaterialRenderMethod::ALPHA_TEST)])));
        }
    }

    /**
     * Create permutations for Nether Wart plant blocks.
     *
     * @param Builder $builder
     * @param NetherWartPlant $block
     * @return void
     */
    public static function makeNetherPlant(Builder $builder, NetherWartPlant $block): void
    {
        $stringId = $builder->getStringId();
        $builder->setSerializer(static fn (NetherWartPlant $block) => (new Writer($stringId))->writeInt(StateNames::AGE, $block->getAge()));
        $builder->setDeserializer(static fn (Reader $in) => (clone $block)->setAge($in->readBoundedInt(StateNames::AGE, 0, 3)));
        $builder->addProperty(new BlockProperty(StateNames::AGE, $ages = range(0, $block::MAX_AGE)));
        $builder->addComponent(new GeometryBlockComponent(ExtendedGeometry::NETHER_WART->toString()));
        foreach ($ages as $age) {
            $builder->addPermutation(Permutation::create("query.block_state('" . StateNames::AGE . "') == {$age}")
                ->addComponent(SelectionBoxBlockComponent::fromCrops($block, $age))
                ->addComponent(new MaterialInstancesBlockComponent([new Material($builder->getName() . "_{$age}", renderMethod: MaterialRenderMethod::ALPHA_TEST)])));
        }
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
        $builder->setSerializer(static fn (Slab $block) => (new Writer($stringId))->writeString(BlockStateNames::MC_VERTICAL_HALF, match ($block->getSlabType()) {
            SlabType::BOTTOM => StateValues::MC_VERTICAL_HALF_BOTTOM,
            SlabType::TOP => StateValues::MC_VERTICAL_HALF_TOP,
            SlabType::DOUBLE => "double",
            default => throw new \RuntimeException("Invalid slab type"),
        }));
        $builder->setDeserializer(static fn (Reader $in) => (clone $block)->setSlabType(match ($in->readString(BlockStateNames::MC_VERTICAL_HALF)) {
            StateValues::MC_VERTICAL_HALF_BOTTOM => SlabType::BOTTOM,
            StateValues::MC_VERTICAL_HALF_TOP => SlabType::TOP,
            "double" => SlabType::DOUBLE,
            default => throw new \RuntimeException("Invalid slab type"),
        }));

        $builder->addComponent($geometry = new GeometryBlockComponent(ExtendedGeometry::SLAB->toString()));

        /** @var MaterialInstancesBlockComponent $material */
        $material = $builder->getComponent(BlockComponentIds::MATERIAL_INSTANCES);
        $builder->addComponent(new ItemVisualBlockComponent($geometry, $material));
        $builder->addProperty(new BlockProperty(BlockStateNames::MC_VERTICAL_HALF, [StateValues::MC_VERTICAL_HALF_BOTTOM, StateValues::MC_VERTICAL_HALF_TOP, "double"]))
            ->addPermutation(
                Permutation::create("q.block_state('" . BlockStateNames::MC_VERTICAL_HALF . "') == '" . StateValues::MC_VERTICAL_HALF_BOTTOM . "'")
                ->addComponent(new CollisionBoxBlockComponent(true, BlockCollision::SLAB()))
                ->addComponent(new SelectionBoxBlockComponent(true, BlockCollision::SLAB()))
                ->addComponent(new TransformationBlockComponent(translation: new Vector3(0, -0.25, 0)))
            )
            ->addPermutation(
                Permutation::create("q.block_state('" . BlockStateNames::MC_VERTICAL_HALF . "') == '" . StateValues::MC_VERTICAL_HALF_TOP . "'")
                ->addComponent(new CollisionBoxBlockComponent(true, BlockCollision::SLAB()))
                ->addComponent(new SelectionBoxBlockComponent(true, BlockCollision::SLAB()))
                ->addComponent(new TransformationBlockComponent(translation: new Vector3(0, 0.25, 0)))
            )
            ->addPermutation(Permutation::create("q.block_state('" . BlockStateNames::MC_VERTICAL_HALF . "') == 'double'")
                ->addComponent(new GeometryBlockComponent())
                ->addComponent(new CollisionBoxBlockComponent(true))
                ->addComponent(new SelectionBoxBlockComponent(true)));
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
            throw new \RuntimeException("Fence blocks must extend " . NexlyFence::class . " to be registered.");
        }

        $reflection = new ReflectionClass(Fence::class);
        $connections = $reflection->getProperty("connections");

        $stringId = $builder->getStringId();
        $builder->setSerializer(static function (Fence $block) use ($connections, $stringId): Writer {
            $co = $connections->getValue($block);
            return (new Writer($stringId))
                ->writeInt("mc:n", isset($co[Facing::NORTH]))
                ->writeInt("mc:s", isset($co[Facing::SOUTH]))
                ->writeInt("mc:w", isset($co[Facing::WEST]))
                ->writeInt("mc:e", isset($co[Facing::EAST]));
        });
        $builder->setDeserializer(static function (Reader $in) use ($block, $reflection, $connections): Fence {
            $cloned = clone $block;
            $co = [];

            if ($in->readInt("mc:n")) {
                $co[Facing::NORTH] = Facing::NORTH;
            }
            if ($in->readInt("mc:s")) {
                $co[Facing::SOUTH] = Facing::SOUTH;
            }
            if ($in->readInt("mc:w")) {
                $co[Facing::WEST] = Facing::WEST;
            }
            if ($in->readInt("mc:e")) {
                $co[Facing::EAST] = Facing::EAST;
            }

            $connections->setValue($cloned, $co);
            return $cloned;
        });
        $builder->addProperty(new BlockProperty("mc:n", [0, 1]))
            ->addProperty(new BlockProperty("mc:s", [0, 1]))
            ->addProperty(new BlockProperty("mc:w", [0, 1]))
            ->addProperty(new BlockProperty("mc:e", [0, 1]));

        /** @var MaterialInstancesBlockComponent $material */
        $material = $builder->getComponent(BlockComponentIds::MATERIAL_INSTANCES);
        $builder->addComponent(new ItemVisualBlockComponent(new GeometryBlockComponent(ExtendedGeometry::FENCE->toString() . "_render"), $material));
        $builder->addComponent(
            (new GeometryBlockComponent(ExtendedGeometry::FENCE->toString()))
            ->add("n", "q.block_state('mc:n') == 1")
            ->add("s", "q.block_state('mc:s') == 1")
            ->add("w", "q.block_state('mc:w') == 1")
            ->add("e", "q.block_state('mc:e') == 1")
        );

        FencePermutation::create($builder)->apply();
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
        $builder->setSerializer(
            static fn (FenceGate $block) => (new Writer($stringId))
            ->writeInt(StateNames::MC_CARDINAL_DIRECTION, match ($block->getFacing()) {
                Facing::NORTH => 2,
                Facing::SOUTH => 3,
                Facing::WEST => 4,
                Facing::EAST => 5,
                default => throw new \RuntimeException("Invalid facing direction"),
            })
            ->writeInt(StateNames::IN_WALL_BIT, $block->isInWall() ? 1 : 0)
            ->writeInt(StateNames::OPEN_BIT, $block->isOpen() ? 1 : 0)
        );
        $builder->setDeserializer(
            static fn (Reader $in) => (clone $block)
            ->setFacing(match ($in->readInt(StateNames::MC_CARDINAL_DIRECTION)) {
                2 => Facing::NORTH,
                3 => Facing::SOUTH,
                4 => Facing::WEST,
                5 => Facing::EAST,
                default => throw new \RuntimeException("Invalid facing direction"),
            })
            ->setInWall($in->readInt(BlockStateNames::IN_WALL_BIT))
            ->setOpen($in->readInt(BlockStateNames::OPEN_BIT))
        );
        $builder->addProperty(new BlockProperty(StateNames::MC_CARDINAL_DIRECTION, $facings = range(2, 5)));
        $builder->addProperty(new BlockProperty(StateNames::IN_WALL_BIT, [0, 1]));
        $builder->addProperty(new BlockProperty(StateNames::OPEN_BIT, [0, 1]));

        /** @var MaterialInstancesBlockComponent $material */
        $material = $builder->getComponent(BlockComponentIds::MATERIAL_INSTANCES);
        $builder->addComponent(new ItemVisualBlockComponent(new GeometryBlockComponent(ExtendedGeometry::FENCE_GATE->toString() . "_render"), $material));
        $builder->addComponent(new OnInteractBlockComponent());
        foreach ($facings as $dir) {
            foreach ([0, 1] as $open) {
                foreach ([0, 1] as $inWall) {
                    $expr =
                        "q.block_state('" . StateNames::OPEN_BIT . "') == $open && " .
                        "q.block_state('" . StateNames::IN_WALL_BIT . "') == $inWall && " .
                        "q.block_state('" . StateNames::MC_CARDINAL_DIRECTION . "') == $dir";

                    $permutation = Permutation::create($expr);
                    $permutation->addComponent(
                        (new GeometryBlockComponent(ExtendedGeometry::FENCE_GATE->toString()))
                        ->add("open", "q.block_state('" . StateNames::OPEN_BIT . "') == 1")
                        ->add("close", "q.block_state('" . StateNames::OPEN_BIT . "') == 0")
                    )->addComponent(
                        new CollisionBoxBlockComponent(!$open, BlockCollision::FENCE_GATE())
                    )->addComponent(
                        new SelectionBoxBlockComponent(true, BlockCollision::FENCE_GATE())
                    )->addComponent(new TransformationBlockComponent(match ($dir) {
                        2 => new Vector3(0, 0, 0),
                        3 => new Vector3(0, 180, 0),
                        4 => new Vector3(0, 90, 0),
                        5 => new Vector3(0, 270, 0),
                        default => throw new \RuntimeException("Invalid direction")
                    }, translation: match ($inWall) {
                        0 => new Vector3(0, 0, 0),
                        1 => new Vector3(0, -0.187, 0),
                        default => throw new \RuntimeException("Invalid inWall value")
                    }));

                    $builder->addPermutation($permutation);
                }
            }
        }
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
        $builder->setSerializer(
            static fn (Wall $block) => (new Writer($stringId))
            ->writeInt(StateNames::WALL_POST_BIT, $block->isPost())
            ->writeInt(StateNames::WALL_CONNECTION_TYPE_NORTH, ($encode = static fn (WallConnectionType|null $v) => match ($v) {
                default => 0,
                WallConnectionType::SHORT => 1,
                WallConnectionType::TALL => 2,
            })($block->getConnection(Facing::NORTH)))
            ->writeInt(StateNames::WALL_CONNECTION_TYPE_SOUTH, $encode($block->getConnection(Facing::SOUTH)))
            ->writeInt(StateNames::WALL_CONNECTION_TYPE_WEST, $encode($block->getConnection(Facing::WEST)))
            ->writeInt(StateNames::WALL_CONNECTION_TYPE_EAST, $encode($block->getConnection(Facing::EAST)))
        );
        $builder->setDeserializer(
            static fn (Reader $in) => (clone $block)
            ->setPost($in->readInt(StateNames::WALL_POST_BIT))
            ->setConnection(Facing::NORTH, ($decode = static fn (int $v) => match ($v) {
                1 => WallConnectionType::SHORT,
                2 => WallConnectionType::TALL,
                default => null,
            })($in->readInt(StateNames::WALL_CONNECTION_TYPE_NORTH)))
            ->setConnection(Facing::SOUTH, $decode($in->readInt(StateNames::WALL_CONNECTION_TYPE_SOUTH)))
            ->setConnection(Facing::WEST, $decode($in->readInt(StateNames::WALL_CONNECTION_TYPE_WEST)))
            ->setConnection(Facing::EAST, $decode($in->readInt(StateNames::WALL_CONNECTION_TYPE_EAST)))
        );

        $builder->addProperty(new BlockProperty(StateNames::WALL_POST_BIT, [0, 1]))
            ->addProperty(new BlockProperty(StateNames::WALL_CONNECTION_TYPE_NORTH, [0, 1, 2]))
            ->addProperty(new BlockProperty(StateNames::WALL_CONNECTION_TYPE_SOUTH, [0, 1, 2]))
            ->addProperty(new BlockProperty(StateNames::WALL_CONNECTION_TYPE_WEST, [0, 1, 2]))
            ->addProperty(new BlockProperty(StateNames::WALL_CONNECTION_TYPE_EAST, [0, 1, 2]));

        /** @var MaterialInstancesBlockComponent $material */
        $material = $builder->getComponent(BlockComponentIds::MATERIAL_INSTANCES);
        $builder->addComponent(new ItemVisualBlockComponent(new GeometryBlockComponent(ExtendedGeometry::WALL->toString() . "_render"), $material));
        $builder->addComponent(
            (new GeometryBlockComponent(ExtendedGeometry::WALL->toString()))
            ->add("n", "q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') != 0")
            ->add("s", "q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') != 0")
            ->add("w", "q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') != 0")
            ->add("e", "q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') != 0")
            ->add("ns", "q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') == 0")
            ->add("we", "q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') != 0")
            ->add("mid", "q.block_state('" . StateNames::WALL_POST_BIT . "') == 1 || !((q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') == 0) || (q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') == 0))")
        );

        WallPermutation::create($builder)->apply();
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
        $builder->setSerializer(
            static fn (Trapdoor $block) => (new Writer($stringId))
            ->write5MinusHorizontalFacing($block->getFacing())
            ->writeInt(StateNames::UPSIDE_DOWN_BIT, $block->isTop())
            ->writeInt(StateNames::OPEN_BIT, $block->isOpen())
        );
        $builder->setDeserializer(
            static fn (Reader $in) => (clone $block)
            ->setFacing($in->read5MinusHorizontalFacing())
            ->setTop($in->readInt(StateNames::UPSIDE_DOWN_BIT))
            ->setOpen($in->readInt(StateNames::OPEN_BIT))
        );

        $builder->addProperty(new BlockProperty(StateNames::DIRECTION, $facings = [0, 1, 2, 3])); // 0: East, 1: West, 2: South, 3: North
        $builder->addProperty(new BlockProperty(StateNames::UPSIDE_DOWN_BIT, [0, 1]));
        $builder->addProperty(new BlockProperty(StateNames::OPEN_BIT, [0, 1]));

        $builder->addComponent(new OnInteractBlockComponent());
        $builder->addComponent($material = new MaterialInstancesBlockComponent([new Material($builder->getName(), renderMethod: MaterialRenderMethod::BLEND)]));
        $builder->addComponent(new ItemVisualBlockComponent((new GeometryBlockComponent(ExtendedGeometry::TRAPDOOR->toString()))
            ->add("open", "false")
            ->add("close", "true"), $material));

        $builder->addComponent(
            (new GeometryBlockComponent(ExtendedGeometry::TRAPDOOR->toString()))
            ->add("open", "q.block_state('" . StateNames::OPEN_BIT . "') == 1")
            ->add("close", "q.block_state('" . StateNames::OPEN_BIT . "') == 0")
        );

        foreach ($facings as $dir) {
            foreach ([0, 1] as $open) {
                foreach ([0, 1] as $top) {
                    $expr =
                        "q.block_state('" . StateNames::DIRECTION . "') == $dir && " .
                        "q.block_state('" . StateNames::OPEN_BIT . "') == $open && " .
                        "q.block_state('" . StateNames::UPSIDE_DOWN_BIT . "') == $top";

                    $permutation = Permutation::create($expr);

                    $box = !$open ?
                        new BlockCollision(new Vector3(-8, 0, -8), new Vector3(16, 3, 16)) :
                        new BlockCollision(new Vector3(-8, 0, -8), new Vector3(3, 16, 16));

                    $translation = $top && !$open ? new Vector3(0, 0.813, 0) : new Vector3(0, 0, 0);

                    $permutation->addComponent(new CollisionBoxBlockComponent(true, $box))
                        ->addComponent(new SelectionBoxBlockComponent(true, $box))
                        ->addComponent(new TransformationBlockComponent(match ($dir) {
                            0 => new Vector3(0, 180, 0),    // East
                            1 => new Vector3(0, 0, 0),  // West
                            2 => new Vector3(0, 90, 0),   // South
                            3 => new Vector3(0, 270, 0),  // North
                            default => throw new \RuntimeException("Invalid direction")
                        }, translation: $translation));

                    $builder->addPermutation($permutation);
                }
            }
        }
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
        $builder->setSerializer(
            static fn (Hopper $block) => (new Writer($stringId))
            ->writeInt(StateNames::TOGGLE_BIT, $block->isPowered())
            ->writeFacingWithoutUp($block->getFacing())
        );
        $builder->setDeserializer(
            static fn (Reader $in) => (clone $block)
            ->setPowered($in->readInt(StateNames::TOGGLE_BIT))
            ->setFacing($in->readFacingWithoutUp())
        );

        $builder->addProperty(new BlockProperty(StateNames::FACING_DIRECTION, $facings = [0, 2, 3, 4, 5])); // 0: Down, 2: North, 3: South, 4: West, 5: East
        $builder->addProperty(new BlockProperty(StateNames::TOGGLE_BIT, [0, 1]));

        $builder->addComponent(
            (new GeometryBlockComponent(ExtendedGeometry::HOPPER->toString()))
            ->add("ground", "q.block_state('" . StateNames::FACING_DIRECTION . "') == 0")
            ->add("north", "q.block_state('" . StateNames::FACING_DIRECTION . "') == 2")
            ->add("south", "q.block_state('" . StateNames::FACING_DIRECTION . "') == 3")
            ->add("west", "q.block_state('" . StateNames::FACING_DIRECTION . "') == 4")
            ->add("east", "q.block_state('" . StateNames::FACING_DIRECTION . "') == 5")
        );
        $builder->addComponent(new MaterialInstancesBlockComponent([
            new Material($builder->getName() . "_top", target: MaterialTarget::UP, renderMethod: MaterialRenderMethod::ALPHA_TEST),
            new Material($builder->getName() . "_inside", target: MaterialTarget::DOWN, renderMethod: MaterialRenderMethod::ALPHA_TEST),
            new Material($builder->getName() . "_outside", target: MaterialTarget::NORTH, renderMethod: MaterialRenderMethod::ALPHA_TEST),
            new Material($builder->getName() . "_outside", target: MaterialTarget::SOUTH, renderMethod: MaterialRenderMethod::ALPHA_TEST),
            new Material($builder->getName() . "_outside", target: MaterialTarget::WEST, renderMethod: MaterialRenderMethod::ALPHA_TEST),
            new Material($builder->getName() . "_outside", target: MaterialTarget::EAST, renderMethod: MaterialRenderMethod::ALPHA_TEST)
        ]));
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
        $builder->setSerializer(static function (HeadBlock $b) use ($stringId): Writer {
            return (new Writer($stringId))
                ->writeFacingWithoutDown($b->getFacing())
                ->writeInt(StateNames::ROTATION, $b->getRotation());
        });
        $builder->setDeserializer(static function (Reader $in) use ($block): HeadBlock {
            return (clone $block)
                ->setFacing($in->readFacingWithoutDown())
                ->setRotation($in->readInt(StateNames::ROTATION));
        });

        $builder->addProperty(new BlockProperty(StateNames::FACING_DIRECTION, $facings = [1, 2, 3, 4, 5])); // 1: Up, 2: North, 3: South, 4: West, 5: East
        $builder->addProperty(new BlockProperty(StateNames::ROTATION, $rotations = range($block::MIN_ROTATION, $block::MAX_ROTATION))); // 0-15
        $builder->addComponent($geometry = (new GeometryBlockComponent(ExtendedGeometry::MOBHEAD->toString())));
        $builder->addComponent($material = new MaterialInstancesBlockComponent([new Material($block->getTexture(), renderMethod: MaterialRenderMethod::BLEND)]));
        $builder->addComponent(new ItemVisualBlockComponent($geometry, $material));
        foreach ($facings as $dir) {
            foreach ($rotations as $rot) {
                $expr =
                    "q.block_state('" . StateNames::FACING_DIRECTION . "') == $dir && " .
                    "q.block_state('" . StateNames::ROTATION . "') == $rot";

                $permutation = Permutation::create($expr);
                $permutation->addComponent(new CollisionBoxBlockComponent(true, BlockCollision::MOBHEAD()))
                    ->addComponent(new SelectionBoxBlockComponent(true, BlockCollision::MOBHEAD()))
                    ->addComponent(new TransformationBlockComponent(translation: new Vector3($dir !== 1 ? match($dir) {
                        4 => 0.24,
                        5 => -0.24,
                        default => 0
                    } : 0, $dir !== 1 ? 0.25 : 0, $dir !== 1 ? match($dir) {
                        2 => 0.24,
                        3 => -0.24,
                        default => 0
                    } : 0)));

                $builder->addPermutation($permutation);
            }
        }
    }
}
