<?php

namespace Tests\Unit;

use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\BadCallbackDataFormatException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\Exceptions\TooLongCallbackDataParameterException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\InlineButton\InlineButton;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Exceptions\FullPathWasAlreadyBuiltException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Exceptions\FullPathWasNotBuiltException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Exceptions\UnknownMenuItemException;
use CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Menu\Menu;
use Longman\TelegramBot\Exception\TelegramException;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    /**
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     * @throws UnknownMenuItemException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClone(): void
    {
        $menu1 = $this->createTestMenu();
        $menu2 = clone $menu1;
        $this->assertInstanceOf(Menu::class, $menu2);
    }

    /**
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     * @throws UnknownMenuItemException
     * @throws FullPathWasNotBuiltException
     */
    public function testFullPathWasNotBuiltFindMenuByPath(): void
    {
        $menu = $this->createTestMenu();

        $this->expectException(FullPathWasNotBuiltException::class);
        $menu->findMenuByPath('root');
    }

    /**
     * @throws BadCallbackDataFormatException
     * @throws FullPathWasNotBuiltException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     * @throws UnknownMenuItemException
     */
    public function testFullPathWasNotBuiltGetInlineKeyboardButtons(): void
    {
        $menu = $this->createTestMenu();

        $this->expectException(FullPathWasNotBuiltException::class);
        $menu->getInlineKeyboardButtons();
    }

    /**
     * @throws BadCallbackDataFormatException
     * @throws FullPathWasNotBuiltException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     * @throws UnknownMenuItemException
     */
    public function testFullPathWasNotBuiltGetMenuSectionButton(): void
    {
        $menu = $this->createTestMenu();

        $this->expectException(FullPathWasNotBuiltException::class);
        $menu->getMenuSectionButton();
    }

    /**
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     * @throws UnknownMenuItemException
     * @throws FullPathWasAlreadyBuiltException
     */
    public function testFullPathWasAlreadyBuilt(): void
    {
        $menu = $this->createTestMenu();
        $menu->buildPathsFromThisRoot();

        $this->expectException(FullPathWasAlreadyBuiltException::class);
        $menu->buildPathsFromThisRoot();
    }

    /**
     * @throws BadCallbackDataFormatException
     * @throws FullPathWasAlreadyBuiltException
     * @throws FullPathWasNotBuiltException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     * @throws UnknownMenuItemException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testFindMenuByPath(): void
    {
        $menu = $this->createTopMenu();
        $menu->buildPathsFromThisRoot();

        $menuTarget01 = $menu->findMenuByPath(Menu::path('/'));
        $menuTarget02 = $menu->findMenuByPath(Menu::path(''));
        $menuTarget1  = $menu->findMenuByPath(Menu::path('root'));
        $menuTarget2  = $menu->findMenuByPath(Menu::path('root', 'section_a', 'section_c'));
        $menuTarget3  = $menu->findMenuByPath(Menu::path('root', 'section_a', 'section_c', 'section_c'));
        $menuTarget4  = $menu->findMenuByPath(Menu::path('unknown_path'));
        $menuTarget5  = $menu->findMenuByPath(Menu::path('root', 'unknown_section'));

        $this->assertNull($menuTarget01);
        $this->assertNull($menuTarget02);
        $this->assertEquals($menuTarget1->getMenuSectionButton()->getText(), 'Top root menu');
        $this->assertEquals($menuTarget2->getMenuSectionButton()->getText(), 'Section C 1');
        $this->assertEquals($menuTarget3->getMenuSectionButton()->getText(), 'Section C 2');
        $this->assertNull($menuTarget4);
        $this->assertNull($menuTarget5);
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * @return Menu
     *
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     * @throws UnknownMenuItemException
     */
    protected function createTopMenu(): Menu
    {
        return new Menu('Top root menu', 'root', [
            [
                InlineButton::toast('toast_id', 'Toast message!'),
                InlineButton::toast('toast_id', 'Toast message!'),
                new Menu ('Section A 1', 'section_a', [
                    InlineButton::toast('toast_id', 'Toast message!'),
                    new Menu ('Section C 1', 'section_c', [
                        InlineButton::toast('toast_id', 'Toast message!'),
                        InlineButton::toast('toast_id', 'Toast message!'),
                        [
                            new Menu ('Section C 2', 'section_c', [
                                InlineButton::toast('toast_id', 'Toast message!'),
                                InlineButton::toast('toast_id', 'Toast message!'),
                            ]),
                        ],
                    ]),
                ]),
                [
                    new Menu ('Section A 2', 'section_a', [
                        InlineButton::toast('toast_id', 'Toast message!'),
                    ]),
                ],
            ],
            InlineButton::toast('toast_id', 'Toast message!'),
            new Menu ('Section B', 'section_b', [
                InlineButton::toast('toast_id', 'Toast message!'),
                InlineButton::toast('toast_id', 'Toast message!'),
            ]),
        ]);
    }

    /**
     * @return Menu
     * @throws BadCallbackDataFormatException
     * @throws TelegramException
     * @throws TooLongCallbackDataParameterException
     * @throws UnknownMenuItemException
     */
    protected function createTestMenu(): Menu
    {
        return new Menu ('name', 'root', [
            InlineButton::toast('toast_id', 'Toast message!'),
            new Menu ('name', 'james', [
                InlineButton::toast('toast_id', 'Toast message!'),
                new Menu ('name', 'bond', [
                    InlineButton::toast('toast_id', 'Toast message!'),
                ])
            ]),
            new Menu ('name', 'john', [
                InlineButton::toast('toast_id', 'Toast message!'),
                new Menu ('name', 'smith', [
                    InlineButton::toast('toast_id', 'Toast message!'),
                ])
            ])
        ]);
    }
}
