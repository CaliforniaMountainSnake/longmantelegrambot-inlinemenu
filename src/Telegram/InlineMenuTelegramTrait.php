<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Telegram;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Utils\InlineMenuLogger;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * You must implement only setUpdate(), other abstract methods are implemented by Telegram class by default.
 */
trait InlineMenuTelegramTrait
{
    use InlineMenuLogger;

    /**
     * @param Update $_new_update
     */
    abstract protected function setUpdate(Update $_new_update): void;

    /**
     * @param string $command
     *
     * @return mixed
     */
    abstract public function executeCommand(string $command);

    /**
     * @return mixed
     */
    abstract public function getBotUsername();

    /**
     * @return mixed
     */
    abstract public function getCommandsList();

    /**
     * @param array $paths
     */
    public function setCommandsPaths(array $paths): void
    {
        $this->commands_paths = [];
        $this->addCommandsPaths($paths);
    }

    /**
     * @param string        $_command The bot command.
     * @param string        $_text    The text which will be sent to the command.
     * @param CallbackQuery $_callback_query
     *
     * @return ServerResponse|null
     * @throws TelegramException
     */
    public function executeCommandFromCallbackquery(
        string $_command,
        string $_text,
        CallbackQuery $_callback_query
    ): ?ServerResponse {
        // In a callback_query "message->from" entity will have the bot itself data.
        // So, we need to copy userId from the "chat" entity.
        return $this->executeCommandWithText($_command, $_text, $_callback_query->getMessage(), true);
    }

    /**
     * @param string  $_command
     * @param string  $_text
     * @param Message $_message
     * @param bool    $_is_copy_user_data_from_chat_object
     *
     * @return ServerResponse|null
     * @throws TelegramException
     */
    public function executeCommandWithText(
        string $_command,
        string $_text,
        Message $_message,
        bool $_is_copy_user_data_from_chat_object = false
    ): ?ServerResponse {
        /** @var Update $update */
        $update = $this->update;
        $rawUpdate = json_decode($update->toJson(), true);
        if (($jsonError = json_last_error()) !== JSON_ERROR_NONE) {
            $this->getInlineMenuLogger()->error('Update json decoding error: "' . $jsonError . '"');
        }

        $rawUpdate['message'] = $_message->getRawData();
        $rawUpdate['message']['text'] = $_text;

        // In a callback_query "message->from" entity will have the bot itself data,
        // so the conversation will be started with the bot itself, if we don't change "message->from" value.
        if ($_is_copy_user_data_from_chat_object) {
            $rawUpdate['message']['from'] = $rawUpdate['message']['chat'];
        }

        return $this->executeCommandWithUpdate($_command, $rawUpdate);
    }

    /**
     * @param string $_command
     * @param array  $_raw_update
     *
     * @return ServerResponse|null Command response or null if this command does not exist.
     * @throws TelegramException
     * @see https://github.com/php-telegram-bot/core/issues/485#issuecomment-298847941
     * @see https://github.com/php-telegram-bot/core/issues/532#issuecomment-446393767
     */
    public function executeCommandWithUpdate(string $_command, array $_raw_update): ?ServerResponse
    {
        $placeholderCommandObject = $this->getCommandObject($_command);
        if ($placeholderCommandObject === null) {
            $this->getInlineMenuLogger()->error('Command "' . $_command . '" does not exist!');
            return null;
        }
        $commandClass = get_class($placeholderCommandObject);

        /** @var Command $commandObject */
        $commandObject = new $commandClass($this, new Update($_raw_update));
        return $commandObject->preExecute();
    }
}
