<?php

namespace Nexly\Blocks\Components\Types;

enum MinecraftGeometry: string
{
    case FULL_BLOCK = "minecraft:geometry.full_block";
    case CROSS = "minecraft:geometry.cross";
    case BEACON = "minecraft:geometry.beacon";
    case BIG_DRIPLEAF = "minecraft:geometry.big_dripleaf";
    case BIG_DRIPLEAF_FULL_BIT = "minecraft:geometry.big_dripleaf_full_bit";
    case BIG_DRIPLEAF_PARTIAL_BIT = "minecraft:geometry.big_dripleaf_partial_bit";
    case BIG_DRIPLEAF_STEM = "minecraft:geometry.big_dripleaf_stem";
    case BUTTON = "minecraft:geometry.button";
    case BUTTON_PRESSED = "minecraft:geometry.button_pressed";
    case CUBE_COLUMN_HORIZONTAL = "minecraft:geometry.cube_column_horizontal";
    case DOOR_BOTTOM_LEFT = "minecraft:geometry.door_bottom_left";
    case DOOR_BOTTOM_LEFT_OPEN = "minecraft:geometry.door_bottom_left_open";
    case DOOR_BOTTOM_RIGHT = "minecraft:geometry.door_bottom_right";
    case DOOR_BOTTOM_RIGHT_OPEN = "minecraft:geometry.door_bottom_right_open";
    case DOOR_TOP_LEFT = "minecraft:geometry.door_top_left";
    case DOOR_TOP_LEFT_OPEN = "minecraft:geometry.door_top_left_open";
    case DOOR_TOP_RIGHT = "minecraft:geometry.door_top_right";
    case DOOR_TOP_RIGHT_OPEN = "minecraft:geometry.door_top_right_open";
    case EMPTY = "minecraft:geometry.empty";
    case ENCHANTING_TABLE = "minecraft:geometry.enchanting_table";
    case FENCE_POST = "minecraft:geometry.fence_post";
    case HOPPER = "minecraft:geometry.hopper";
    case HOPPER_SIDE = "minecraft:geometry.hopper_side";
    case LEAVES = "minecraft:geometry.leaves";
    case LECTERN = "minecraft:geometry.lectern";
    case PRESSURE_PLATE_DOWN = "minecraft:geometry.pressure_plate_down";
    case PRESSURE_PLATE_UP = "minecraft:geometry.pressure_plate_up";
    case SLAB = "minecraft:geometry.slab";
    case SLAB_TOP = "minecraft:geometry.slab_top";
    case STAIRS = "minecraft:geometry.stairs";
    case TEMPLATE_FENCE_GATE = "minecraft:geometry.template_fence_gate";
    case TEMPLATE_FENCE_GATE_OPEN = "minecraft:geometry.template_fence_gate_open";
    case TEMPLATE_FENCE_GATE_WALL = "minecraft:geometry.template_fence_gate_wall";
    case TEMPLATE_FENCE_GATE_WALL_OPEN = "minecraft:geometry.template_fence_gate_wall_open";
    case TEMPLATE_TRAPDOOR_BOTTOM = "minecraft:geometry.template_trapdoor_bottom";
    case TEMPLATE_TRAPDOOR_OPEN = "minecraft:geometry.template_trapdoor_open";
    case TEMPLATE_TRAPDOOR_TOP = "minecraft:geometry.template_trapdoor_top";

    /**
     * Returns the enum value as a string.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }
}
