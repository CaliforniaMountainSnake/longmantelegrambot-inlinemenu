# longmantelegrambot-inlinemenu
It's the inline menu for the longman/telegram-bot library!

## Functionality:
- Inline-menu with sub-menus.
- Call a bot command by inline button.
- Toast message!


## Install:
### Require this package with Composer
Install this package through [Composer](https://getcomposer.org/).
Edit your project's `composer.json` file to require `californiamountainsnake/longmantelegrambot-inlinemenu`:
```json
{
    "name": "yourproject/yourproject",
    "type": "project",
    "require": {
        "php": "^7.3.1",
        "californiamountainsnake/longmantelegrambot-inlinemenu": "*"
    }
}
```
and run `composer update`

### or
run this command in your command line:
```bash
composer require californiamountainsnake/longmantelegrambot-inlinemenu
```

## Usage:
1. Extend your Telegram class from InlineMenuTelegram (or include the InlineMenuTelegramTrait into your existed Telegram class):
```php
<?php
// Create your own Telegram class:
class MyTelegram extends InlineMenuTelegram
{

}

// somewhere in the webhook.php:
$telegram = new MyTelegram($bot_api_key, $bot_username);
```

2. Create your Menu object:
```php
<?php
$menu = new Menu('Top root menu', 'root', [
    [
        InlineButton::startCommand('Help!', 'help'),
        InlineButton::url('It\'s google', 'https://google.ru'),
    ],
    [
        InlineButton::toast('toast_identifier', 'Toast text!'),
        new Menu('Sub menu', 'submenu', [
            InlineButton::toast('sub_menu_toast', 'Submenu toast!'),
            InlineButton::menuSection('<< Back', Menu::path('root'))
        ]),
    ]
]);

// You must call this method once only on the your TOP level menu object.
$menu->buildPathsFromThisRoot();
```

3. Extend your standard CallbackqueryCommand from InlineMenuCallbackqueryCommand and realise abstract methods:
```php
<?php
class CallbackqueryCommand extends InlineMenuCallbackqueryCommand
{
    protected function getRootMenu(): Menu
    {
        // return the Menu object from the previous step
        return new Menu (...);
    }
}
```
 
4. Get standard Longman\TelegramBot\Entities\InlineKeyboard object and use it in Request::sendMessage () in your commands:
```php
<?php
$menu = new Menu (...);
$menu->buildPathsFromThisRoot();
$inlineKeyboard = $menu->getInlineKeyboard();

Request::sendMessage([
    'chat_id' => 'some_chat_id',
    'text' => 'This is menu!',
    'reply_markup' => $inlineKeyboard,
]);
```
