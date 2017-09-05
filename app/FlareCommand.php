<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Log;

class FlareCommand extends Command
{
	public function customBefore() { 
		/*do common shared 'before' goals for all Batch Processes here, like audit-logging so params should be available here */ 
		Log::info('custom before!!!');
	}
	public function customAfter() { 
		/*do common shared 'after' goals for all Batch Processes. Params should be available here. */
		Log::info('custom after!!!');
	}
	
	public function __construct()
    {
       parent::__construct();
	}
	//override the execute function
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		//$this->before($this->customBefore());
		//$this->after($this->customAfter());
        $method = method_exists($this, 'handle') ? 'handle' : 'fire';		
        return $this->laravel->call([$this, $method]);
    }
}
