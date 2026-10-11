<?php

namespace Nexly\Blocks\Components\Types;

enum CullingLayer: string
{
    case UNDEFINED = "minecraft:culling_layer.undefined";
    case LEAVES = "minecraft:culling_layer.leaves";
}
