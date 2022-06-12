<?php

namespace JoeriAbbo\Slack;

use Illuminate\Support\ServiceProvider;
use JoeriAbbo\Slack\Client\Client as SlackClient;
use JoeriAbbo\Slack\Client\MockSlackClient;

class SlackPackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();
        if (!empty($channels = config('slack.channels'))) {
            foreach ($channels as $key => $channel) {
                $this->app->bind(sprintf('slack.%s', $key), function () use ($channel) {
                    if (env('APP_ENV') != 'testing' && !empty(config('slack.bearer_token'))) {
                        return new SlackClient(config('slack.bearer_token'), $channel);
                    } else {
                        return new MockSlackClient('INVALID TOKEN', $channel);
                    }
                });
            }
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/config.php' => config_path('slack.php'),], 'config');
        }

    }

    public function boot(): void
    {
    }
}
