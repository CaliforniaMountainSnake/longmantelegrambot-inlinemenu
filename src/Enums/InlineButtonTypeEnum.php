<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums;

class InlineButtonTypeEnum extends CallbackDataTypeEnum
{
    public const URL = 'url';
    public const SWITCH_INLINE_QUERY = 'switch_inline_query';
    public const SWITCH_INLINE_QUERY_CURRENT_CHAT = 'switch_inline_query_current_chat';
    public const CALLBACK_GAME = 'callback_game';
    public const PAY = 'pay';


    //--------------------------------------------------------------------------
    // These methods are just for IDE autocomplete and not are mandatory.
    //--------------------------------------------------------------------------
    public static function URL(): self
    {
        return new static (static::URL);
    }

    public static function SWITCH_INLINE_QUERY(): self
    {
        return new static (static::SWITCH_INLINE_QUERY);
    }

    public static function SWITCH_INLINE_QUERY_CURRENT_CHAT(): self
    {
        return new static (static::SWITCH_INLINE_QUERY_CURRENT_CHAT);
    }

    public static function CALLBACK_GAME(): self
    {
        return new static (static::CALLBACK_GAME);
    }

    public static function PAY(): self
    {
        return new static (static::PAY);
    }
}
