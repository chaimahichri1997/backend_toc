@extends('emails.layouts.default')
@section('content')

	<div style="text-align:center;">
		<h2 style="text-align:center;color: #000000;text-transform: uppercase;">
		{{__('Contact Request Received')}}
		</h2>
	</div>
	<br>
	{{__('Hello')}} <b>{{$contact->name}}</b>, <br>
	{{__('Thank you for your contact request.')}}
    <br>
	{{__('We will get in touch with you soon.')}}
	<br><br>

	{{__('Regards')}}<br>
	{{__('Team Open Crate')}}<br>

@endsection