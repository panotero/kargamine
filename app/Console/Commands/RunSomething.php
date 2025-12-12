<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunSomething extends Command
{

    protected $signature = 'run:something';

    protected $description = 'Runs a scheduled process';
    public function handle()
    {
        \Log::info('Scheduler executed run:something');

        return Command::SUCCESS;
    }
}
