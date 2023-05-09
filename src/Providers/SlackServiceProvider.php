<?php

namespace Viodev\Providers;

use Illuminate\Support\ServiceProvider;
use Viodev\LaravelSlack;

class SlackServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LaravelSlack::class,  function () {
            return new LaravelSlack(
                config('app.name'),
                config('slack.webhook_url')
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/slack.php' => config_path('slack.php'),
        ], 'slack-config');
    }
}