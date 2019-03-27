<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums;

use MyCLabs\Enum\Enum;

class CallbackDataTypeEnum extends Enum
{
    public const TOAST          = 't';
    public const START_COMMAND  = 's';
    public const COMMAND_BUTTON = 'c';
    public const MENU_SECTION   = 'm';

    //--------------------------------------------------------------------------
    // These methods are just for IDE autocomplete and not are mandatory.
    //--------------------------------------------------------------------------
    public static function TOAST()
    {
        return new static (static::TOAST);
    }

    public static function START_COMMAND()
    {
        return new static (static::START_COMMAND);
    }

    public static function COMMAND_BUTTON()
    {
        return new static (static::COMMAND_BUTTON);
    }

    public static function MENU_SECTION()
    {
        return new static (static::MENU_SECTION);
    }
}
