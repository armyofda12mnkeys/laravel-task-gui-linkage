<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchProcess extends Model
{    
	protected $table = 'batch_processes';
	
	protected $fillable = ['name','timing_to_use','schedule_timing_cron','schedule_timing_keyword','task'];
	//$b = App\BatchProcess::create(['name'=>'BatchJob1', 'schedule_timing_cron'=>'*/5 * * * *', 'task'=>'emails:send'])

	
	/**
     * Get the params for the BatchProcess
    */
    public function batchprocessparams()
    {
        return $this->hasMany('App\BatchProcessParam');
    }
}
