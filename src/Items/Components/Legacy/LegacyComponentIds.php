<?php

namespace Nexly\Items\Components\Legacy;

enum LegacyComponentIds: string
{
    case MAX_DAMAGE = "minecraft:max_damage";
    case HAND_EQUIPPED = "minecraft:hand_equipped";
    case STACKED_BY_DATA = "minecraft:stacked_by_data";
    case FOIL = "minecraft:foil";
    case USE_DURATION = "minecraft:use_duration";
    case MAX_STACK_SIZE = "minecraft:max_stack_size";
    case FOOD = "minecraft:food";
    case SEED = "minecraft:seed";
    case BLOCK_RENDER = "minecraft:block";
    case CAMERA = "minecraft:camera";

    /**
     * Returns the name of the component.
     *
     * @return string The name of the component.
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
