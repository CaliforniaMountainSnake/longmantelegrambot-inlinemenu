<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums\CallbackDataTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\BadCallbackDataFormatException;
use Longman\TelegramBot\Entities\CallbackQuery;

class CallbackDataEntity
{
    public const CALLBACK_DATA_DELIMITER = '|';

    /**
     * @var CallbackDataTypeEnum
     */
    protected $type;

    /**
     * @var string
     */
    protected $data;

    /**
     * CallbackDataEntity constructor.
     *
     * @param CallbackDataTypeEnum $_type
     * @param string $_data
     *
     * @throws BadCallbackDataFormatException
     */
    public function __construct(CallbackDataTypeEnum $_type, string $_data)
    {
        $this->type = $_type;
        $this->data = $_data;

        $commandButtonType = CallbackDataTypeEnum::COMMAND_BUTTON;
        if ((string)$_type === $commandButtonType && self::splitStringByFirstDelimiter($_data) === null) {
            throw new BadCallbackDataFormatException('The data of the callback_data with type "'
                . $commandButtonType . '" contains less than 2 elements!');
        }
    }

    public function __clone()
    {
        $this->type = clone $this->type;
    }

    /**
     * @return CallbackDataTypeEnum
     */
    public function getType(): CallbackDataTypeEnum
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return string|null
     */
    public function getExtraDataPart1(): ?string
    {
        $extra = $this->getExtraData();
        if ($extra === null) {
            return null;
        }

        [$part1, $part2] = $extra;
        return $part1;
    }

    /**
     * @return string|null
     */
    public function getExtraDataPart2(): ?string
    {
        $extra = $this->getExtraData();
        if ($extra === null) {
            return null;
        }

        [$part1, $part2] = $extra;
        return $part2;
    }

    /**
     * @param CallbackQuery $_callback_query
     *
     * @return self
     * @throws BadCallbackDataFormatException
     */
    public static function fromCallbackQuery(CallbackQuery $_callback_query): self
    {
        $split = self::splitStringByFirstDelimiter($_callback_query->getData());
        if ($split === null) {
            throw new BadCallbackDataFormatException('The callback_data contains less than 2 elements!');
        }
        [$typeString, $data] = $split;

        try {
            $type = new CallbackDataTypeEnum($typeString);
        } catch (\UnexpectedValueException $e) {
            throw new BadCallbackDataFormatException('Unknown type in the callback_data! '
                . $e->getMessage(), $e->getCode(), $e);
        }

        return new self($type, $data);
    }

    /**
     * Разделить данные согласно первому встреченному разделителю.
     * Если в данных нет разделителя, вернуть null.
     *
     * @param string $_data
     * @return array|null
     */
    protected static function splitStringByFirstDelimiter(string $_data): ?array
    {
        $exploded = \explode(self::CALLBACK_DATA_DELIMITER, $_data);
        if (\count($exploded) < 2) {
            return null;
        }

        $data1 = $exploded[0];
        unset ($exploded[0]);
        $data2 = \implode(self::CALLBACK_DATA_DELIMITER, $exploded);

        return [$data1, $data2];
    }

    /**
     * @return array|null
     */
    protected function getExtraData(): ?array
    {
        $split = self::splitStringByFirstDelimiter($this->getData());
        if ($split === null) {
            return null;
        }

        return $split;
    }
}
