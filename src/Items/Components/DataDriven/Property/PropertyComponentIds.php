<?php

namespace Nexly\Items\Components\DataDriven\Property;

enum PropertyComponentIds: string
{
    case ALLOW_OFF_HAND = "allow_offhand";
    case CAN_DESTROY_IN_CREATIVE = "can_destroy_in_creative";
    case DAMAGE = "damage";
    case ENCHANTABLE_VALUE = "enchantable_value";
    case ENCHANTABLE_SLOT = "enchantable_slot";
    case FOIL = "foil";
    case FRAME_COUNT = "frame_count";
    case HAND_EQUIPPED = "hand_equipped";
    case IS_HIDDEN_IN_COMMANDS = "is_hidden_in_commands";
    case LIQUID_CLIPPED = "liquid_clipped";
    case MAX_STACk_SIZE = "max_stack_size";
    case MINING_SPEED = "mining_speed";
    case SHOULD_DESPAWN = "should_despawn";
    case STACKED_BY_DATA = "stacked_by_data";
    case USE_ANIMATION = "use_animation";
    case USE_DURATION = "use_duration";
    case BLOCK = "block";
    case HOVER_TEXT_COLOR = "hover_text_color";
    case ICON = "minecraft:icon";
    case GLINT = "glint";
    case INTERACT_BUTTON = "interact_button";
    case ON_USE = "on_use";
    case ON_USE_ON = "on_use_on"; // TODO: Investigate
    case RARITY = "rarity";

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
