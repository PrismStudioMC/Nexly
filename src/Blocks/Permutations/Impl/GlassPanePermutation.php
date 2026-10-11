<?php

namespace Nexly\Blocks\Permutations\Impl;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\Types\BoxCollision;
use Nexly\Blocks\Permutations\Permutation;
use pocketmine\math\Vector3;

class GlassPanePermutation
{
    protected function __construct(
        protected Builder $builder
    ) {
    }

    /**
     * Creates a new instance of WallPermutation.
     *
     * @param Builder $builder
     * @return self
     */
    public static function create(Builder $builder): self
    {
        return new self($builder);
    }

    /**
     * Applies the wall permutations to the builder.
     *
     * @return Builder
     */
    public function apply(): Builder
    {
        return $this->builder
            ->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 0 && q.block_state('minecraft:connection_south') == 0 && q.block_state('minecraft:connection_west') == 0 && q.block_state('minecraft:connection_east') == 0")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -1), new Vector3(2, 16, 2))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -1), new Vector3(2, 16, 2))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 1 && q.block_state('minecraft:connection_south') == 1 && q.block_state('minecraft:connection_west') == 1 && q.block_state('minecraft:connection_east') == 1")
                    ->addComponent(new CollisionBoxBlockComponent(true/*, new BlockCollision(new Vector3(-2, 0, -2), new Vector3(4, 16, 4))*/)) // IMPOSSIBLE TO REPRODUCE THIS CASE IN-GAME
                    ->addComponent(new SelectionBoxBlockComponent(true))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 1 && q.block_state('minecraft:connection_south') == 0 && q.block_state('minecraft:connection_west') == 0 && q.block_state('minecraft:connection_east') == 0")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -8), new Vector3(2, 16, 9))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -8), new Vector3(2, 16, 9))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 0 && q.block_state('minecraft:connection_south') == 1 && q.block_state('minecraft:connection_west') == 0 && q.block_state('minecraft:connection_east') == 0")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -1), new Vector3(2, 16, 9))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -1), new Vector3(2, 16, 9))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 0 && q.block_state('minecraft:connection_south') == 0 && q.block_state('minecraft:connection_west') == 1 && q.block_state('minecraft:connection_east') == 0")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -1), new Vector3(9, 16, 2))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -1), new Vector3(9, 16, 2))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 0 && q.block_state('minecraft:connection_south') == 0 && q.block_state('minecraft:connection_west') == 0 && q.block_state('minecraft:connection_east') == 1")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -1), new Vector3(9, 16, 2))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -1), new Vector3(9, 16, 2))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 1 && q.block_state('minecraft:connection_south') == 1 && q.block_state('minecraft:connection_west') == 0 && q.block_state('minecraft:connection_east') == 0")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -8), new Vector3(2, 16, 16))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -8), new Vector3(2, 16, 16))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 0 && q.block_state('minecraft:connection_south') == 0 && q.block_state('minecraft:connection_west') == 1 && q.block_state('minecraft:connection_east') == 1")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -1), new Vector3(16, 16, 2))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -1), new Vector3(16, 16, 2))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 1 && q.block_state('minecraft:connection_south') == 0 && q.block_state('minecraft:connection_west') == 1 && q.block_state('minecraft:connection_east') == 0")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -2), new Vector3(9, 16, 2))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -8), new Vector3(9, 16, 9))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 1 && q.block_state('minecraft:connection_south') == 0 && q.block_state('minecraft:connection_west') == 0 && q.block_state('minecraft:connection_east') == 1")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -8), new Vector3(2, 16, 9))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -8), new Vector3(9, 16, 9))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 0 && q.block_state('minecraft:connection_south') == 1 && q.block_state('minecraft:connection_west') == 1 && q.block_state('minecraft:connection_east') == 0")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -1), new Vector3(2, 16, 9))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -1), new Vector3(9, 16, 9))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 0 && q.block_state('minecraft:connection_south') == 1 && q.block_state('minecraft:connection_west') == 0 && q.block_state('minecraft:connection_east') == 1")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -1), new Vector3(9, 16, 2))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -1), new Vector3(9, 16, 9))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 1 && q.block_state('minecraft:connection_south') == 0 && q.block_state('minecraft:connection_west') == 1 && q.block_state('minecraft:connection_east') == 1")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -1), new Vector3(16, 16, 2))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -8), new Vector3(16, 16, 9))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 0 && q.block_state('minecraft:connection_south') == 1 && q.block_state('minecraft:connection_west') == 1 && q.block_state('minecraft:connection_east') == 1")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -1), new Vector3(16, 16, 2))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -1), new Vector3(16, 16, 9))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 1 && q.block_state('minecraft:connection_south') == 1 && q.block_state('minecraft:connection_west') == 1 && q.block_state('minecraft:connection_east') == 0")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -8), new Vector3(2, 16, 16))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -8), new Vector3(9, 16, 16))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition("q.block_state('minecraft:connection_north') == 1 && q.block_state('minecraft:connection_south') == 1 && q.block_state('minecraft:connection_west') == 0 && q.block_state('minecraft:connection_east') == 1")
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-1, 0, -8), new Vector3(2, 16, 16))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -8), new Vector3(9, 16, 16))]))
            );
    }
}
