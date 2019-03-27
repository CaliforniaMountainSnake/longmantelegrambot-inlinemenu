<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums\CallbackDataTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums\InlineButtonTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\TooLongCallbackDataParameterException;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Exception\TelegramException;

class InlineButton extends CallbackDataEntity
{
    use InlineButtonHelpers;

    public const CALLBACK_DATA_MAX_LENGTH = 64;

    /**
     * @var string
     */
    protected $text;

    public function __construct(InlineButtonTypeEnum $_type, string $_text, string $_data)
    {
        parent::__construct($_type, $_data);
        $this->text = $_text;
    }

    /**
     * @return array
     * @throws TooLongCallbackDataParameterException
     */
    public function getInlineKeyboardArray(): array
    {
        $params = ['text' => $this->text];
        $key    = null;
        $value  = null;

        switch ((string)$this->type) {
            case CallbackDataTypeEnum::TOAST:
                $key   = 'callback_data';
                $value = CallbackDataTypeEnum::TOAST . self::CALLBACK_DATA_DELIMITER . $this->data;
                $this->checkCallbackDataLength($value);
                break;

            case CallbackDataTypeEnum::START_COMMAND:
                $key   = 'callback_data';
                $value = CallbackDataTypeEnum::START_COMMAND . self::CALLBACK_DATA_DELIMITER . $this->data;
                $this->checkCallbackDataLength($value);
                break;

            case CallbackDataTypeEnum::MENU_SECTION:
                $key   = 'callback_data';
                $value = CallbackDataTypeEnum::MENU_SECTION . self::CALLBACK_DATA_DELIMITER . $this->data;
                $this->checkCallbackDataLength($value);
                break;

            case CallbackDataTypeEnum::COMMAND_BUTTON:
                $key   = 'callback_data';
                $value = CallbackDataTypeEnum::COMMAND_BUTTON . self::CALLBACK_DATA_DELIMITER . $this->data;
                $this->checkCallbackDataLength($value);
                break;

            default:
                $key   = (string)$this->type;
                $value = $this->data;
                break;
        }

        $params[$key] = $value;
        return $params;
    }

    /**
     * @return InlineKeyboardButton
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     */
    public function getInlineKeyboardButton(): InlineKeyboardButton
    {
        return new InlineKeyboardButton($this->getInlineKeyboardArray());
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $_callback_data
     * @throws TooLongCallbackDataParameterException
     */
    private function checkCallbackDataLength(string $_callback_data): void
    {
        $len = \strlen($_callback_data);
        if ($len > self::CALLBACK_DATA_MAX_LENGTH) {
            throw new TooLongCallbackDataParameterException('callback_data parameter has length ' . $len
                . ' of ' . self::CALLBACK_DATA_MAX_LENGTH . ' max allowed: "' . $_callback_data . '"');
        }
    }
}
