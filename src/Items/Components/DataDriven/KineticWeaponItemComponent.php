<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use Nexly\Items\Components\DataDriven\KineticWeaponKineticEffectConditionsItemComponent as DamageConditions;

/**
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/itemreference/examples/itemcomponents/minecraft_kinetic_weapon?view=minecraft-bedrock-stable
 * @since 1.21.120
 * @internal
 */
#[Attribute(Attribute::TARGET_CLASS)]
class KineticWeaponItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly float $hitboxMargin = 0.0,
        private readonly int $min = 0,
        private readonly int $max = 3,
        private readonly float             $damageModifier = 0.0,
        private readonly float             $damageMultiplier = 1.0,
        private readonly int               $delay = 0,
        private readonly ?DamageConditions $damageConditions = null,
        private readonly ?DamageConditions $dismoutConditions = null,
        private readonly ?DamageConditions $knockbackConditions = null,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::KINETIC_WEAPON->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create();
        if ($this->damageConditions !== null) {
            $nbt = $nbt->setTag("damage_conditions", $this->damageConditions->toNBT());
        }

        if ($this->dismoutConditions !== null) {
            $nbt = $nbt->setTag("dismount_conditions", $this->dismoutConditions->toNBT());
        }

        if ($this->knockbackConditions !== null) {
            $nbt = $nbt->setTag("knockback_conditions", $this->knockbackConditions->toNBT());
        }

        return $nbt
            ->setTag("hitbox_margin", new FloatTag($this->hitboxMargin))
            ->setTag(
                "reach",
                CompoundTag::create()
                ->setTag("min", new IntTag($this->min))
                ->setTag("max", new IntTag($this->max))
            )
            ->setTag("damage_modifier", new FloatTag($this->damageModifier))
            ->setTag("damage_multiplier", new FloatTag($this->damageMultiplier))
            ->setTag("delay", new IntTag($this->delay));
    }
}
