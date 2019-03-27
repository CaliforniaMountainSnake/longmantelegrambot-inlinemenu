<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Enums\InlineButtonTypeEnum;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\BadCallbackDataFormatException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\InlineButton;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Exceptions\FullPathWasAlreadyBuiltException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Exceptions\FullPathWasNotBuiltException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Exceptions\UnknownMenuItemException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Utils\PathUtils;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Exception\TelegramException;

class Menu
{
    use PathUtils;

    public const PATH_DELIMITER = '/';

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $id;

    /**
     * Full path with all parents.
     * @var string
     */
    protected $fullPath;

    /**
     * @var array
     */
    protected $items;

    /**
     * MenuSection constructor.
     *
     * @param string $_text InlineButton text of the menu section.
     * @param string $_id Menu section path identifier.
     * @param array $_items Multidimensional array contains InlineKeyboardButton or Menu objects.
     *
     * @throws UnknownMenuItemException
     */
    public function __construct(string $_text, string $_id, array $_items)
    {
        $this->text  = $_text;
        $this->id    = $_id;
        $this->items = $_items;
        $this->checkItemsTypes();
    }

    public function __clone()
    {
        // Clone items' objects.
        $this->deepWalkThroughChildrenItems(function (&$child, Menu &$parent) {
            if (\is_object($child)) {
                $child = clone $child;
            }
        });
    }

    /**
     * Make path string using current PATH_DELIMITER.
     * @param string ...$_ids
     * @return string
     */
    public static function path(string ...$_ids): string
    {
        return \implode(self::PATH_DELIMITER, $_ids);
    }

    /**
     * Получить массив стандартных кнопок клавиатуры библиотеки.
     *
     * @noinspection PhpDocRedundantThrowsInspection
     * @return array
     * @throws FullPathWasNotBuiltException
     */
    public function getInlineKeyboardButtons(): array
    {
        $menu = clone $this;
        \array_walk_recursive($menu->items, function (&$child) {
            if (!($child instanceof self)) {
                return;
            }

            $child = $child->getMenuSectionButton()->getInlineKeyboardButton();
        });

        return $menu->items;
    }

    /**
     * @return InlineKeyboard
     * @throws FullPathWasNotBuiltException
     * @throws TelegramException
     */
    public function getInlineKeyboard(): InlineKeyboard
    {
        return new InlineKeyboard(...$this->getInlineKeyboardButtons());
    }


    /**
     * Проложить у дочерних меню абсолютные пути, используя это в качестве корневого.
     * @throws FullPathWasAlreadyBuiltException
     */
    public function buildPathsFromThisRoot(): void
    {
        if (!empty($this->fullPath)) {
            throw new FullPathWasAlreadyBuiltException('FullPath was already built!');
        }

        $this->fullPath = $this->id;
        $this->deepWalkThroughChildrenItems(function (&$child, Menu &$parent) {
            if (!($child instanceof Menu)) {
                return;
            }
            $child->injectParentPath($parent);
        });
    }

    /**
     * @param string $_path
     * @return Menu|null
     * @throws FullPathWasNotBuiltException
     */
    public function findMenuByPath(string $_path): ?self
    {
        $this->checkFullPathWasBuilt();
        if ($this->fullPath === $_path) {
            return $this;
        }

        $result = null;
        $this->deepWalkThroughChildrenItems(function (&$child, Menu &$parent) use ($_path, &$result) {
            if (!($child instanceof Menu)) {
                return;
            }

            if ($child->fullPath === $_path) {
                $result = $child;
                return;
            }
        });

        return $result;
    }

    /**
     * @param Menu $_menu
     * @param string $_path
     * @return Menu|null
     */
    public static function getMenuByPathOld(Menu $_menu, string $_path): ?self
    {
        $topPath   = self::getTopPath($_path, self::PATH_DELIMITER);
        $extraPath = self::deleteFirstPart($_path, self::PATH_DELIMITER);

        if ($_menu->id === $_path) {
            // Нашли полное совпадение.
            return $_menu;
        }

        if ($_menu->id !== $topPath) {
            // Верхняя часть пути не совпадает, дальнейший поиск невозможен.
            return null;
        }

        // Совпадает верхняя часть пути, продолжаем поиск среди элементов.
        $childrenResult = null;
        \array_walk_recursive($_menu->items,
            function (&$childMenu) use (&$childrenResult, $extraPath, $_menu) {
                if (!($childMenu instanceof Menu)) {
                    return;
                }

                $childResult = self::getMenuByPathOld($childMenu, $extraPath);
                if ($childResult !== null) {
                    $childrenResult = $childResult;
                }
            });

        return $childrenResult;
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * Обойти все дочерние элементы меню (любых типов).
     *
     * @param callable $_callback Функция, в которую будут переданы по ссылке каждый дочерний элемент и его родитель.
     */
    protected function deepWalkThroughChildrenItems(callable $_callback): void
    {
        \array_walk_recursive($this->items, function (&$child) use ($_callback) {
            $_callback ($child, $this);
            if ($child instanceof self) {
                $child->deepWalkThroughChildrenItems($_callback);
            }
        });
    }

    private function injectParentPath(Menu $_parent): void
    {
        $this->fullPath = $_parent->fullPath . self::PATH_DELIMITER . $this->id;
    }

    /**
     * @throws FullPathWasNotBuiltException
     */
    private function checkFullPathWasBuilt(): void
    {
        if (!empty ($this->fullPath)) {
            return;
        }

        throw new FullPathWasNotBuiltException('FullPath was not built! You must use Menu::makePathsFromThisRoot() on the root menu before using this method!');
    }

    /**
     * @noinspection PhpDocRedundantThrowsInspection
     * @throws UnknownMenuItemException
     */
    protected function checkItemsTypes(): void
    {
        \array_walk_recursive($this->items, function (&$value) {
            if ($value instanceof InlineKeyboardButton || $value instanceof self) {
                return;
            }

            $varType = \gettype($value);
            if ($varType === 'object') {
                $varType = \get_class($value);
            }
            throw new UnknownMenuItemException('Menu "' . $this->text
                . '" contains the element with unknown type "' . $varType . '"!');
        });
    }

    //------------------------------------------------------------------------------------------------------------------
    // Getters.
    //------------------------------------------------------------------------------------------------------------------
    /**
     * @return InlineButton
     * @throws BadCallbackDataFormatException
     * @throws FullPathWasNotBuiltException
     */
    public function getMenuSectionButton(): InlineButton
    {
        $this->checkFullPathWasBuilt();
        return new InlineButton(InlineButtonTypeEnum::MENU_SECTION(), $this->text, $this->fullPath);
    }

    /**
     * @return array
     */
    protected function getItems(): array
    {
        return $this->items;
    }
}
