<?php

namespace Nexly\Blocks\Components\Types;

/**
 * All vanilla block sound types accepted by the minecraft:sound block component.
 * Values match the sound names used in RP/blocks.json.
 *
 * @since 1.26.50 (minecraft:sound component)
 */
enum BlockSoundType: string
{
    case AMETHYST_BLOCK = "amethyst_block";
    case AMETHYST_CLUSTER = "amethyst_cluster";
    case ANCIENT_DEBRIS = "ancient_debris";
    case ANVIL = "anvil";
    case AZALEA = "azalea";
    case AZALEA_LEAVES = "azalea_leaves";
    case BAMBOO = "bamboo";
    case BAMBOO_SAPLING = "bamboo_sapling";
    case BAMBOO_WOOD = "bamboo_wood";
    case BAMBOO_WOOD_HANGING_SIGN = "bamboo_wood_hanging_sign";
    case BASALT = "basalt";
    case BIG_DRIPLEAF = "big_dripleaf";
    case BONE_BLOCK = "bone_block";
    case CACTUS_FLOWER = "cactus_flower";
    case CALCITE = "calcite";
    case CANDLE = "candle";
    case CAVE_VINES = "cave_vines";
    case CHAIN = "chain";
    case CHERRY_LEAVES = "cherry_leaves";
    case CHERRY_WOOD = "cherry_wood";
    case CHERRY_WOOD_HANGING_SIGN = "cherry_wood_hanging_sign";
    case CHISELED_BOOKSHELF = "chiseled_bookshelf";
    case CINNABAR = "cinnabar";
    case CLOTH = "cloth";
    case COMPARATOR = "comparator";
    case COPPER = "copper";
    case COPPER_BULB = "copper_bulb";
    case COPPER_CHEST = "copper_chest";
    case COPPER_CHEST_OXIDIZED = "copper_chest_oxidized";
    case COPPER_CHEST_WEATHERED = "copper_chest_weathered";
    case COPPER_GOLEM_STATUE = "copper_golem_statue";
    case COPPER_GRATE = "copper_grate";
    case CORAL = "coral";
    case CREAKING_HEART = "creaking_heart";
    case DEADBUSH = "deadbush";
    case DECORATED_POT = "decorated_pot";
    case DEEPSLATE = "deepslate";
    case DEEPSLATE_BRICKS = "deepslate_bricks";
    case DIRT_WITH_ROOTS = "dirt_with_roots";
    case DRIED_GHAST = "dried_ghast";
    case DRIPSTONE_BLOCK = "dripstone_block";
    case EYEBLOSSOM = "eyeblossom";
    case FIREFLY_BUSH = "firefly_bush";
    case FROG_SPAWN = "frog_spawn";
    case FROGLIGHT = "froglight";
    case FUNGUS = "fungus";
    case GLASS = "glass";
    case GLOW_LICHEN = "glow_lichen";
    case GRASS = "grass";
    case GRAVEL = "gravel";
    case HANGING_ROOTS = "hanging_roots";
    case HANGING_SIGN = "hanging_sign";
    case HEAVY_CORE = "heavy_core";
    case HONEY_BLOCK = "honey_block";
    case IRON = "iron";
    case ITEMFRAME = "itemframe";
    case LADDER = "ladder";
    case LANTERN = "lantern";
    case LARGE_AMETHYST_BUD = "large_amethyst_bud";
    case LEAF_LITTER = "leaf_litter";
    case LEVER = "lever";
    case LODESTONE = "lodestone";
    case MANGROVE_ROOTS = "mangrove_roots";
    case MEDIUM_AMETHYST_BUD = "medium_amethyst_bud";
    case METAL = "metal";
    case MOB_SPAWNER = "mob_spawner";
    case MOSS_BLOCK = "moss_block";
    case MOSS_CARPET = "moss_carpet";
    case MUD = "mud";
    case MUD_BRICKS = "mud_bricks";
    case MUDDY_MANGROVE_ROOTS = "muddy_mangrove_roots";
    case NETHER_BRICK = "nether_brick";
    case NETHER_GOLD_ORE = "nether_gold_ore";
    case NETHER_SPROUTS = "nether_sprouts";
    case NETHER_WART = "nether_wart";
    case NETHER_WOOD = "nether_wood";
    case NETHER_WOOD_HANGING_SIGN = "nether_wood_hanging_sign";
    case NETHERITE = "netherite";
    case NETHERRACK = "netherrack";
    case NYLIUM = "nylium";
    case PACKED_MUD = "packed_mud";
    case PALE_HANGING_MOSS = "pale_hanging_moss";
    case PINK_PETALS = "pink_petals";
    case POINTED_DRIPSTONE = "pointed_dripstone";
    case POLISHED_TUFF = "polished_tuff";
    case POPLAR_LEAVES = "poplar_leaves";
    case POTENT_SULFUR = "potent_sulfur";
    case POWDER_SNOW = "powder_snow";
    case RED_SHRUB = "red_shrub";
    case RESIN = "resin";
    case RESIN_BRICK = "resin_brick";
    case ROOTS = "roots";
    case SAND = "sand";
    case SCAFFOLDING = "scaffolding";
    case SCULK = "sculk";
    case SCULK_CATALYST = "sculk_catalyst";
    case SCULK_SENSOR = "sculk_sensor";
    case SCULK_SHRIEKER = "sculk_shrieker";
    case SCULK_VEIN = "sculk_vein";
    case SHELF = "shelf";
    case SHELF_MUSHROOM = "shelf_mushroom";
    case SHROOMLIGHT = "shroomlight";
    case SLIME = "slime";
    case SMALL_AMETHYST_BUD = "small_amethyst_bud";
    case SNOW = "snow";
    case SOUL_SAND = "soul_sand";
    case SOUL_SOIL = "soul_soil";
    case SPONGE = "sponge";
    case SPORE_BLOSSOM = "spore_blossom";
    case STEM = "stem";
    case STONE = "stone";
    case STRAW_BED = "straw_bed";
    case SULFUR = "sulfur";
    case SULFUR_SPIKE = "sulfur_spike";
    case SUSPICIOUS_GRAVEL = "suspicious_gravel";
    case SUSPICIOUS_SAND = "suspicious_sand";
    case SWEET_BERRY_BUSH = "sweet_berry_bush";
    case TERRACOTTA = "terracotta";
    case TRIAL_SPAWNER = "trial_spawner";
    case TUFF = "tuff";
    case TUFF_BRICKS = "tuff_bricks";
    case TURTLE_EGG = "turtle_egg";
    case VAULT = "vault";
    case VINES = "vines";
    case WEB = "web";
    case WEEPING_VINES = "weeping_vines";
    case WET_SPONGE = "wet_sponge";
    case WOOD = "wood";

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
