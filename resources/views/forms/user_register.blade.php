{!! Form::model($model) !!}


<div class="form-group">
    {!! Form::label('firstname') !!}
    {!! Form::text('firstname') !!}
</div>

<div class="form-group">
    {!! Form::label('lastname') !!}
    {!! Form::text('lastname') !!}
</div>

<div class="form-group">
    {!! Form::label('email') !!}
    {!! Form::text('email') !!}
</div>

<div class="form-group">
    {!! Form::label('password') !!}
    {!! Form::password('password') !!}
</div>

<div class="form-group">
    {!! Form::label('password_confirmation') !!}
    {!! Form::password('password_confirmation') !!}
</div>

{!! Form::submit() !!}


{!! Form::close() !!}