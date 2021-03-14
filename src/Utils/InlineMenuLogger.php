<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Utils;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

trait InlineMenuLogger
{
    /**
     * @var LoggerInterface|null
     */
    private $inlineMenuLogger;

    /**
     * @param LoggerInterface $_logger
     */
    public function setInlineMenuLogger(LoggerInterface $_logger): void
    {
        $this->inlineMenuLogger = $_logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getInlineMenuLogger(): LoggerInterface
    {
        if ($this->inlineMenuLogger === null) {
            $this->inlineMenuLogger = new NullLogger();
        }
        return $this->inlineMenuLogger;
    }
}
