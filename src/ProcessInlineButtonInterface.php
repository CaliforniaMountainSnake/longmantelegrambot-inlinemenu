<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu;

use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;

/**
 * Интерфейс, который должны реализовывать все классы команд бота,
 * чтобы иметь возможность принимать данные от inline-кнопок с помощью системы.
 */
interface ProcessInlineButtonInterface
{
    public static function processInlineButton(
        string $_data,
        InlineMenuCallbackqueryCommand $_command,
        CallbackQuery $_callback_query
    ): ?ServerResponse;
}
