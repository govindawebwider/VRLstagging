<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<body>
<div class="container">
<div class="row">

<div class="col-md-4 col-md-offset-4">
  <div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title">Register</h4>
    </div>
    <div class="panel-body">
        {!! Form::open(array('url'=>'registration','class'=>'form form-horizontal text-left')) !!}
     {!! csrf_field() !!}
        {!! Form::label('username', 'Name')!!}
        {!! Form::text('username',null,array('class'=>'form-control'))!!}


        {!! Form::label('email', 'Email')!!}
        {!! Form::email('email',null,array('class'=>'form-control'))!!}

        {!! Form::label('password', 'Password')!!}
        {!! Form::password('password',array('class'=>'form-control'))!!}
        
        {!! Form::label('confirmpassword', ' Confirm Password')!!}
        {!! Form::password('confirmpassword',array('class'=>'form-control'))!!}

        {!! Form::label('dob', ' Date of Birth')!!}
        {!! Form::date('dob','',array('class'=>'form-control'))!!} 

        
        {!! Form::label('phone', ' Phone No.')!!}
        {!! Form::number('phone','',array('class'=>'form-control'))!!}

        {!! Form::label('gender', ' Choose Your Gender')!!}
        {!! Form::select('gender',array('male'=>'Male','female'=>'female'),null,['class'=>'form-control'])!!}

       


          <br>
          {!! Form::submit('Submit',array('class'=>'btn btn-primary center-block'))!!}
          {!! Form::close() !!}
    </div>
  </div>
</div>
</div>
</div>
<script src="//code.jquery.com/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>