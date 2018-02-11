<?php
namespace Deflopian\EducationTestToCourse;

use Illuminate\Support\ServiceProvider;

class EducationTestToCourseServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('education-test-to-course.php'),
        ]);

        $this->publishes([
            __DIR__.'/../Views/mail' => resource_path('views/mail'),
        ]);

        // Register commands
        $this->commands('command.education-test-to-course.migration');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRoutes();

        $this->registerCommands();

        $this->mergeConfig();
        $this->loadViews();
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->singleton('command.education-test-to-course.migration', function ($app) {
            return new MigrationCommand();
        });
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
    }

    /**
     * Merges user's and tests's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'education-test-to-course'
        );
    }

    /**
     * Merges user's and tests's configs.
     *
     * @return void
     */
    private function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../Views', 'education-test-to-course');
    }

    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.education-test-to-course.migration'
        ];
    }
}
