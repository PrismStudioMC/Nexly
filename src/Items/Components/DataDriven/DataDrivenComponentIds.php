<?php

namespace Nexly\Items\Components\DataDriven;

enum DataDrivenComponentIds: string
{
    case ARMOR = "minecraft:armor";
    case BUNDLE_INTERACTION = "minecraft:bundle_interaction";
    case CHARGEABLE = "minecraft:chargeable";
    case COMPOSTABLE = "minecraft:compostable";
    case COOLDOWN = "minecraft:cooldown";
    case CUSTOM_COMPONENTS = "minecraft:custom_components";
    case DAMAGE_ABSORPTION = "minecraft:damage_absorption";
    case DIGGER = "minecraft:digger";
    case DISPLAY_NAME = "minecraft:display_name";
    case DURABILITY = "minecraft:durability";
    case DURABILITY_SENSOR = "minecraft:durability_sensor";
    case DYEABLE = "minecraft:dyeable";
    case ENCHANTABLE = "minecraft:enchantable";
    case ENTITY_PLACER = "minecraft:entity_placer";
    case FIRE_RESISTANT = "minecraft:fire_resistant";
    case FOOD = "minecraft:food";
    case FUEL = "minecraft:fuel";
    case BLOCK_PLACER = "minecraft:block_placer";
    case PROJECTILE = "minecraft:projectile";
    case RECORD = "minecraft:record";
    case RENDER_OFFSETS = "minecraft:render_offsets";
    case REPAIRABLE = "minecraft:repairable";
    case SHOOTER = "minecraft:shooter";
    case STORAGE = "minecraft:storage_item";
    case STORAGE_WEIGHT_LIMIT = "minecraft:storage_item_weight_limit";
    case STORAGE_WEIGHT_MODIFIER = "minecraft:storage_item_weight_modifier";
    case TAGS = "minecraft:tags";
    case THROWABLE = "minecraft:throwable";
    case USE_MODIFIERS = "minecraft:use_modifiers";
    case WEAPON = "minecraft:weapon";
    case WEARABLE = "minecraft:wearable";
    case PUBLISHER_ON_USE_ON = "minecraft:publisher_on_use_on";
    case SWING_DURATION = "minecraft:swing_duration";
    case SWING_SOUNDS = "minecraft:swing_sounds";
    case PIERCING_WEAPON = "minecraft:piercing_weapon";
    case KINETIC_WEAPON = "minecraft:kinetic_weapon";
    case KINETIC_WEAPON_KINETIC_EFFECT_CONDITIONS = "minecraft:kinetic_weapon_kinetic_effect_conditions";

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
