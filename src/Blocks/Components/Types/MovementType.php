<?php

namespace Nexly\Blocks\Components\Types;

enum MovementType: string
{
    case PUSH_PULL = 'push_pull';
    case PUSH = 'push';
    case POPPED = 'popped';
    case IMMOVABLE = 'immovable';
}
