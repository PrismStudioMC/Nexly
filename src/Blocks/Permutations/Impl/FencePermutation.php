<?php

namespace Nexly\Blocks\Permutations\Impl;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\Types\BoxCollision;
use Nexly\Blocks\Permutations\Permutation;
use pocketmine\math\Vector3;

class FencePermutation
{
    private function __construct(
        private Builder $builder
    ) {
    }

    /**
     * Creates a new instance of FencePermutation.
     *
     * @param Builder $builder
     * @return self
     */
    public static function create(Builder $builder): self
    {
        return new self($builder);
    }

    /**
     * Helper to generate the block state condition string cleanly.
     */
    private function getCondition(int $n, int $s, int $w, int $e): string
    {
        return "q.block_state('minecraft:connection_north') == {$n} && " .
            "q.block_state('minecraft:connection_south') == {$s} && " .
            "q.block_state('minecraft:connection_west') == {$w} && " .
            "q.block_state('minecraft:connection_east') == {$e}";
    }

    /**
     * Applies the fence permutations to the builder.
     *
     * @return Builder
     */
    public function apply(): Builder
    {
        return $this->builder
            // 0 connections (Base Pillar)
            ->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(0, 0, 0, 0))
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -2), new Vector3(4, 24, 4))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -2), new Vector3(4, 16, 4))]))
            )

            // 1 connection
            ->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(1, 0, 0, 0)) // North
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -8), new Vector3(4, 24, 10))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -8), new Vector3(4, 16, 10))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(0, 1, 0, 0)) // South
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -2), new Vector3(4, 24, 10))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -2), new Vector3(4, 16, 10))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(0, 0, 1, 0)) // West
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -2), new Vector3(10, 24, 4))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -2), new Vector3(10, 16, 4))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(0, 0, 0, 1)) // East
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -2), new Vector3(10, 24, 4))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -2), new Vector3(10, 16, 4))]))
            )

            // 2 connections (Axis-aligned)
            ->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(1, 1, 0, 0)) // North + South
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -8), new Vector3(4, 24, 16))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -8), new Vector3(4, 16, 16))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(0, 0, 1, 1)) // West + East
                    ->addComponent(new CollisionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -2), new Vector3(16, 24, 4))]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -2), new Vector3(16, 16, 4))]))
            )

            // 2 connections (Corners)
            ->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(1, 0, 1, 0)) // North + West
                    ->addComponent(new CollisionBoxBlockComponent(true, [
                        new BoxCollision(new Vector3(-2, 0, -8), new Vector3(4, 24, 10)),
                        new BoxCollision(new Vector3(2, 0, -2), new Vector3(6, 24, 4))
                    ]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -8), new Vector3(10, 16, 10))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(1, 0, 0, 1)) // North + East
                    ->addComponent(new CollisionBoxBlockComponent(true, [
                        new BoxCollision(new Vector3(-2, 0, -8), new Vector3(4, 24, 10)),
                        new BoxCollision(new Vector3(-8, 0, -2), new Vector3(6, 24, 4))
                    ]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -8), new Vector3(10, 16, 10))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(0, 1, 1, 0)) // South + West
                    ->addComponent(new CollisionBoxBlockComponent(true, [
                        new BoxCollision(new Vector3(-2, 0, -2), new Vector3(4, 24, 10)),
                        new BoxCollision(new Vector3(2, 0, -2), new Vector3(6, 24, 4))
                    ]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -2), new Vector3(10, 16, 10))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(0, 1, 0, 1)) // South + East
                    ->addComponent(new CollisionBoxBlockComponent(true, [
                        new BoxCollision(new Vector3(-2, 0, -2), new Vector3(4, 24, 10)),
                        new BoxCollision(new Vector3(-8, 0, -2), new Vector3(6, 24, 4))
                    ]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -2), new Vector3(10, 16, 10))]))
            )

            // 3 connections
            ->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(1, 1, 1, 0)) // North + South + West
                    ->addComponent(new CollisionBoxBlockComponent(true, [
                        new BoxCollision(new Vector3(-2, 0, -8), new Vector3(4, 24, 16)),
                        new BoxCollision(new Vector3(2, 0, -2), new Vector3(6, 24, 4))
                    ]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-2, 0, -8), new Vector3(10, 16, 16))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(1, 1, 0, 1)) // North + South + East
                    ->addComponent(new CollisionBoxBlockComponent(true, [
                        new BoxCollision(new Vector3(-2, 0, -8), new Vector3(4, 24, 16)),
                        new BoxCollision(new Vector3(-8, 0, -2), new Vector3(6, 24, 4))
                    ]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -8), new Vector3(10, 16, 16))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(1, 0, 1, 1)) // North + West + East
                    ->addComponent(new CollisionBoxBlockComponent(true, [
                        new BoxCollision(new Vector3(-8, 0, -2), new Vector3(16, 24, 4)),
                        new BoxCollision(new Vector3(-2, 0, -8), new Vector3(4, 24, 6))
                    ]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -8), new Vector3(16, 16, 10))]))
            )->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(0, 1, 1, 1)) // South + West + East
                    ->addComponent(new CollisionBoxBlockComponent(true, [
                        new BoxCollision(new Vector3(-8, 0, -2), new Vector3(16, 24, 4)),
                        new BoxCollision(new Vector3(-2, 0, 2), new Vector3(4, 24, 6))
                    ]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -2), new Vector3(16, 16, 10))]))
            )

            // 4 connections
            ->addPermutation(
                Permutation::create()
                    ->setCondition($this->getCondition(1, 1, 1, 1)) // North + South + West + East
                    ->addComponent(new CollisionBoxBlockComponent(true, [
                        new BoxCollision(new Vector3(-2, 0, -8), new Vector3(4, 24, 16)),
                        new BoxCollision(new Vector3(-8, 0, -2), new Vector3(16, 24, 4))
                    ]))
                    ->addComponent(new SelectionBoxBlockComponent(true, [new BoxCollision(new Vector3(-8, 0, -8), new Vector3(16, 16, 16))]))
            );
    }
}