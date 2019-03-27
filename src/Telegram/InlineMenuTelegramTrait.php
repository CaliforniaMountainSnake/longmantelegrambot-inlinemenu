<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Telegram;

use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * You must implement only setUpdate(), others abstract method are implemented Telegram class by default.
 */
trait InlineMenuTelegramTrait
{
    abstract protected function setUpdate(Update $_new_update): void;

    abstract public function executeCommand(string $command);

    abstract public function getBotUsername();

    abstract public function getCommandsList();

    /**
     * @param string $_command
     * @param CallbackQuery $_callback_query
     *
     * @return mixed
     * @throws TelegramException
     */
    public function executeCommandFromCallbackquery(string $_command, CallbackQuery $_callback_query)
    {
        $updateArr = [
            'update_id' => 0,
            'message' => [
                'message_id' => 0,
                'from' => $_callback_query->getFrom()->getRawData(),
                'date' => \time(),
                'text' => '',
            ]
        ];
        if ($_callback_query->getMessage()) {
            $updateArr['message']['chat'] = $_callback_query->getMessage()->getChat()->getRawData();
        }

        $this->setUpdate(new Update($updateArr, $this->getBotUsername()));
        return $this->executeCommand($_command);
    }
}
