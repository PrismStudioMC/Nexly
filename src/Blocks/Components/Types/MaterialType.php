<?php

namespace Nexly\Blocks\Components\Types;

enum MaterialType : string
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

// enum class MaterialType : uint {
//    Air                      = 0,
//    Dirt                     = 1,
//    Wood                     = 2,
//    Metal                    = 3,
//    Grate                    = 4,
//    Water                    = 5,
//    Lava                     = 6,
//    Leaves                   = 7,
//    Plant                    = 8,
//    SolidPlant               = 9,
//    Fire                     = 10,
//    Glass                    = 11,
//    Explosive                = 12,
//    Ice                      = 13,
//    PowderSnow               = 14,
//    Cactus                   = 15,
//    Portal                   = 16,
//    StoneDecoration          = 17,
//    Bubble                   = 18,
//    Barrier                  = 19,
//    DecorationSolid          = 20,
//    ClientRequestPlaceholder = 21,
//    StructureVoid            = 22,
//    Solid                    = 23,
//    NonSolid                 = 24,
//    Any                      = 25,
//};