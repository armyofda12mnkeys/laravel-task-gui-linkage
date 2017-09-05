<?php

namespace App\Console\Commands;

//use Illuminate\Console\Command;
use Log;
use App\FlareCommand;

class TestTaskJob2 extends FlareCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:testtaskjob2 {--param1=some_default_key} {--param2=This is the default param2} {--param3=foo_bar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a test command #2';

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
        $params = $this->options();
		Log::info('LOG:command: testtaskjob2, params:'.print_r($params,1));
		error_log('command: testtaskjob2, params:'.print_r($params,1));
    }
}
