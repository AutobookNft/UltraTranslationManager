<?php

namespace Ultra\TranslationManager\Providers;

use Illuminate\Contracts\Translation\Translator as TranslatorContract;
use Illuminate\Support\ServiceProvider;
use Ultra\TranslationManager\TranslationManager;
use Illuminate\Translation\Translator as LaravelTranslator;
use Ultra\TranslationManager\ErrorReporters\DefaultErrorReporter;
use Ultra\TranslationManager\Loggers\ULMLogger;

/**
 * Service Provider for Ultra Translation Manager.
 *
 * This class registers and bootstraps the Ultra Translation Manager (UTM) services
 * within a Laravel application. It binds the TranslationManager to the Laravel
 * IoC container, extends the default translator, and publishes configuration files.
 */
class UltraTranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * This method binds the Ultra Translation Manager to the application's service
     * container and extends Laravel's translator with UTM's implementation.
     *
     * @return void
     */
    public function register()
    {
        // Bind the 'ultra.translation' singleton to the container
        $this->app->singleton('ultra.translation', function ($app) {
            // Resolve the error reporter from config or use the default
            $errorReporter = $app->make(config('translation-manager.error_reporter', DefaultErrorReporter::class));
            
            // Use ULMLogger as the default logger since UltraLogManager is a required dependency
            $logger = new ULMLogger();
            
            // Return a new instance of TranslationManager with the configured dependencies
            return new TranslationManager($errorReporter, $logger);
        });

        // Extend Laravel's translator with UTM's implementation
        $this->app->extend('translator', function (LaravelTranslator $laravelTranslator, $app) {
            // Retrieve the UTM instance from the container
            $utmInstance = $app->make('ultra.translation');
            
            // Inject the Laravel translator into UTM using setter injection
            $utmInstance->setLaravelTranslator($laravelTranslator);
            
            // Return UTM as the new translator implementation
            return $utmInstance;
        });

        // Bind the TranslatorContract interface to the 'translator' binding
        $this->app->singleton(TranslatorContract::class, function ($app) {
            return $app->make('translator');
        });

        // Merge the package's configuration with the application's configuration
        $this->mergeConfigFrom(__DIR__ . '/../../config/translation-manager.php', 'translation-manager');
    }

    /**
     * Bootstrap services.
     *
     * This method publishes the configuration file and registers core translations
     * during the application's boot process.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the configuration file to the application's config directory
        $this->publishes([
            __DIR__ . '/../../config/translation-manager.php' => config_path('translation-manager.php'),
        ], 'utm-config');

        // Register core translations for the 'core' package using the UltraTrans facade
        \Ultra\TranslationManager\Facades\UltraTrans::registerPackageTranslations(
            'core',
            resource_path('lang')
        );
    }
}