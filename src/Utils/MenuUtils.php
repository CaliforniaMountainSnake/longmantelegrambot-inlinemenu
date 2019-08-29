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
     * Get menu object.
     *
     * @return Menu
     */
    abstract protected function getMenu(): Menu;

    /**
     * Get conversation object.
     *
     * @return Conversation|null
     */
    abstract protected function getConversation(): ?Conversation;

    /**
     * Send text message and show the menu.
     *
     * @param string             $_text
     * @param array|null         $_errors
     * @param string|null        $_chat_id
     * @param ParseModeEnum|null $_parse_mode
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    protected function sendTextMessageAndShowMenu(
        string $_text,
        ?array $_errors = null,
        ?string $_chat_id = null,
        ?ParseModeEnum $_parse_mode = null
    ): ServerResponse {
        return $this->sendTextMessage($_text, $_errors, $this->getMenuInlineKeyboard(), $_chat_id, $_parse_mode);
    }

    /**
     * Stop active conversation and send message.
     *
     * @param string             $_text
     * @param array|null         $_errors
     * @param string|null        $_chat_id
     * @param ParseModeEnum|null $_parse_mode
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    protected function sendFatalError(
        string $_text,
        ?array $_errors = null,
        ?string $_chat_id = null,
        ?ParseModeEnum $_parse_mode = null
    ): ServerResponse {
        $conversation = $this->getConversation();
        if ($conversation !== null) {
            $conversation->stop();
        }
        return $this->sendTextMessageAndShowMenu($_text, $_errors, $_chat_id, $_parse_mode);
    }

    /**
     * Get the object with the inline keyboard.
     *
     * @return InlineKeyboard
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
