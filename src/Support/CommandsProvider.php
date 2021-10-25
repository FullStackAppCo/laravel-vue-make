<?php

namespace FullStackAppCo\VueMake\Support;

use Illuminate\Support\ServiceProvider;
use FullStackAppCo\VueMake\Console\Commands\VueComponentCommand;

class CommandsProvider extends ServiceProvider
{

    public static function stubs()
    {
        return [
            'vue.stub',
        ];
    }

    public function boot()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            VueComponentCommand::class,
        ]);

        $publishes = [];

        foreach (static::stubs() as $stub) {
            $publishes[__DIR__ . "/../../stubs/{$stub}"] = base_path("stubs/{$stub}");
        }

        $this->publishes($publishes, 'vue-stub');
    }
}
