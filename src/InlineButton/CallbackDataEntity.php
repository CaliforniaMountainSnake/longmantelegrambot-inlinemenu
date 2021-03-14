<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums\CallbackDataTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\BadCallbackDataFormatException;
use Longman\TelegramBot\Entities\CallbackQuery;

class CallbackDataEntity
{
    public const CALLBACK_DATA_DELIMITER = '|';
    public const DELETE_MESSAGE_MARKER = 'd';

    /**
     * @var CallbackDataTypeEnum
     */
    protected $type;

    /**
     * @var array
     */
    protected $data;

    /**
     * CallbackDataEntity constructor.
     *
     * @param CallbackDataTypeEnum $_type
     * @param array                $_data
     */
    public function __construct(CallbackDataTypeEnum $_type, array $_data)
    {
        $this->type = $_type;
        $this->data = $_data;
    }

    public function __clone()
    {
        $this->type = clone $this->type;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => (string)$this->type,
            'data' => $this->data,
        ];
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->toArray();
    }

    /**
     * @param CallbackQuery $_callback_query
     *
     * @return self
     * @throws BadCallbackDataFormatException
     */
    public static function createFromCallbackQuery(CallbackQuery $_callback_query): self
    {
        self::assertCallbackDataHaveGoodFormat($_callback_query);
        [$typeString, $data] = self::parseTypeAndDataFromRawCallbackData($_callback_query);

        try {
            $type = new CallbackDataTypeEnum($typeString);
        } catch (\UnexpectedValueException $e) {
            $msg = 'Unknown type in the callback_data "' . $typeString . '"! ' . $e->getMessage();
            throw new BadCallbackDataFormatException($msg, $e->getCode(), $e);
        }

        return new self($type, $data);
    }

    /**
     * @param string $_callback_data
     *
     * @return array
     */
    protected static function parseDataFromString(string $_callback_data): array
    {
        return \explode(self::CALLBACK_DATA_DELIMITER, $_callback_data);
    }

    /**
     * @param array $_additional_data
     *
     * @return string
     */
    protected function dataToString(array $_additional_data = []): string
    {
        $merged = \array_merge($_additional_data, $this->data);
        return \implode(self::CALLBACK_DATA_DELIMITER, $merged);
    }

    /**
     * @param CallbackQuery $_callback_query
     *
     * @return string[]
     */
    private static function parseTypeAndDataFromRawCallbackData(CallbackQuery $_callback_query): array
    {
        $exploded = self::parseDataFromString($_callback_query->getData());
        $typeString = $exploded[0];
        $data = $exploded;
        unset ($data[0]);
        $data = \array_values($data);

        return [$typeString, $data];
    }

    /**
     * @param CallbackQuery $_callback_query
     *
     * @throws BadCallbackDataFormatException
     */
    private static function assertCallbackDataHaveGoodFormat(CallbackQuery $_callback_query): void
    {
        $exploded = self::parseDataFromString($_callback_query->getData());
        if (\count($exploded) < 2) {
            throw new BadCallbackDataFormatException('The callback_data contains less than 2 elements!');
        }
    }

    //------------------------------------------------------------------------------------------------------------------
    // Getters.
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return CallbackDataTypeEnum
     */
    public function getType(): CallbackDataTypeEnum
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
