<?php

namespace Nexly\Blocks\Mappers;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\Types\BoxCollision;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\math\Vector3;

class FenceBlockMapper extends BlockMapper
{
    /**
     * @param Builder $builder
     * @param Block $block
     * @param array $states
     * @return Permutation
     */
    public function map(Builder $builder, Block $block, array $states): Permutation
    {
        $n = $states[StateNames::MC_CONNECTION_NORTH];
        $s = $states[StateNames::MC_CONNECTION_SOUTH];
        $w = $states[StateNames::MC_CONNECTION_WEST];
        $e = $states[StateNames::MC_CONNECTION_EAST];

        $minX = $e ? -8 : -2;
        $maxX = $w ? 8 : 2;

        $minZ = $n ? -8 : -2;
        $maxZ = $s ? 8 : 2;

        $selectionBox = new BoxCollision(
            new Vector3($minX, 0, $minZ),
            new Vector3($maxX - $minX, 16, $maxZ - $minZ)
        );

        $collisions = [];

        if ($n || $s) {
            $collisions[] = new BoxCollision(
                new Vector3(-2, 0, $minZ),
                new Vector3(4, 24, $maxZ - $minZ)
            );
        }
        if ($w || $e) {
            $collisions[] = new BoxCollision(
                new Vector3($minX, 0, -2),
                new Vector3($maxX - $minX, 24, 4)
            );
        }

        if (!$n && !$s && !$w && !$e) {
            $collisions[] = new BoxCollision(new Vector3(-2, 0, -2), new Vector3(4, 24, 4));
        }

        $expr = MolangHelper::and(
            MolangHelper::stateEquals(StateNames::MC_CONNECTION_NORTH, $n),
            MolangHelper::stateEquals(StateNames::MC_CONNECTION_SOUTH, $s),
            MolangHelper::stateEquals(StateNames::MC_CONNECTION_WEST, $w),
            MolangHelper::stateEquals(StateNames::MC_CONNECTION_EAST, $e)
        );
        return Permutation::create($expr)
            ->addComponent(new CollisionBoxBlockComponent(true, $collisions))
            ->addComponent(new SelectionBoxBlockComponent(true, [$selectionBox]));
    }
}
