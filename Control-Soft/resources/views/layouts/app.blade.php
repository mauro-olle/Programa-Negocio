<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Control Soft') }}</title>

    {{-- Icon --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* Sticky footer styles-------------------------------------------------- */
        html {
            position: relative;
            min-height: 100%;
        }
        body {
        /* Margin bottom by footer height */
            margin-bottom: 60px;
            background-image: url(/wallpaper.jpg);
            background-size: cover;
        }

        @media (min-width: 768px) 
        {
            .sidebar 
            {
            margin-top: 0px;
            }
        }
        
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        /* Set the fixed height of the footer here */
            height: 60px;
            line-height: 60px; /* Vertically center the text there */
            background-color: #ffffffd9;
        }

        .footer > .container {
            padding-right: 15px;
            padding-left: 15px;
            background-image: url(/logo.png);
            background-size: 180px;
            background-repeat: no-repeat;
            background-position: right;
            background-position-y: 0px;
        }
        
        /* Custom page CSS
        -------------------------------------------------- */
        /* Not required for template or sticky footer method. */
    
        body > .container {
            padding: 60px 15px 0;
        }
        
        .navbar-default{
            background-position: center;
            background-position-y: 0px;
            background-repeat: no-repeat;
            background-size: 160px;
        }

        .footer > .container {
            padding-right: 15px;
            padding-left: 15px;
            /* background-image: url(/logo.png); */
            background-size: 180px;
            background-repeat: no-repeat;
            background-position: right;
            background-position-y: 0px;
        }
        /* Para las imagenes de Productos. */
        .zoom {
            padding: 1px;
            background-color: #dbdbdb;
            transition: transform .25s;
            z-index:1;
            position: inherit;
        }
        
        span.oi {
            display: contents;
        }

        .zoom:hover {
            -ms-transform: scale(7); /* IE 9 */
            -webkit-transform: scale(7); /* Safari 3-8 */
            transform: scale(7); 
        }

        .table>tbody>tr>td {
            padding: 5px;
            vertical-align: middle;
        }

        code {
            font-size: 80%;
        }

        .pd6 {
            padding: 6px;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
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
                        @guest
                            {{-- <li><a href="{{ route('login') }}">Login</a></li> --}}
                            {{--  <li><a href="{{ route('register') }}">Register</a></li>  --}}
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->nombre }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Cerrar sesión
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
        
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
        <footer class="footer">
            <div class="container">
                <span class="text-muted">Por <b>JABlack Soft</b> | Copyright © 2018
                </span>
            </div>
        </footer>
    </body>
</html>