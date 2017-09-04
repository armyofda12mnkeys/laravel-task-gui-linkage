<html>
    <body>
		@if ($flash = session('flash_message') )
		<div class="flash">
			{{$flash}}
		</div>
		@endif
		<div>
			<h3>List of Batch Processes:</h3>
			<table>
					<tr>
						<td>Name</td>
						<td>Edit</td>
						<td>Delete</td>
					</tr>
					@foreach ($batchprocesses as $batchprocess)
					<tr>
						<td>{{$batchprocess->name}}</td>
						<td>
							<a href="batchprocess/{{$batchprocess->id}}">View</a>
						</td>
						<td>
							<a href="batchprocess/{{$batchprocess->id}}/edit">Edit</a>
							<!--<a href="{{ route('batchprocess.create', $batchprocess->id) }}">EDIT3</a>-->
						</td>
						<td>
								<a href="batchprocess/{{$batchprocess->id}}/deleteConfirm">Delete</a>
						</td>
					</tr>
					@endforeach
			</table>
			<a href="batchprocess/create">Create new Batch Process</a>
			
			<h3>Current Tasks</h3>
			<pre>{{$tasks_output}}</pre>
			<?php
			/*
			//dont think i have access to command line output
			@foreach ($tasks as $task)
				<div>{{$task}}</div>
			@endforeach
			*/
			?>
		</div>
    </body>
</html>
