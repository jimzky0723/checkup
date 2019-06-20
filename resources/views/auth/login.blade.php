<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PHA Check-Up | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ asset('resources/assets/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('resources/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/assets/css/AdminLTE.min.css') }}">
    <link rel="icon" href="{{ asset('resources/img/favicon.png') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
@if(Session::has('ok'))
    <div class="row">
        <div class="alert alert-success text-center">
            <strong class="text-center">{{ Session::get('ok') }}</strong>
        </div>
    </div>
@endif
<div class="login-box">
    <div class="login-logo">
        <img src="{{ asset('resources/img/logo.png') }}" />
        <br />
        <a href="#"><b>PHA</b> CHECK-UP LOGIN</a>
    </div><!-- /.login-logo -->

    <form role="form" method="POST" action="{{ url('/login') }}">
        {{ csrf_field() }}
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <div class="form-group has-feedback {{ $errors->has('username') ? ' has-error' : '' }}">
                <input id="username" type="text" placeholder="Login ID" class="form-control" name="username" value="{{ old('username') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" name="password" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="form-group">
                        <label style="cursor:pointer;">
                            <input type="checkbox" name="remember"> Remember Me
                        </label>
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div><!-- /.col -->
            </div>
        </div><!-- /.login-box-body -->

    </form>
</div><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="{{ asset('resources/assets/js/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ asset('resources/assets/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
</body>
</html>
