<?php


namespace Ultra\TranslationManager\ErrorReporters;

use Illuminate\Support\Facades\Log;
use Ultra\TranslationManager\Interfaces\ErrorReporter;

class DefaultErrorReporter implements ErrorReporter
{
    public function report(string $errorCode, array $context = [], ?\Throwable $exception = null): void
    {
        $message = "[UTM] Error: {$errorCode}";
        if ($exception) {
            $context['exception'] = $exception->getMessage();
        }
        Log::warning($message, $context);
    }
}
