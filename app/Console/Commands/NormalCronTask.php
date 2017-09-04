<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class NormalCronTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:normalcrontask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a normally scheduled task';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		Log::info('LOG:command: NORMAL-CRON-TASK');
        error_log('command: NORMAL-CRON-TASK');
    }
}
