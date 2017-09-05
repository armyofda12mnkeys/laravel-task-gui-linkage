<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;
//use ScheduleList;
//use TestTaskJob1;
//use TestTaskJob2;
//use BatchProcess;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
		\App\Console\Commands\ScheduleList::class,
		\App\Console\Commands\NormalCronTask::class,
        \App\Console\Commands\TestTaskJob1::class,
		\App\Console\Commands\TestTaskJob2::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

		//*
		// Get all tasks from the database
		$batch_processes = \App\BatchProcess::all();		
		// Go through each task to dynamically set them up.
		foreach ($batch_processes as $batch_process) {
			// Use the scheduler to add the task at its desired frequency
			$batchprocess_name = $batch_process->name;
			Log::info('Looking at BATCHPROCESS:'. $batchprocess_name);
			//error_log('Looking at BATCHPROCESS:'. $batchprocess_name);
			$batchprocess_task = $batch_process->task; //get via dropdown on Batch Process page; which is a Command which is what to run ... something like 'emails:send', 'writeTo:LogFile', 'pushnotification:not-logged-in-users', 'pushnotification:logged-in-but-no-submit-users', etc
			$batchprocess_timing_to_use = $batch_process->timing_to_use;
			$batchprocess_schedule_timing_cron = $batch_process->schedule_timing_cron; //like ‘* * * * *’
			$batchprocess_timing_keyword = $batch_process->schedule_timing_keyword; //like ‘daily’
			$batchprocess_params = $batch_process->batchprocessparams; //convert to format like '--param1=value1 --param2=value2'
			$batchprocess_params_str = '';
			foreach ($batchprocess_params as $batchprocess_param) {
				$batchprocess_params_str .= ' --'. $batchprocess_param->key ."='". $batchprocess_param->value ."'";
			}
			//Log::info('ADD BATCHPROCESS AS SCHEDULED TASK!!!: '. $batchprocess_task .' '. $batchprocess_params_str);
			//Log::info($batchprocess_timing_to_use);
			if($batchprocess_timing_to_use=='cron') {
				$schedule->command($batchprocess_task .' '. $batchprocess_params_str)
				->cron($batchprocess_schedule_timing_cron)
				->before(function() use ($batchprocess_name,$batchprocess_task,$batchprocess_params,$batchprocess_params_str) { Log::info("before command name=$batchprocess_name, task=$batchprocess_task, params=$batchprocess_params"); error_log('before command'); })
				->after( function() use ($batchprocess_name,$batchprocess_task,$batchprocess_params,$batchprocess_params_str) { Log::info("after command  name=$batchprocess_name, task=$batchprocess_task, params=$batchprocess_params"); error_log('after command'); });
			} else {
				$schedule->command($batchprocess_task .' '. $batchprocess_params_str)
				->$batchprocess_timing_keyword()
				->before(function() use ($batchprocess_name,$batchprocess_task,$batchprocess_params,$batchprocess_params_str) { Log::info("before command: name=$batchprocess_name, task=$batchprocess_task, params=$batchprocess_params"); error_log('before command'); })
				->after( function() use ($batchprocess_name,$batchprocess_task,$batchprocess_params,$batchprocess_params_str) { Log::info("after command:  name=$batchprocess_name, task=$batchprocess_task, params=$batchprocess_params"); error_log('after command');  });
			}		
		}
		//*/
		
		$schedule->command('command:normalcrontask')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
