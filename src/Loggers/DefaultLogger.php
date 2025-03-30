<?php

namespace Ultra\TranslationManager\Loggers;

use Ultra\TranslationManager\Interfaces\LoggerInterface;

class DefaultLogger implements LoggerInterface
{
    public function debug(string $message, array $context = []): void
    {
        \Log::debug($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        \Log::warning($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        \Log::error($message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        \Log::info($message, $context);
    }
}