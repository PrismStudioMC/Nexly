<?php

namespace Nexly\Blocks\Components\Types;

enum NoteInstrument: string
{
    case HARP = "note.harp";
    case BD = "note.bd";
    case SNARE = "note.snare";
    case HAT = "note.hat";
    case BASS_ATTACK = "note.bassattack";
    case FLUTE = "note.flute";
    case BELL = "note.bell";
    case GUITAR = "note.guitar";
    case CHIME = "note.chime";
    case XYLOPHONE = "note.xylophone";
    case IRON_XYLOPHONE = "note.iron_xylophone";
    case COW_BELL = "note.cow_bell";
    case DIDGERIDOO = "note.didgeridoo";
    case BIT = "note.bit";
    case BANJO = "note.banjo";
    case PLING = "note.pling";
    case TRUMPET = "note.trumpet";
    case TRUMPET_EXPOSED = "note.trumpet_exposed";
    case TRUMPET_WEATHERED = "note.trumpet_weathered";
    case TRUMPET_OXIDIZED = "note.trumpet_oxidized";
    case ZOMBIE = "note.zombie";
    case SKELETON = "note.skeleton";
    case CREEPER = "note.creeper";
    case ENDERDRAGON = "note.enderdragon";
    case WITHERSKELETON = "note.witherskeleton";
    case PIGLIN = "note.piglin";
    case NONE = "note.none";

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
