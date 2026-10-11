<?php

namespace Nexly\Blocks\Components;

enum BlockComponentIds: string
{
    case BREATHABILITY = "minecraft:breathability";
    case COLLISION_BOX = "minecraft:collision_box";
    case CONNECTION_RULE = "minecraft:connection_rule";
    case DESTRUCTIBLE_BY_EXPLOSION = "minecraft:destructible_by_explosion";
    case DESTRUCTIBLE_BY_MINING = "minecraft:destructible_by_mining";
    case DISPLAY_NAME = "minecraft:display_name";
    case EMBEDDED_VISUAL = "minecraft:embedded_visual";
    case FLAMMABLE = "minecraft:flammable";
    case FRICTION = "minecraft:friction";
    case GEOMETRY = "minecraft:geometry";
    case ITEM_VISUAL = "minecraft:item_visual";
    case LIGHT_DAMPENING = "minecraft:light_dampening";
    case LIGHT_EMISSION = "minecraft:light_emission";
    case LIQUID_DETECTION = "minecraft:liquid_detection";
    case MATERIAL_INSTANCES = "minecraft:material_instances";
    case ON_INTERACT = "minecraft:on_interact";
    case ON_PLAYER_PLACING = "minecraft:on_player_placing";
    case PLACEMENT_FILTER = "minecraft:placement_filter";
    case REPLACEABLE = "minecraft:replaceable";
    case SELECTION_BOX = "minecraft:selection_box";
    case TRANSFORMATION = "minecraft:transformation";
    case CUSTOM_COMPONENTS = "minecraft:custom_components";
    case FLOWER_POTTABLE = "minecraft:flower_pottable";
    case RANDOM_OFFSET = "minecraft:random_offset";
    case SUPPORT = "minecraft:support";
    case PRECIPITATION_INTERACTIONS = "minecraft:precipitation_interactions";
    case CHEST_OBSTRUCTION = "minecraft:chest_obstruction";
    case SOUND = "minecraft:sound";
    case BLOCK_ENTITY = "minecraft:block_entity";
    case INSTRUMENT_SOUND = "minecraft:instrument_sound";
    case CRAFTING_TABLE = "minecraft:crafting_table";
    case DESTRUCTION_PARTICLES = "minecraft:destruction_particles";
    case LOOT = "minecraft:loot";
    case MAP_COLOR = "minecraft:map_color";
    case MOVABLE = "minecraft:movable";
    case REDSTONE_CONDUCTIVITY = "minecraft:redstone_conductivity";
    case REDSTONE_CONSUMER = "minecraft:redstone_consumer";
    case REDSTONE_PRODUCER = "minecraft:redstone_producer";
    case TICK = "minecraft:tick";
    case ENTITY_FALL_ON = "minecraft:entity_fall_on";
    case LEASHABLE = "minecraft:leashable";

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
