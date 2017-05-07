<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>Udoktor</title>
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="{{ mix('css/all.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @yield('css')
</head>

<body class="@yield('body-class')">
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader pl-size-xl">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <h1>Cargando aplicación; por favor, espere...</h1>
        </div>
    </div>

    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">UDOKTOR</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="http://" target="_blank"><i class="fa fa-facebook fa-2x"></i></a></li>
                    <li><a href="http://" target="_blank"><i class="fa fa-twitter fa-2x"></i></a></li>
                    <li><a href="http://" target="_blank"><i class="fa fa-pinterest fa-2x"></i></a></li>
                    <li><a href="http://" target="_blank"><i class="fa fa-youtube fa-2x"></i></a></li>
                    <li><a href="http://" target="_blank"><i class="fa fa-google-plus fa-2x"></i></a></li>
                    <li><a href="http://" target="_blank"><i class="fa fa-instagram fa-2x"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="@yield('body-body')">
        <div class="logo">
            <a href="#"><b>Udoktor</b></a>
            <small>Bienvenido</small>
        </div>
        <div class="card">
            <div class="body">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ mix('js/all.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    @yield('js')
</body>
</html>