<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums\CallbackDataTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\CallbackDataEntity;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\BadCallbackDataFormatException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Exceptions\FullPathWasNotBuiltException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Menu;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Telegram\InlineMenuTelegramTrait;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

abstract class InlineMenuCallbackqueryCommand extends SystemCommand
{
    use InlineMenuSendUtils;

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

    abstract protected function getRootMenu(): Menu;

    /**
     * @return ServerResponse
     * @throws TelegramException
     * @throws FullPathWasNotBuiltException
     */
    public function execute(): ServerResponse
    {
        $callback_query = $this->getCallbackQuery();

        // Detect query type.
        try {
            $query = CallbackDataEntity::fromCallbackQuery($callback_query);
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

            case CallbackDataTypeEnum::COMMAND_BUTTON():
                return $this->processCommandButton($query, $callback_query);

            default:
                return $this->processDefault($query, $callback_query);
        }
    }

    protected function processDefault(CallbackDataEntity $_query, CallbackQuery $_callback_query): ServerResponse
    {
        return $this->answerToast($_callback_query, 'Unknown callback type!');
    }

    /**
     * @param CallbackDataEntity $_query
     * @param CallbackQuery $_callback_query
     * @return ServerResponse
     */
    protected function processToast(CallbackDataEntity $_query, CallbackQuery $_callback_query): ServerResponse
    {
        return $this->answerToast($_callback_query, $_query->getData());
    }

    /**
     * @param CallbackDataEntity $_query
     * @param CallbackQuery $_callback_query
     * @return ServerResponse
     * @throws TelegramException
     */
    protected function processStartCommand(CallbackDataEntity $_query, CallbackQuery $_callback_query): ServerResponse
    {
        $command = $_query->getData();
        $this->telegram->executeCommandFromCallbackquery($command, $_callback_query);
        return $this->answerEmpty($_callback_query);
    }

    /**
     * @param CallbackDataEntity $_query
     * @param CallbackQuery $_callback_query
     * @return ServerResponse
     * @throws FullPathWasNotBuiltException
     * @throws TelegramException
     */
    protected function processMenuSection(CallbackDataEntity $_query, CallbackQuery $_callback_query): ServerResponse
    {
        $rootMenu = $this->getRootMenu();
        $path     = $_query->getData();

        $childMenu = $rootMenu->findMenuByPath($path);
        if ($childMenu === null) {
            return $this->answerToast($_callback_query, 'Undefined menu path "' . $path . '"!');
        }

        $buttons = $childMenu->getInlineKeyboardButtons();
        $this->editMessageKeyboard($_callback_query->getMessage(), new InlineKeyboard(...$buttons));

        return $this->answerEmpty($_callback_query);
    }

    /**
     * @param CallbackDataEntity $_query
     * @param CallbackQuery $_callback_query
     * @return ServerResponse
     */
    protected function processCommandButton(CallbackDataEntity $_query, CallbackQuery $_callback_query): ServerResponse
    {
        $dataCommand = $_query->getExtraDataPart1();
        $dataPayload = $_query->getExtraDataPart2();

        /** @var Command[] $commands */
        $commands = \array_filter($this->telegram->getCommandsList(), function ($command) {
            /** @var Command $command */
            return !$command->isSystemCommand() && $command->isEnabled() && $command instanceof ProcessInlineButtonInterface;
        });

        /** @var ProcessInlineButtonInterface $targetCommand */
        $targetCommand = $this->getCommandWithSomeName($commands, $dataCommand);
        if ($targetCommand === null) {
            return $this->answerToast($_callback_query,
                'Unknown command! Be sure command implements ProcessInlineButtonInterface!');
        }

        $response = $targetCommand::processInlineButton($dataPayload, $this, $_callback_query);
        return $response ?? $this->answerEmpty($_callback_query);
    }


    /**
     * @param Command[] $_commands
     * @param string $_name
     * @return Command|null
     */
    protected function getCommandWithSomeName(array $_commands, string $_name): ?Command
    {
        foreach ($_commands as $command) {
            if ($command->getName() === $_name) {
                return $command;
            }
        }
        return null;
    }
}
