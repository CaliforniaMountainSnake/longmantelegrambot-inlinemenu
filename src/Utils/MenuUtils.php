<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Utils;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Exceptions\FullPathWasNotBuiltException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Menu;
use CaliforniaMountainSnake\LongmanTelegrambotUtils\SendUtils;
use CaliforniaMountainSnake\SocialNetworksAPI\Telegram\ParseModeEnum;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

trait MenuUtils
{
    use SendUtils;

    /**
     * Получить объект меню.
     * @return Menu
     */
    abstract protected function getMenu(): Menu;

    /**
     * Получить объект conversation.
     * @return Conversation|null
     */
    abstract protected function getConversation(): ?Conversation;

    /**
     * Отправить текстовое сообщение и показать меню.
     *
     * @param string $_text
     * @param string|null $_chat_id
     * @param ParseModeEnum|null $_parse_mode
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    protected function sendTextMessageAndShowMenu(
        string $_text,
        ?string $_chat_id = null,
        ?ParseModeEnum $_parse_mode = null
    ): ServerResponse {
        return $this->sendTextMessage($_text, null, $this->getMenuInlineKeyboard(), $_chat_id, $_parse_mode);
    }

    /**
     * Завершить conversation и отправить сообщение.
     *
     * @param string $_text
     * @param string|null $_chat_id
     * @param ParseModeEnum|null $_parse_mode
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    protected function sendFatalError(
        string $_text,
        ?string $_chat_id = null,
        ?ParseModeEnum $_parse_mode = null
    ): ServerResponse {
        $conversation = $this->getConversation();
        if ($conversation !== null) {
            $conversation->stop();
        }
        return $this->sendTextMessageAndShowMenu($_text, $_chat_id, $_parse_mode);
    }

    /**
     * Получить объект клавиатуры с меню.
     *
     * @return InlineKeyboard
     * @throws TelegramException
     */
    protected function getMenuInlineKeyboard(): InlineKeyboard
    {
        try {
            return $this->getMenu()->getInlineKeyboard();
        } catch (FullPathWasNotBuiltException $e) {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
