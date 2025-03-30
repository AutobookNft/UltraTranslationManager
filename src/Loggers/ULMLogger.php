<?php

namespace Ultra\TranslationManager\Loggers;

use Ultra\TranslationManager\Interfaces\LoggerInterface;
use Ultra\UltraLogManager\Facades\UltraLog;

class ULMLogger implements LoggerInterface
{
    public function debug(string $message, array $context = []): void
    {
        UltraLog::log('debug', 'UTM Action', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        UltraLog::warning('UTM Action', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        UltraLog::error('UTM Action', $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        UltraLog::info('UTM Action', $message, $context);
    }
}