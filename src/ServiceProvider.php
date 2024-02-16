<?php

namespace Thoughtco\StatamicStopForumSpam;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $listen = [
        \Statamic\Events\FormSubmitted::class => [Listeners\FormSubmittedListener::class],
    ];

    public function boot()
    {
        parent::boot();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/stop-forum-spam.php' => config_path('stop-forum-spam.php'),
            ], 'shopify-stop-forum-spam');
        }
    }
}
