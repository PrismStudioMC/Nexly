<?php

namespace Nexly\Blocks\Components\Types;

enum MaterialType: string
{
    case Air = "air";
    case Dirt = "dirt";
    case Wood = "wood";
    case Metal = "metal";
    case Grate = "grate";
    case Water = "water";
    case Lava = "lava";
    case Leaves = "leaves";
    case Plant = "plant";
    case SolidPlant = "solidPlant"; // Crashes if used
    case Fire = "fire";
    case Glass = "glass";
    case Explosive = "explosive";
    case Ice = "ice"; // Not working properly
    case PowderSnow = "powderSnow"; // Not working properly
    case Cactus = "cactus";
    case Portal = "portal";
    case StoneDecoration = "stoneDecoration";
    case Bubble = "bubble";
    case Barrier = "barrier";
    case DecorationSolid = "decorationSolid";
    case ClientRequestPlaceholder = "clientRequestPlaceholder";
    case StructureVoid = "structureVoid";
    case Solid = "solid";
    case NonSolid = "nonSolid";
    case Any = "any";
}
