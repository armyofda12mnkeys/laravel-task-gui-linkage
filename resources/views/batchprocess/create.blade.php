<html>
	<head>
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
			$('#remove_param_btn').click(function(){
				
			});
		});
		</script>
	</head>
    <body>
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

        <form action="/batchprocess" method="POST">
			{!! csrf_field() !!}
			<label>Name:</label> <input type="text" name="name" id="name" />
			<br/>
			Would you like to use Cron timing or Keyword timing?<br/>
			<input type="radio" name="timing_to_use" value="cron"/> Cron<br/>
			<input type="radio" name="timing_to_use" value="keyword"/> Keyword<br/>
			<div id="cron_timing_holder" style="display:none;">
				<label>Cron timing:</label> <input type="text" name="schedule_timing_cron" id="schedule_timing_cron" />
			</div>
			<div id="keyword_timing_holder" style="display:none;">
				<label>Keyword timing:</label> 
				<select name="schedule_timing_keyword" id="schedule_timing_keyword">
					<option value="">--enter cron above or pick keyword below--</option>
					@foreach ($laravel_timing_keywords as $laravel_timing_keyword)
					<option value="{{$laravel_timing_keyword}}">{{$laravel_timing_keyword}}</option>
					@endforeach
				</select>				
				<!--<input type="text" name="schedule_timing_keyword" id="schedule_timing_keyword" />-->
			</div>
			<label>Task associated with this batch job (Note: enter exactly as the name in the Command's $signature. For example: command:testtaskjob1):</label> <input type="text" name="task" id="task" />
			<br/>
			<button type="button" id="add_param_btn">Add Param</button>
			<div id="params_holder">
			</div>
			
			<button type="submit" id="create_btn">>Create</button>
		</form>
		
    </body>
</html>