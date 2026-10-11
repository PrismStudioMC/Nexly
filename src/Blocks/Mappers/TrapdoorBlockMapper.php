<?php

namespace Nexly\Blocks\Mappers;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\TransformationBlockComponent;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\math\Vector3;
use RuntimeException;

class TrapdoorBlockMapper extends BlockMapper
{
    /**
     * @param Builder $builder
     * @param Block $block
     * @param array $states
     * @return Permutation
     */
    public function map(Builder $builder, Block $block, array $states): Permutation
    {
        $dir = $states[StateNames::DIRECTION];
        $top = $states[StateNames::UPSIDE_DOWN_BIT];
        $open = $states[StateNames::OPEN_BIT];

        $expr = MolangHelper::and(
            MolangHelper::stateEquals(StateNames::DIRECTION, $dir),
            MolangHelper::stateEquals(StateNames::OPEN_BIT, $open),
            MolangHelper::stateEquals(StateNames::UPSIDE_DOWN_BIT, $top)
        );
        return Permutation::create($expr)
            ->addComponent(new TransformationBlockComponent(rotation: match (true) {
                !$top && !$open && $dir == 3 => new Vector3(0, 0, 0),      // North
                !$top && !$open && $dir == 2 => new Vector3(0, 180, 0),    // South
                !$top && !$open && $dir == 1 => new Vector3(0, 90, 0),     // West
                !$top && !$open && $dir == 0 => new Vector3(0, -90, 0),    // East

                !$top && $open && $dir == 3 => new Vector3(90, 180, 0),    // North
                !$top && $open && $dir == 2 => new Vector3(90, 0, 0),      // South
                !$top && $open && $dir == 1 => new Vector3(0, -90, 90),    // West
                !$top && $open && $dir == 0 => new Vector3(180, 90, 90),   // East

                $top && !$open && $dir == 3 => new Vector3(0, 0, 180),     // North
                $top && !$open && $dir == 2 => new Vector3(180, 0, 0),     // South
                $top && !$open && $dir == 1 => new Vector3(180, -90, 0),   // West
                $top && !$open && $dir == 0 => new Vector3(180, 90, 0),    // East

                $top && $open && $dir == 3 => new Vector3(90, 180, 0),     // North
                $top && $open && $dir == 2 => new Vector3(90, 0, 180),     // South
                $top && $open && $dir == 1 => new Vector3(0, 90, 90),      // West
                $top && $open && $dir == 0 => new Vector3(180, -90, 90),   // East

                default => throw new RuntimeException("Invalid direction state")
            }));
    }
}
