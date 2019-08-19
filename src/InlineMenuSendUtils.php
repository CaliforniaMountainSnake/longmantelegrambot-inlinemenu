<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu;

use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

trait InlineMenuSendUtils
{
    /**
     * From docs: Text of the notification. If not specified, nothing will be shown to the user, 0-200 characters.
     *
     * @return int
     */
    protected function getToastMaxLength(): int
    {
        return 200;
    }

    /**
     * @param CallbackQuery $_callback_query
     * @param string        $_text
     * @param bool          $_show_alert
     * @param int           $_cache_time
     *
     * @return ServerResponse
     */
    public function answerToast(
        CallbackQuery $_callback_query,
        string $_text,
        bool $_show_alert = false,
        $_cache_time = 5
    ): ServerResponse {
        return Request::answerCallbackQuery([
            'callback_query_id' => $_callback_query->getId(),
            'text' => \mb_substr($_text, 0, $this->getToastMaxLength()),
            'show_alert' => $_show_alert ? 'true' : 'false',
            'cache_time' => $_cache_time,
        ]);
    }

    /**
     * @param CallbackQuery $_callback_query
     *
     * @return ServerResponse
     */
    protected function answerEmpty(CallbackQuery $_callback_query): ServerResponse
    {
        return Request::answerCallbackQuery([
            'callback_query_id' => $_callback_query->getId(),
        ]);
    }

    /**
     * @param Message        $_message
     * @param InlineKeyboard $_keyboard
     *
     * @return ServerResponse
     */
    protected function editMessageKeyboard(Message $_message, InlineKeyboard $_keyboard): ServerResponse
    {
        return Request::editMessageReplyMarkup([
            'chat_id' => $_message->getChat()->getId(),
            'message_id' => $_message->getMessageId(),
            'reply_markup' => $_keyboard,
        ]);
    }

    /**
     * Use this method to delete a message, including service messages, with certain limitations.
     *
     * @param Message $_message
     *
     * @return ServerResponse
     */
    protected function deleteMessage(Message $_message): ServerResponse
    {
        return Request::deleteMessage([
            'chat_id' => $_message->getChat()->getId(),
            'message_id' => $_message->getMessageId(),
        ]);
    }
}
