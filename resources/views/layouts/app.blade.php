<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="preloader">
        <span></span>
    </div>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->firstname }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')



    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>


    <style>
        #preloader {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            opacity: 0.3;
            background: #000;
            z-index: 99999;
        }
        #preloader > span {
            position: absolute;
            width: 70px;
            height: 70px;
            left: 50%;
            top: 35%;
            margin: 0 0 0 -15px;
            border: 8px solid #3097D1;
            border-right-color: transparent;
            border-radius: 50%;
            box-shadow: 0 0 25px 2px #ccc;
            animation: spin 1s linear infinite;
        }

        @keyframes spin
        {
            from { transform: rotate(0deg); opacity: 0.8; }
            50%  { transform: rotate(180deg); opacity: 1; }
            to   { transform: rotate(360deg); opacity: 0.8; }
        }
    </style>


    <script>

        $('form').submit(function(e){
            e.preventDefault();

            $('#preloader').show();

            $(this).find('.help-block').remove();
            $(this).find('.form-group').removeClass('has-error');

            $.ajax({
                url: this.action,
                type: this.method,
                data: $(this).serialize(),
                dataType: 'json',
                success: function (data) {
                    $('#preloader').hide();
                },
                error: function (data) {
                    var errors = data.responseJSON;

                    if(errors){
                        for(var field in errors){
                            $('#' + field)
                                .after('<span class="help-block">' + errors[field][0] + '</span>')
                                .parent().addClass('has-error');
                        }
                    }

                    $('#preloader').hide();
                }
            });
        });

        console.log(window.app);


    </script>
</body>
</html>
