<?php

namespace Nexly\Blocks\Permutations\Impl;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\Types\BoxCollision;
use Nexly\Blocks\Permutations\Permutation;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\math\Vector3;

class WallPermutation
{
    private function __construct(
        private Builder $builder
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
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') == 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -4), new Vector3(8, 13, 8))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -4), new Vector3(8, 13, 8))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') == 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -8), new Vector3(8, 13, 12))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -8), new Vector3(8, 13, 12))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') == 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -4), new Vector3(8, 13, 12))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -4), new Vector3(8, 13, 12))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') == 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -4), new Vector3(12, 13, 8))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -4), new Vector3(12, 13, 8))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') != 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-9, 0, -4), new Vector3(13, 13, 8))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-9, 0, -4), new Vector3(13, 13, 8))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') == 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-3, 0, -8), new Vector3(6, 13, 16))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-3, 0, -8), new Vector3(6, 13, 16))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') != 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -3), new Vector3(16, 13, 6))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -3), new Vector3(16, 13, 6))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') == 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -9), new Vector3(16, 13, 13))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -9), new Vector3(16, 13, 13))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') != 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -8), new Vector3(12, 13, 12))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -8), new Vector3(12, 13, 12))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') == 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -4), new Vector3(12, 13, 12))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -4), new Vector3(12, 13, 12))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') != 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -4), new Vector3(12, 13, 12))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -4), new Vector3(12, 13, 12))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') != 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -8), new Vector3(16, 13, 12))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -8), new Vector3(16, 13, 12))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') != 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -4), new Vector3(16, 13, 12))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -4), new Vector3(16, 13, 12))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') == 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -8), new Vector3(12, 13, 16))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-4, 0, -8), new Vector3(12, 13, 16))))
        )->addPermutation(
            Permutation::create()
            ->setCondition("q.block_state('" . StateNames::WALL_CONNECTION_TYPE_NORTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_SOUTH . "') != 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_WEST . "') == 0 && q.block_state('" . StateNames::WALL_CONNECTION_TYPE_EAST . "') != 0")
            ->addComponent(new CollisionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -8), new Vector3(12, 13, 16))))
            ->addComponent(new SelectionBoxBlockComponent(true, new BoxCollision(new Vector3(-8, 0, -8), new Vector3(12, 13, 16))))
        );
    }
}
