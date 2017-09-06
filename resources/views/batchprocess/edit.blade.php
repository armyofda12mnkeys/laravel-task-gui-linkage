@extends('layouts.main')

@section('scripts')	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script>
		$(function(){
			$('#add_param_btn').click(function(){
				$('#params_holder').append(`<div class="param_holder">
					Param Key: <input type="text" name="new_param_keys[]"   value="" placeholder="enter_param_key_name" />
					<br/>
					Param Value: <input type="text" name="new_param_values[]" value="" placeholder="Enter your value for this key" />
				</div>`);
			});
			$('input[type=radio][name=timing_to_use]').change(function(){
				//alert('changed!');
				if (this.value == 'cron') {
					$('#cron_timing_holder').show();
					$('#keyword_timing_holder').hide();
					$('#schedule_timing_cron').val('');
				} else if (this.value == 'keyword') {					
					$('#cron_timing_holder').hide();
					$('#keyword_timing_holder').show();
					$('#schedule_timing_keyword').val('');
				}
			 });
			$('.remove_param_btn').click(function(){
				let param_holder = $(this).closest('.param_holder');
				param_holder.remove();
			});
		});
		</script>
@endsection

@section('content')	
		@if ($flash = session('flash_message') )
		<div class="flash">
			{{$flash}}
		</div>
		@endif
		
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

        <form action="/batchprocess/{{$batchprocess->id}}" method="POST">
			{!! csrf_field() !!}
			{{method_field('PUT')}} <!-- PATCH partial update, PUT whole object update -->
			<label>Name:</label> <input type="text" name="name" id="name" value="{{$batchprocess->name}}" />
			<br/>
			Would you like to use Cron timing or Keyword timing?<br/>
			<input type="radio" name="timing_to_use" value="cron"    {!!($batchprocess->timing_to_use == 'cron'    ? ' checked="checked"': '')!!}/> Cron<br/>
			<input type="radio" name="timing_to_use" value="keyword" {!!($batchprocess->timing_to_use == 'keyword' ? ' checked="checked"': '')!!}/> Keyword<br/>	
			<div id="cron_timing_holder"    {!! empty($batchprocess->schedule_timing_cron)    ? " style='display:none;'" : '' !!}>
				<label>Cron timing:</label> <input type="text" name="schedule_timing_cron" id="schedule_timing_cron" value="{{$batchprocess->schedule_timing_cron}}" />
			</div>
			<div id="keyword_timing_holder" {!! empty($batchprocess->schedule_timing_keyword) ? " style='display:none;'" : '' !!}>
				<label>Keyword timing:</label> 
				<select name="schedule_timing_keyword" id="schedule_timing_keyword">
					<option value="">--enter cron above or pick keyword below--</option>
					@foreach ($laravel_timing_keywords as $laravel_timing_keyword)
					<option value="{{$laravel_timing_keyword}}" {!!($batchprocess->schedule_timing_keyword == $laravel_timing_keyword ? ' selected="selected"': '')!!}>{{$laravel_timing_keyword}}</option>
					@endforeach
				</select>
				<!--<input type="text" name="schedule_timing_keyword" id="schedule_timing_keyword" value="{{$batchprocess->schedule_timing_keyword}}"/>-->
			</div>
			<br/>
			<label>Task associated with this batch job (Note: enter exactly as the name in the Command's $signature. For example: command:testtaskjob1):</label> <input type="text" name="task" id="task" value="{{$batchprocess->task}}" />
			<br/>
			<button type="button" id="add_param_btn">Add Param</button>
			<div id="params_holder">
				@foreach ($batchprocess->batchprocessparams as $batchprocessparam)
				<div class="param_holder">
					<input type="hidden" name="old_param_ids[]"   value="{{$batchprocessparam->id}}" />
					Param ID: {{$batchprocessparam->id}},
					Param Key: <input type="text" name="old_param_keys[]"   value="{{$batchprocessparam->key}}" placeholder="enter_param_key_name" />,
					Param Value: <input type="text" name="old_param_values[]" value="{{$batchprocessparam->value}}" placeholder="Enter your value for this key" />
					<button type="button" class="remove_param_btn">Remove Param</button>
				</div>
				
				@endforeach
			</div>
			
			<button type="submit" id="update_btn">UPDATE</button>
		</form>
@endsection