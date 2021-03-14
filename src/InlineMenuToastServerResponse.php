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
     * @var bool
     */
    protected $isShowAlert;

    /**
     * @var int
     */
    protected $cacheTime;

    /**
     * InlineMenuToastServerResponse constructor.
     *
     * @param array  $data
     * @param string $bot_username
     * @param string $_toast_message
     * @param bool   $_is_show_alert
     * @param int    $_cache_time
     */
    public function __construct(
        array $data,
        string $bot_username,
        string $_toast_message,
        bool $_is_show_alert = false,
        int $_cache_time = 5
    ) {
        $this->toastMsg = $_toast_message;
        $this->isShowAlert = $_is_show_alert;
        $this->cacheTime = $_cache_time;
        parent::__construct($data, $bot_username);
    }

    /**
     * @param ServerResponse $_response
     * @param string         $_toast_message
     * @param bool           $_is_show_alert
     * @param int            $_cache_time
     *
     * @return InlineMenuToastServerResponse
     */
    public static function fromServerResponse(
        ServerResponse $_response,
        string $_toast_message,
        bool $_is_show_alert = false,
        int $_cache_time = 5
    ): self {
        /** @noinspection PhpUndefinedFieldInspection */
        return new self($_response->bot_username, $_response->raw_data, $_toast_message, $_is_show_alert, $_cache_time);
    }

    /**
     * @param string $_toast_message
     * @param bool   $_is_show_alert
     * @param int    $_cache_time
     *
     * @return InlineMenuToastServerResponse
     */
    public static function toast(string $_toast_message, bool $_is_show_alert = false, int $_cache_time = 5): self
    {
        return new self(['ok' => true, 'result' => true], null,
            $_toast_message, $_is_show_alert, $_cache_time);
    }

    /**
     * @return string
     */
    public function getToastMsg(): string
    {
        return $this->toastMsg;
    }

    /**
     * @return bool
     */
    public function isShowAlert(): bool
    {
        return $this->isShowAlert;
    }

    /**
     * @return int
     */
    public function getCacheTime(): int
    {
        return $this->cacheTime;
    }
}
