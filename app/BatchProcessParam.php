<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchProcessParam extends Model
{
    //
	protected $table = 'batch_process_params';
	
	protected $fillable = ['key','value'];
	
	
	/**
     * Get the params for the BatchProcess
    */
    public function batchprocesses()
    {
        return $this->belongsTo('App\BatchProcess');
    }
}
