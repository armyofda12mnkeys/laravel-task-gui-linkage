@extends('layouts.main')

@section('scripts')
	<form action="/batchprocess/{{$batchprocess->id}}" method="POST">
		{!! csrf_field() !!}
		{{method_field('DELETE')}} 
		Are you sure you want to delete '{{$batchprocess->name}}' (id:{{$batchprocess->id}})?
		<br/>
		<button type="submit" name="is_delete_confirmed" value="yes">Yes</button>
		<br/>
		<button type="submit" name="is_delete_confirmed" value="no">No, do not delete!</button>
	</form>
@endsection