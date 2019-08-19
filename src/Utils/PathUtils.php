<?php

namespace CaliforniaMountainSnake\LongmanTelegrambotInlinemenu\Utils;

trait PathUtils
{
    /**
     * @param string $_path
     * @param string $_delimiter
     *
     * @return string
     */
    protected static function deleteFirstPart(string $_path, string $_delimiter): string
    {
        $ex = \explode($_delimiter, $_path);
        if (\count($ex) > 1) {
            unset ($ex[0]);
            return \implode($_delimiter, $ex);
        }

        return $_path;
    }

    /**
     * @param string $_path
     * @param string $_delimiter
     *
     * @return string
     */
    protected static function getTopPath(string $_path, string $_delimiter): string
    {
        $ex = \explode($_delimiter, $_path);
        return $ex[0];
    }
}
