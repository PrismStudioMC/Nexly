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

class GlassPaneBlockMapper extends BlockMapper
{
    public function map(Builder $builder, Block $block, array $states): Permutation
    {
        $n = $states[StateNames::MC_CONNECTION_NORTH];
        $s = $states[StateNames::MC_CONNECTION_SOUTH];
        $w = $states[StateNames::MC_CONNECTION_WEST];
        $e = $states[StateNames::MC_CONNECTION_EAST];

        $expr = MolangHelper::and(
            MolangHelper::stateEquals(StateNames::MC_CONNECTION_NORTH, $n),
            MolangHelper::stateEquals(StateNames::MC_CONNECTION_SOUTH, $s),
            MolangHelper::stateEquals(StateNames::MC_CONNECTION_WEST, $w),
            MolangHelper::stateEquals(StateNames::MC_CONNECTION_EAST, $e)
        );

        $minX = $w ? -8 : -1;
        $maxX = $e ? 8 : 1;
        $minZ = $n ? -8 : -1;
        $maxZ = $s ? 8 : 1;

        $boxes = [];

        if ($n || $s) {
            $boxes[] = new BoxCollision(new Vector3(-1, 0, $minZ), new Vector3(2, 16, $maxZ - $minZ));
        }
        if ($w || $e) {
            $boxes[] = new BoxCollision(new Vector3($minX, 0, -1), new Vector3($maxX - $minX, 16, 2));
        }

        if (!$n && !$s && !$w && !$e) {
            $boxes[] = new BoxCollision(new Vector3(-1, 0, -1), new Vector3(2, 16, 2));
        }

        return Permutation::create($expr)
            ->addComponent(new CollisionBoxBlockComponent(true, $boxes))
            ->addComponent(new SelectionBoxBlockComponent(true, $boxes));
    }
}
