{!! Form::open() !!}


<div class="form-group">
    {!! Form::label('email') !!}
    {!! Form::text('email') !!}
</div>

<div class="form-group">
    {!! Form::label('password') !!}
    {!! Form::password('password') !!}
</div>


{!! Form::submit() !!}


{!! Form::close() !!}