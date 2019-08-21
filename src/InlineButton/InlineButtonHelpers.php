<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums\InlineButtonTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\TooLongCallbackDataParameterException;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;

trait InlineButtonHelpers
{
    /**
     * Show a popup message.
     *
     * @param string $_button_text
     * @param string $_message
     *
     * @return InlineKeyboardButton
     * @throws TooLongCallbackDataParameterException
     */
    public static function toast(string $_button_text, string $_message): InlineKeyboardButton
    {
        $inlineButton = new InlineButton (InlineButtonTypeEnum::TOAST(), $_button_text, [$_message]);
        return $inlineButton->getInlineKeyboardButton();
    }

    /**
     * Start any command.
     *
     * @param string      $_button_text    The button text.
     * @param string      $_command_name   The name of the command which will be executed.
     * @param string|null $_command_text   The text which will be sent into the command like an usual user text.
     * @param bool        $_delete_message Delete the message with the inline keyboard after command execution?
     *
     * @return InlineKeyboardButton
     * @throws TooLongCallbackDataParameterException
     */
    public static function startCommand(
        string $_button_text,
        string $_command_name,
        ?string $_command_text = null,
        bool $_delete_message = false
    ): InlineKeyboardButton {
        $data = [
            $_command_name,
            $_command_text ?? '',
            $_delete_message ? CallbackDataEntity::DELETE_MESSAGE_MARKER : ''
        ];

        $inlineButton = new InlineButton (InlineButtonTypeEnum::START_COMMAND(), $_button_text, $data);
        return $inlineButton->getInlineKeyboardButton();
    }

    /**
     * Just a short alias for the startCommand() with command text.
     * (because it works like an usual keyboard button).
     *
     * @param string $_button_text    The button text.
     * @param string $_command_name   The name of the command which will be executed.
     * @param bool   $_delete_message Delete the message with the inline keyboard after command execution?
     *
     * @return InlineKeyboardButton
     * @throws TooLongCallbackDataParameterException
     */
    public static function button(
        string $_button_text,
        string $_command_name,
        bool $_delete_message = false
    ): InlineKeyboardButton {
        return self::startCommand($_button_text, $_command_name, $_button_text, $_delete_message);
    }

    /**
     * Create a fully equivalent of usual keyboard button.
     * (The message with inline keyboard will be deleted after button pressing).
     *
     * @param string $_button_text  The button text.
     * @param string $_command_name The name of the command which will be executed.
     *
     * @return InlineKeyboardButton
     * @throws TooLongCallbackDataParameterException
     */
    public static function buttonDisposable(string $_button_text, string $_command_name): InlineKeyboardButton
    {
        return self::startCommand($_button_text, $_command_name, $_button_text, true);
    }

    /**
     * Create an inline keyboard from the string array.
     *
     * @param string $_command_name   The name of the command which will be executed.
     * @param array  $_buttons        The array with keyboard buttons' string labels.
     *                                The array keys will be sent to the command.
     *                                The array values will used as the visible button's text.
     * @param bool   $_delete_message Delete the message with the inline keyboard after command execution?
     *
     * @return InlineKeyboard
     */
    public static function buttonsArray(
        string $_command_name,
        array $_buttons,
        bool $_delete_message = false
    ): InlineKeyboard {
        \array_walk_recursive($_buttons, static function (&$value, &$key) use ($_command_name, $_delete_message) {
            $visibleText = $value;
            $realText = $key;
            $value = self::startCommand($visibleText, $_command_name, $realText, $_delete_message);
        });

        return new InlineKeyboard(...$_buttons);
    }

    /**
     * @param string $_button_text
     * @param string $_absolute_path
     *
     * @return InlineKeyboardButton
     * @throws TooLongCallbackDataParameterException
     */
    public static function menuSection(string $_button_text, string $_absolute_path): InlineKeyboardButton
    {
        $inlineButton = new InlineButton (InlineButtonTypeEnum::MENU_SECTION(), $_button_text, [$_absolute_path]);
        return $inlineButton->getInlineKeyboardButton();
    }

    /**
     * @param string $_button_text
     * @param string $_url
     *
     * @return InlineKeyboardButton
     * @throws TooLongCallbackDataParameterException
     */
    public static function url(string $_button_text, string $_url): InlineKeyboardButton
    {
        $inlineButton = new InlineButton (InlineButtonTypeEnum::URL(), $_button_text, [$_url]);
        return $inlineButton->getInlineKeyboardButton();
    }

    /**
     * @param string $_button_text
     * @param string $_data
     *
     * @return InlineKeyboardButton
     * @throws TooLongCallbackDataParameterException
     */
    public static function switchInlineQuery(string $_button_text, string $_data): InlineKeyboardButton
    {
        $inlineButton = new InlineButton (InlineButtonTypeEnum::SWITCH_INLINE_QUERY(), $_button_text, [$_data]);
        return $inlineButton->getInlineKeyboardButton();
    }

    /**
     * @param string $_button_text
     * @param string $_data
     *
     * @return InlineKeyboardButton
     * @throws TooLongCallbackDataParameterException
     */
    public static function switchInlineQueryCurrentChat(string $_button_text, string $_data): InlineKeyboardButton
    {
        $inlineButton = new InlineButton (InlineButtonTypeEnum::SWITCH_INLINE_QUERY_CURRENT_CHAT(), $_button_text,
            [$_data]);
        return $inlineButton->getInlineKeyboardButton();
    }

    /**
     * @param string $_button_text
     * @param string $_data
     *
     * @return InlineKeyboardButton
     * @throws TooLongCallbackDataParameterException
     */
    public static function callbackGame(string $_button_text, string $_data): InlineKeyboardButton
    {
        $inlineButton = new InlineButton (InlineButtonTypeEnum::CALLBACK_GAME(), $_button_text, [$_data]);
        return $inlineButton->getInlineKeyboardButton();
    }

    /**
     * @param string $_button_text
     * @param string $_data
     *
     * @return InlineKeyboardButton
     * @throws TooLongCallbackDataParameterException
     */
    public static function pay(string $_button_text, string $_data): InlineKeyboardButton
    {
        $inlineButton = new InlineButton (InlineButtonTypeEnum::PAY(), $_button_text, [$_data]);
        return $inlineButton->getInlineKeyboardButton();
    }
}
