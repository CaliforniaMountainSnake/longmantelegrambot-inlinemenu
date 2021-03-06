# Changelog
The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
### Changed
### Deprecated
### Removed
### Fixed
### Security


## [1.3.0] - 2021-03-15
### Changed
- A better way to run commands.

## [1.2.1] - 2021-03-14
### Fixed
- Logger fix in the InlineMenuCallbackqueryCommand.

## [1.2.0] - 2021-03-14
### Added
- InlineMenuLogger trait.
- InlineMenuTelegramTrait::setCommandsPaths() method.
### Fixed
- A new modern way to execute commands in the InlineMenuTelegramTrait.
- Some fixes in the MenuTest.

## [1.1.7] - 2019-12-14
### Added
- InlineMenuToastServerResponse works and has been improved!

## [1.1.6] - 2019-12-14
### Added
- Added the experimental support to show toast when command has been started via inline button.
### Changed
- Composer dependencies have been updated.

## [1.1.5] - 2019-09-06
### Added
- Added the method InlineMenuTelegramTrait::executeCommandWithText() that can execute any command and pass a text into it.

## [1.1.4] - 2019-08-29
### Changed
- ! The signature of methods MenuUtils::sendTextMessageAndShowMenu() and MenuUtils::sendFatalError() have been changed!

## [1.1.3] - 2019-08-23
### Fixed
- Fix the bug. InlineButtonHelpers::buttonsArray() now can unpack the associative arrays.

## [1.1.2] - 2019-08-21
### Changed
- The InlineButtonHelpers::buttonsArray() now sends the array's keys to a command and uses the array's values as the visible button text.

## [1.1.1] - 2019-08-20
### Changed
- The InlineButtonHelpers::buttonsArray() has been improved.

## [1.1.0] - 2019-08-19
### Added
- The number of CallbackDataTypeEnum's types has been reduced.
- The functional of START_COMMAND has been greatly improved. Now it can start command with user's input text. Inline button can fully emulate the regular keyboard's button. The text from the inline button can be sent into the target command like a regular keyboard button has been pressed.
- Added the possibility to delete the message with the inline keyboard after an inline button has been pressed.
- There is more qualitative infrastructure of the inline buttons processing.
### Changed
- Updated Composer dependencies.

## [1.0.7] - 2019-08-07
### Added
- Added the possibility to specify the chat_id and parse_mode in the MenuUtils::sendTextMessageAndShowMenu() method.

## [1.0.6] - 2019-07-29
### Changed
- The package californiamountainsnake/longmantelegrambot-utils has been updated to the version 1.1.0.
- Updated Composer dependencies.

## [1.0.5] - 2019-07-22
### Added
- Added the MenuUtils trait.
### Changed
- Updated Composer dependencies.

## [1.0.4] - 2019-07-14
### Added
- Added this changelog.
### Changed
- Changed longman/telegram-bot version to ~0.55 (from ~0.55.1) to be compatible with new library versions.
- Changed minimal php version to 7.2 (from 7.3.1).
