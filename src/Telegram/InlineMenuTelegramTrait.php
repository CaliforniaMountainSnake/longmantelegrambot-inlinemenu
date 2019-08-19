<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Telegram;

use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Update;

/**
 * You must implement only setUpdate(), other abstract methods are implemented by Telegram class by default.
 */
trait InlineMenuTelegramTrait
{
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
     * @param string        $_command The bot's command.
     * @param string        $_text    The text which will be sent to the command.
     * @param CallbackQuery $_callback_query
     *
     * @return mixed
     */
    public function executeCommandFromCallbackquery(string $_command, string $_text, CallbackQuery $_callback_query)
    {
        $updateArr = [
            'update_id' => 0,
            'message' => [
                'message_id' => 0,
                'from' => $_callback_query->getFrom()->getRawData(),
                'date' => \time(),
                'text' => $_text,
            ]
        ];
        if ($_callback_query->getMessage()) {
            $updateArr['message']['chat'] = $_callback_query->getMessage()->getChat()->getRawData();
        }

        $this->setUpdate(new Update($updateArr, $this->getBotUsername()));
        return $this->executeCommand($_command);
    }
}
