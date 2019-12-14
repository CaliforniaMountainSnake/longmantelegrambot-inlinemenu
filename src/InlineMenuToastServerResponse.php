<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu;

use Longman\TelegramBot\Entities\ServerResponse;

/**
 * Objects of this class can be used to send signal to CallbackQueryCommand to answer for callback_query with toast,
 * if the command has been started via inline button.
 */
class InlineMenuToastServerResponse extends ServerResponse
{
    /**
     * @var string
     */
    protected $toastMsg;

    /**
     * InlineMenuToastServerResponse constructor.
     *
     * @param array  $data
     * @param        $bot_username
     * @param string $_toast_message
     */
    public function __construct(array $data, $bot_username, string $_toast_message)
    {
        $this->toastMsg = $_toast_message;
        parent::__construct($data, $bot_username);
    }

    /**
     * @param ServerResponse $_response
     * @param string         $_toast_message
     *
     * @return InlineMenuToastServerResponse
     */
    public static function fromServerResponse(ServerResponse $_response, string $_toast_message): self
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return new self($_response->bot_username, $_response->raw_data, $_toast_message);
    }

    /**
     * @param string $_toast_message
     *
     * @return InlineMenuToastServerResponse
     */
    public static function toast(string $_toast_message): self
    {
        return new self(['ok' => true, 'result' => true], null, $_toast_message);
    }

    /**
     * @return string
     */
    public function getToastMsg(): string
    {
        return $this->toastMsg;
    }
}
