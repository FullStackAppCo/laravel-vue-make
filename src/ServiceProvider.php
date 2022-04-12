<?php

namespace FullStackAppCo\VueMake;

use FullStackAppCo\VueMake\Console\Commands\VueComponentCommand;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
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
            $publishes[__DIR__ . "/../stubs/{$stub}"] = base_path("stubs/{$stub}");
        }

        $this->publishes($publishes, 'vue-stub');
    }
}
