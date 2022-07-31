@extends('emails.layouts.default')
@section('content')

	<div style="text-align:center;">
		<h2 style="text-align:center;color: #000000;text-transform: uppercase;">
		{{__('Cultural incubator Request Received')}}
		</h2>
	</div>
	<br>
	{{__('Hello')}} <b>{{$request->full_name}}</b>, <br><br>
	{{__('Thank you for your cultural incubator request.')}}
    <br>
	{{__('We will get in touch with you soon.')}}
	<br><br>

	{{__('Regards')}}<br>
	{{__('Team Open Crate')}}<br>

@endsection