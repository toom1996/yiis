<?php


namespace toom1996\base;


use toom1996\helpers\ConsoleHelper;
use toom1996\log\LogDispatcher;

class Stdout extends Component
{
    const STYLE = [
        LogDispatcher::LEVEL_INFO => [ConsoleHelper::BG_GREEN, ConsoleHelper::FG_BLACK],
        LogDispatcher::LEVEL_WARNING => [ConsoleHelper::BG_YELLOW, ConsoleHelper::FG_BLACK],
        LogDispatcher::LEVEL_ERROR => [ConsoleHelper::BG_RED, ConsoleHelper::FG_BLACK],
    ];

    private static function out(string $message, string $level)
    {
        $style = self::STYLE[$level];
        $levelString = self::string('[' . LogDispatcher::getLevelName($level) . ']', $style);
        echo $levelString, ' ', $message, PHP_EOL;
    }

    public static function info(string $message)
    {
        self::out($message,LogDispatcher::LEVEL_INFO);
    }

    public static function warnning(string $message)
    {
        self::out($message,LogDispatcher::LEVEL_WARNING);
    }

    public static function error(string $message)
    {
        self::out($message,LogDispatcher::LEVEL_ERROR);
    }

    public static function string($string, $format = [])
    {
        return ConsoleHelper::ansiFormat($string, $format);
    }

}