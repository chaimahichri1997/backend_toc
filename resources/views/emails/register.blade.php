<div style="text-align:center;">
    <h2 style="text-align:center;text-transform: uppercase;">
        Your account at Toc
    </h2>
</div>
<br>
Hello <b>{{$user->first_name}} {{$user->last_name}}</b>, <br><br>
Your account has been created successfully.<br>
Please activate your account by clicking on this <a href="{{ $url }}" target="_blank">link</a>.
<br><br>

{{__('Best regards,')}}<br>
{{__('AES')}}
