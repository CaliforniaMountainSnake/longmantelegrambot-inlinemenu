<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums\InlineButtonTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\BadCallbackDataFormatException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\TooLongCallbackDataParameterException;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Exception\TelegramException;

trait InlineButtonHelpers
{
    /**
     * @param string $_text
     * @param string $_message
     * @return InlineKeyboardButton
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     */
    public static function toast(string $_text, string $_message): InlineKeyboardButton
    {
        return (new InlineButton (InlineButtonTypeEnum::TOAST(), $_text, $_message))->getInlineKeyboardButton();
    }

    /**
     * @param string $_text
     * @param string $_command_name
     * @return InlineKeyboardButton
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     */
    public static function startCommand(string $_text, string $_command_name): InlineKeyboardButton
    {
        return (new InlineButton (InlineButtonTypeEnum::START_COMMAND(), $_text,
            $_command_name))->getInlineKeyboardButton();
    }

    /**
     * @param string $_text
     * @param string $_target_command
     * @param string $_data
     * @return InlineKeyboardButton
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     */
    public static function commandButton(string $_text, string $_target_command, string $_data): InlineKeyboardButton
    {
        $data = $_target_command . self::CALLBACK_DATA_DELIMITER . $_data;
        return (new InlineButton (InlineButtonTypeEnum::COMMAND_BUTTON(), $_text, $data))->getInlineKeyboardButton();
    }

    /**
     * @param string $_text
     * @param string $_absolute_path
     * @return InlineKeyboardButton
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     */
    public static function menuSection(string $_text, string $_absolute_path): InlineKeyboardButton
    {
        return (new InlineButton (InlineButtonTypeEnum::MENU_SECTION(), $_text,
            $_absolute_path))->getInlineKeyboardButton();
    }

    /**
     * @param string $_text
     * @param string $_data
     * @return InlineKeyboardButton
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     */
    public static function url(string $_text, string $_data): InlineKeyboardButton
    {
        return (new InlineButton (InlineButtonTypeEnum::URL(), $_text, $_data))->getInlineKeyboardButton();
    }

    /**
     * @param string $_text
     * @param string $_data
     * @return InlineKeyboardButton
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     */
    public static function switchInlineQuery(string $_text, string $_data): InlineKeyboardButton
    {
        return (new InlineButton (InlineButtonTypeEnum::SWITCH_INLINE_QUERY(), $_text,
            $_data))->getInlineKeyboardButton();
    }

    /**
     * @param string $_text
     * @param string $_data
     * @return InlineKeyboardButton
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     */
    public static function switchInlineQueryCurrentChat(string $_text, string $_data): InlineKeyboardButton
    {
        return (new InlineButton (InlineButtonTypeEnum::SWITCH_INLINE_QUERY_CURRENT_CHAT(), $_text,
            $_data))->getInlineKeyboardButton();
    }

    /**
     * @param string $_text
     * @param string $_data
     * @return InlineKeyboardButton
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     */
    public static function callbackGame(string $_text, string $_data): InlineKeyboardButton
    {
        return (new InlineButton (InlineButtonTypeEnum::CALLBACK_GAME(), $_text, $_data))->getInlineKeyboardButton();
    }

    /**
     * @param string $_text
     * @param string $_data
     * @return InlineKeyboardButton
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     */
    public static function pay(string $_text, string $_data): InlineKeyboardButton
    {
        return (new InlineButton (InlineButtonTypeEnum::PAY(), $_text, $_data))->getInlineKeyboardButton();
    }
}
