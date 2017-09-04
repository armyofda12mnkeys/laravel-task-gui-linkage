<?php

namespace App\Http\Controllers;

use App\BatchProcess;
use App\BatchProcessParam;
use Illuminate\Http\Request;
use DB;
use Session;
use Log;
use Illuminate\Support\Facades\Artisan;

class BatchProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$data = array();
		//$data['username'] = 'John Smith';
		$batchprocesses = BatchProcess::all();
		$data['batchprocesses'] = $batchprocesses;
		$tasks = Artisan::call('schedule:list');
		$tasks_output = Artisan::output();
		//$data['tasks'] = $tasks;
		$data['tasks_output'] = $tasks_output;
        return view('batchprocess.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {		
		$laravel_timing_keywords = ['everyMinute','everyFiveMinutes','everyTenMinutes','everyThirtyMinutes','hourly','daily','weekly','monthly','quarterly','yearly'];
		$data['laravel_timing_keywords'] = $laravel_timing_keywords;
        return view('batchprocess.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$this->validate($request, [
			'name' => 'required|min:3',			
			'timing_to_use' => 'required',
			'schedule_timing_cron' => 'required_without:schedule_timing_keyword',
			'schedule_timing_keyword' => 'required_without:schedule_timing_cron',
			'task' => 'required',
    	]);
		
		//how to validate BatchProcessParam's keys/params?
		
		DB::transaction(function($request) use ($request)
		{
			$batchprocess = BatchProcess::create([
				'name' => $request->name, 
				'timing_to_use' => $request->timing_to_use,
				'schedule_timing_cron' => $request->schedule_timing_cron, 
				'schedule_timing_keyword' => $request->schedule_timing_keyword, 
				'task' =>  $request->task
			]);

			if($batchprocess) {
				$batch_process_param_keys = $request->new_param_keys;
				$batch_process_param_values = $request->new_param_values;
				foreach($batch_process_param_keys as $index=>$batch_process_param_key){
					$batch_process_param_value = $batch_process_param_values[$index];
					error_log('Creating new Param:' .$batch_process_param_key .':'. $batch_process_param_value );
					$batchprocess->batchprocessparams()->create([
						'key'  => $batch_process_param_key,
						'value'=> $batch_process_param_value,
					]);
				}
				Session::flash('flash_message','Created new Batch "'. $batchprocess->name .'"');	
			} else {				
				Session::flash('flash_message','Could not create new Batch "'. $request->name .'"');
				throw new \Exception('Could not create new Batch');
			}
		});
		
		
		return redirect()->route('batchprocess.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BatchProcess  $batchProcess
     * @return \Illuminate\Http\Response
     */
    public function show(BatchProcess $batchprocess)
    {
		$data['batchprocess'] = $batchprocess;
        return view('batchprocess.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BatchProcess  $batchProcess
     * @return \Illuminate\Http\Response
     */
    public function edit(BatchProcess $batchprocess)
    {
		$data['batchprocess'] = $batchprocess;
		$laravel_timing_keywords = ['everyMinute','everyFiveMinutes','everyTenMinutes','everyThirtyMinutes','hourly','daily','weekly','monthly','quarterly','yearly'];
		$data['laravel_timing_keywords'] = $laravel_timing_keywords;
        return view('batchprocess.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BatchProcess  $batchProcess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BatchProcess $batchprocess)
    {
		$this->validate($request, [
			'name' => 'required|min:3',			
			'timing_to_use' => 'required',
			'schedule_timing_cron' => 'required_without:schedule_timing_keyword',
			'schedule_timing_keyword' => 'required_without:schedule_timing_cron',
			'task' => 'required',
    	]);
		Session::flash('flash_message','UPDATED Batch "'. $batchprocess->name .'"');	
		//how to validate BatchProcessParam's keys/params?
		
		DB::transaction(function($request) use ($request, $batchprocess)
		{			
			$update_result = $batchprocess->update($request->all());
			error_log('ARI!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
			error_log($update_result);
			
			if($batchprocess) {
				$old_batch_process_param_ids = $request->old_param_ids;
				$old_batch_process_param_keys = $request->old_param_keys;
				$old_batch_process_param_values = $request->old_param_values;	
				//1st delete ones not in these keys, the remaining keys on the screen
				if(sizeof($old_batch_process_param_ids) > 0 ) {
					BatchProcessParam::where('batch_process_id', $batchprocess->id)
					->whereNotIn('id', $old_batch_process_param_ids)
					->delete();
				} else { //if no 'old keys' on the screen, means have to remove all of them (be4 adding new ones)
					BatchProcessParam::where('batch_process_id', $batchprocess->id)
					->delete();
				}
				
				
				//2nd: update the current ones on the screen
				if(sizeof($old_batch_process_param_ids)>0) {
					foreach($old_batch_process_param_ids as $index=>$old_batch_process_param_id){
						$old_batch_process_param_key = $old_batch_process_param_keys[$index];
						$old_batch_process_param_value = $old_batch_process_param_values[$index];
						error_log('UPDATE old Param (id:'. $old_batch_process_param_id .'):'. $old_batch_process_param_key .':'. $old_batch_process_param_value );

						$param = BatchProcessParam::find($old_batch_process_param_id);
						//error_log(print_r($param,1));
						if($param) {
							$param->key   = $old_batch_process_param_key;
							$param->value = $old_batch_process_param_value;
							$param->save();
						}
					}
				}
					
				//3rd: insert new ones on the screen
				$new_batch_process_param_keys = $request->new_param_keys;
				$new_batch_process_param_values = $request->new_param_values;
				if(sizeof($new_batch_process_param_keys)>0) {
					foreach($new_batch_process_param_keys as $index=>$new_batch_process_param_key){
						$new_batch_process_param_value = $new_batch_process_param_values[$index];
						error_log('Creating new Param:' .$new_batch_process_param_key .':'. $new_batch_process_param_value );
						$batchprocess->batchprocessparams()->create([
							'key'  => $new_batch_process_param_key,
							'value'=> $new_batch_process_param_value,
						]);
					}
				}
				
				//done!
				Session::flash('flash_message','Updated Batch "'. $batchprocess->name .'"');	
			} else {				
				Session::flash('flash_message','Could not update Batch "'. $request->name .'"');
				throw new \Exception('Could not create new Batch');
			}
		});
		
		
		return redirect()->route('batchprocess.edit', $batchprocess->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BatchProcess  $batchProcess
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, BatchProcess $batchprocess)
    {
        //flash deleted message
		$is_delete_confirmed = $request->is_delete_confirmed;
		$batch_name = $batchprocess->name;
		if($is_delete_confirmed === 'yes') {			
			$batchprocess->delete();
			Session::flash('flash_message','Batch "'. $batch_name .'" deleted.');
			return redirect()->route('batchprocess.index');
		} else {
			Session::flash('flash_message','Batch "'. $batchprocess->name .'" NOT deleted. Please confirm if you would like to delete.');
			return redirect()->route('batchprocess.index');
		}
    }	
    public function deleteConfirm(BatchProcess $batchprocess)
    {
		$data['batchprocess'] = $batchprocess;
        return view('batchprocess.delete-confirm', $data);
    }
}
