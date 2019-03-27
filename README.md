# longmantelegrambot-inlinemenu
It's the inline menu for the longman/telegram-bot library!

Functionality:
- Inline-menu with sub-menus.
- Call a bot command by inline button.
- Toast message!

Usage:
1. Create your own Menu object.
2. Extend your standard CallbackqueryCommand from InlineMenuCallbackqueryCommand and realise abstract methods.
3. Get standard Longman\TelegramBot\Entities\InlineKeyboard object: $menu->getInlineKeyboard() and use it in Request::sendMessage ().
