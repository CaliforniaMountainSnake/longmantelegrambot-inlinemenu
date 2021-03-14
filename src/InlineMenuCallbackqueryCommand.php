<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums\CallbackDataTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\CallbackDataEntity;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\BadCallbackDataFormatException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Exceptions\FullPathWasNotBuiltException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Menu;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Telegram\InlineMenuTelegramTrait;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Utils\InlineMenuLogger;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

abstract class InlineMenuCallbackqueryCommand extends SystemCommand
{
    use InlineMenuSendUtils;
    use InlineMenuLogger;

    /**
     * @var InlineMenuTelegramTrait
     */
    protected $telegram;

    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

    /**
     * @return Menu
     */
    abstract protected function getRootMenu(): Menu;

    /**
     * @return ServerResponse
     * @throws FullPathWasNotBuiltException
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $callback_query = $this->getCallbackQuery();
        $this->getInlineMenuLogger()
            ->debug('Incoming CallbackQuery. Raw object', $callback_query->getRawData());
        $this->getInlineMenuLogger()
            ->info('Incoming CallbackQuery. Raw data: "' . $callback_query->getData() . '"');

        // Detect query type.
        try {
            $query = CallbackDataEntity::createFromCallbackQuery($callback_query);
            $this->getInlineMenuLogger()->debug(CallbackDataEntity::class, $query->toArray());
        } catch (BadCallbackDataFormatException $e) {
            return $this->answerToast($callback_query, $e->getMessage());
        }

        // Perform actions depending on the type.
        switch ($query->getType()) {
            case CallbackDataTypeEnum::TOAST():
                return $this->processToast($query, $callback_query);

            case CallbackDataTypeEnum::START_COMMAND():
                return $this->processStartCommand($query, $callback_query);

            case CallbackDataTypeEnum::MENU_SECTION():
                return $this->processMenuSection($query, $callback_query);

            default:
                return $this->processDefault($query, $callback_query);
        }
    }

    /**
     * @param CallbackDataEntity $_query
     * @param CallbackQuery      $_callback_query
     *
     * @return ServerResponse
     */
    protected function processDefault(CallbackDataEntity $_query, CallbackQuery $_callback_query): ServerResponse
    {
        $msg = 'Unknown callback type: "' . $_query->getType() . '"!';
        $this->getInlineMenuLogger()->warning($msg);
        return $this->answerToast($_callback_query, $msg);
    }

    /**
     * @param CallbackDataEntity $_query
     * @param CallbackQuery      $_callback_query
     *
     * @return ServerResponse
     */
    protected function processToast(CallbackDataEntity $_query, CallbackQuery $_callback_query): ServerResponse
    {
        $text = $_query->getData()[0];
        return $this->answerToast($_callback_query, $text);
    }

    /**
     * @param CallbackDataEntity $_query
     * @param CallbackQuery      $_callback_query
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    protected function processStartCommand(
        CallbackDataEntity $_query,
        CallbackQuery $_callback_query
    ): ServerResponse {
        // Get data.
        [$command, $commandText, $isDeleteMessage] = $_query->getData();
        $isDeleteMessage = $isDeleteMessage === CallbackDataEntity::DELETE_MESSAGE_MARKER;

        // Delete message if need.
        $isDeleteMessage && $this->deleteMessage($_callback_query->getMessage());

        // Execute command.
        $resultObject = $this->telegram->executeCommandFromCallbackquery($command, $commandText, $_callback_query);
        if ($resultObject instanceof InlineMenuToastServerResponse) {
            return $this->answerToast($_callback_query, $resultObject->getToastMsg(), $resultObject->isShowAlert(),
                $resultObject->getCacheTime());
        }

        return $this->answerEmpty($_callback_query);
    }

    /**
     * @param CallbackDataEntity $_query
     * @param CallbackQuery      $_callback_query
     *
     * @return ServerResponse
     * @throws FullPathWasNotBuiltException
     */
    protected function processMenuSection(CallbackDataEntity $_query, CallbackQuery $_callback_query): ServerResponse
    {
        $rootMenu = $this->getRootMenu();
        $path = $_query->getData()[0];

        $childMenu = $rootMenu->findMenuByPath($path);
        if ($childMenu === null) {
            $msg = 'Undefined menu path: "' . $path . '"!';
            $this->getInlineMenuLogger()->error($msg);
            return $this->answerToast($_callback_query, $msg);
        }

        $buttons = $childMenu->getInlineKeyboardButtons();
        $this->editMessageKeyboard($_callback_query->getMessage(), new InlineKeyboard(...$buttons));

        return $this->answerEmpty($_callback_query);
    }
}
