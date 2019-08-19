<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums\CallbackDataTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums\InlineButtonTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\TooLongCallbackDataParameterException;
use Longman\TelegramBot\Entities\InlineKeyboardButton;

class InlineButton extends CallbackDataEntity
{
    use InlineButtonHelpers;

    public const CALLBACK_DATA_MAX_LENGTH = 64;

    /**
     * @var string
     */
    protected $buttonText;

    /**
     * InlineButton constructor.
     *
     * @param InlineButtonTypeEnum $_type
     * @param string               $_button_text
     * @param array                $_data
     */
    public function __construct(InlineButtonTypeEnum $_type, string $_button_text, array $_data)
    {
        parent::__construct($_type, $_data);
        $this->buttonText = $_button_text;
    }

    /**
     * @return array
     * @throws TooLongCallbackDataParameterException
     */
    public function getInlineKeyboardArray(): array
    {
        $params = ['text' => $this->buttonText];
        $key = null;
        $value = null;

        switch ((string)$this->type) {
            case CallbackDataTypeEnum::TOAST:
            case CallbackDataTypeEnum::START_COMMAND:
            case CallbackDataTypeEnum::MENU_SECTION:
                $key = 'callback_data';
                $value = $this->dataToString([(string)$this->type]);
                $this->assertCallbackDataHaveGoodLength($value);
                break;

            default:
                $key = (string)$this->type;
                $value = $this->data[0];
                break;
        }

        $params[$key] = $value;
        return $params;
    }

    /**
     * @return InlineKeyboardButton
     * @throws TooLongCallbackDataParameterException
     */
    public function getInlineKeyboardButton(): InlineKeyboardButton
    {
        return new InlineKeyboardButton($this->getInlineKeyboardArray());
    }

    /**
     * @return string
     */
    public function getButtonText(): string
    {
        return $this->buttonText;
    }

    /**
     * @param string $_callback_data
     *
     * @throws TooLongCallbackDataParameterException
     */
    private function assertCallbackDataHaveGoodLength(string $_callback_data): void
    {
        $len = \strlen($_callback_data);
        if ($len > self::CALLBACK_DATA_MAX_LENGTH) {
            throw new TooLongCallbackDataParameterException('callback_data parameter has length ' . $len
                . ' of ' . self::CALLBACK_DATA_MAX_LENGTH . ' max allowed: "' . $_callback_data . '"');
        }
    }
}
