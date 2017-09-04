BATCH PROCESS VIEW (read-only):<br/>
<b>name:</b>{{$batchprocess->name}}
<br/>
<b>timing cron:</b>{{$batchprocess->schedule_timing_cron}}
<br/>
<b>timing keyword:</b> {{$batchprocess->schedule_timing_keyword}}
<br/>
<b>task name to do:</b> {{$batchprocess->task}}
<br/>
<b>task params:</b><br/>
<?php
//dd($batchprocess->batchprocessparams());
//dd($batchprocess->batchprocessparams);
//echo('key:'.$batchprocess->batchprocessparams[0]->key);
?>
@foreach ($batchprocess->batchprocessparams as $batchprocessparam)
	{{$batchprocessparam->key}}:{{$batchprocessparam->value}}<br/>
@endforeach