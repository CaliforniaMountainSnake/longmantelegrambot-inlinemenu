<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Telegram;

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;

class InlineMenuTelegram extends Telegram
{
    use InlineMenuTelegramTrait;

    protected function setUpdate(Update $_new_update): void
    {
        $this->update = $_new_update;
    }
}
