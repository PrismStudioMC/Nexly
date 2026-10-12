<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\block\Wool;

class NoteInstrument
{
    public const BANJO = "note.banjo";
    public const BASS = "note.bassattack";
    public const BASS_DRUM = "note.bd";
    public const BELL = "note.bell";
    public const BIT = "note.bit";
    public const CHIME = "note.chime";
    public const COW_BELL = "note.cow_bell";
    public const CREEPER = "note.creeper";
    public const DIDGERIDOO = "note.didgeridoo";
    public const ENDER_DRAGON = "note.enderdragon";
    public const FLUTE = "note.flute";
    public const GUITAR = "note.guitar";
    public const HARP = "note.harp";
    public const HAT = "note.hat";
    public const IRON_XYLOPHONE = "note.iron_xylophone";
    public const NONE = "note.none";
    public const PIGLIN = "note.piglin";
    public const PLING = "note.pling";
    public const SKELETON = "note.skeleton";
    public const SNARE = "note.snare";
    public const TRUMPET = "note.trumpet";
    public const TRUMPET_EXPOSED = "note.trumpet_exposed";
    public const TRUMPET_OXIDIZED = "note.trumpet_oxidized";
    public const TRUMPET_WEATHERED = "note.trumpet_weathered";
    public const WITHER_SKELETON = "note.witherskeleton";
    public const XYLOPHONE = "note.xylophone";
    public const ZOMBIE = "note.zombie";

    /**
     * @param Block $block
     * @return string
     */
    public static function from(Block $block): string
    {
        $breakInfo = $block->getBreakInfo();
        $toolType = $breakInfo->getToolType();

        return match (true) {
            $block instanceof Wool => NoteInstrument::GUITAR,
            $toolType === BlockToolType::AXE => NoteInstrument::BASS,
            $toolType === BlockToolType::PICKAXE => NoteInstrument::BASS_DRUM,
            $toolType === BlockToolType::SHOVEL => NoteInstrument::SNARE,
            $block->isTransparent() && $breakInfo->getHardness() <= 0.5 => NoteInstrument::HAT,
            default => NoteInstrument::HARP
        };
    }
}
