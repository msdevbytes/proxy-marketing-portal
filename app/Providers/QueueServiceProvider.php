<?php

namespace App\Providers;

use App\Queue\DatabaseQueueMonitorCommand as QueueDatabaseQueueMonitorCommand;
use App\System\Queue\Console\DatabaseQueueMonitorCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class QueueServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Queue::failing(function (JobFailed $event) {
            report($event);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                QueueDatabaseQueueMonitorCommand::class,
            ]);
        }

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping(10)->sendOutputTo(storage_path() . '/logs/queue-jobs.log');
            $schedule->command('queue:restart')->hourly()->sendOutputTo(storage_path() . '/logs/queue-jobs.log');
            $schedule->command('queue:db-monitor')->everyTenMinutes()->sendOutputTo(storage_path() . '/logs/queue-jobs.log');
        });
    }

    public function register()
    {
        //
    }
}
