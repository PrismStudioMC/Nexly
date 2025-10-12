<?php

namespace Nexly\Recipes;

enum MinecraftShape: string
{
    case ONE = "A";
    case FULL_BLOCK = "AAA\nAAA\nAAA";

    case HELMET = "AAA\nA A";
    case CHESTPLATE = "A A\nAAA\nAAA";
    case LEGGINGS = "AAA\nA A\nA A";
    case BOOTS = "A A\nA A";
    case SWORD = "A\nA\nB";
    case SHOVEL = "A\nB\nB";
    case PICKAXE = "AAA\n B \n B ";
    case AXE = "AA \nAB \n B ";
    case HOE = "AA \n B \n B ";
    case BOW = " A B\nA B \n A B";
    case CROSSBOW = " A B\nABA\n A B";
    case FISHING_ROD = "  A\n B \nB  ";
    case LEAD = "A A\nABA\n B ";
    case SHEARS = " A \nA A";
    case FLINT_AND_STEEL = " A \n  B\nB  ";
    case STICK = "A\nA";

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return explode("\n", $this->value);
    }
}
