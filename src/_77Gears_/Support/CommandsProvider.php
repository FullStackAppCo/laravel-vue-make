<?php

namespace _77Gears_\ReactMake\Support;

use Illuminate\Support\ServiceProvider;
use _77Gears_\ReactMake\Console\Commands\ReactComponentCommand;

class CommandsProvider extends ServiceProvider
{
  public function boot()
  {
    if (!$this->app->runningInConsole()) {
      return;
    }

    $this->commands([
      ReactComponentCommand::class,
    ]);

  }
}