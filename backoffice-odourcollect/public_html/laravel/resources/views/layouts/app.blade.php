<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{!! csrf_token() !!}"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('assets/js/100x100.min.js') }}" defer></script>
        <script src="{{ asset('assets/js/select2.min.js') }}" defer></script>

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('assets/css/100x100.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    </head>

    <body>
        <div id="app">
            <div class="dashboard-main-wrapper">
                <!-- navbar -->
                <div class="dashboard-header">
                    <nav class="navbar navbar-expand-lg bg-white fixed-top">
                        <a class="navbar-brand" href="{{route('home')}}"><img style="width: 26%" src="{{asset('assets/images/nav-logo.svg')}}"></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse " id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto navbar-right-top">
                                <li class="nav-item dropdown nav-user">
                                    <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-circle fa-lg"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                        @if(Auth::guard('web')->check())
                                        <a class="dropdown-item" href="{{route('logout')}}"><i class="fas fa-power-off mr-2"></i>Logout</a>
                                        @else
                                        <a class="dropdown-item" href="{{route('logoutadmin')}}"><i class="fas fa-power-off mr-2"></i>Logout</a>
                                        @endif
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>

                <div class="nav-left-sidebar sidebar-dark">
                    <div class="menu-list">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <a class="d-xl-none d-lg-none" href="#"></a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav flex-column">
                                    <li class="nav-item ">
                                        @if(Auth::guard('web')->check())
                                         <a class="nav-link" href="{{route('home')}}" aria-expanded="false">Dashboard</a>
                                        @else
                                         <a class="nav-link" href="{{route('homeadmin')}}" aria-expanded="false">Dashboard</a>
                                        @endif
                                       
                                    </li>

                                    <li class="nav-item ">
                                        @if(Auth::guard('web')->check())
                                        <a class="nav-link" href="{{route('statistics')}}" aria-expanded="false">Statistics</a>
                                        @else
                                        <a class="nav-link" href="{{ url('admin/statistics') }}" aria-expanded="false">Statistics</a>
                                        @endif
                                       
                                    </li>
                                    @if(Auth::guard('web')->check())
                                    @else
                                    <li class="nav-item ">
                                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2">Users <span class="badge badge-success">6</span></a>
                                        <div id="submenu-2" class="collapse submenu" style="">
                                            <ul class="nav flex-column">
  
                                                <li class="nav-item">
                                                    @if(Auth::guard('web')->check())
                                                    <a class="nav-link" href="{{ route('user.list') }}">Users list</a>
                                                    @else
                                                    <a class="nav-link" href="{{ url('admin/user/list') }}">Users list</a>
                                                    @endif
                                                    
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    @endif
                                    <li class="nav-item ">
                                        {{--<a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3">Observations <span class="badge badge-success">6</span></a>--}}
                                        {{--<div id="submenu-3" class="collapse submenu" style="">--}}
                                            {{--<ul class="nav flex-column">--}}
                                                {{--<li class="nav-item">--}}
                                                    @if(Auth::guard('web')->check())
                                                    <a class="nav-link" href="{{ route('odour.list') }}" aria-expanded="false">Observations</a>
                                                     @else
                                                     <a class="nav-link" href="{{ url('admin/odour/list') }}" aria-expanded="false">Observations</a>
                                                    @endif
                                                {{--</li>--}}
                                            {{--</ul>--}}
                                        {{--</div>--}}
                                    </li>
                                    @if(Auth::guard('web')->check())
                                    <li class="nav-item ">
                                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4">Case study <span class="badge badge-success">6</span></a>
                                        <div id="submenu-4" class="collapse submenu" style="">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    @if(Auth::guard('web')->check())
                                                     <a class="nav-link" href="{{ route('zone.create') }}">Add new</a>
                                                    @else
                                                     <a class="nav-link" href="{{ url('zone/create') }}">Add new</a>
                                                    @endif
                                                   
                                                </li>
                                                <li class="nav-item">
                                                    
                                                    @if(Auth::guard('web')->check())
                                                    <a class="nav-link" href="{{ route('zone.list') }}">Case study list</a>
                                                     @else
                                                     <a class="nav-link" href="{{ url('admin/zone/list') }}">Case study list</a>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    @else
                                    <li class="nav-item ">
                                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4">Case study <span class="badge badge-success">6</span></a>
                                        <div id="submenu-4" class="collapse submenu" style="">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    @if(Auth::guard('web')->check())
                                                     <a class="nav-link" href="{{ route('zone.create') }}">Add new</a>
                                                    @else
                                                     <a class="nav-link" href="{{ url('admin/zone/create') }}">Add new</a>
                                                    @endif
                                                   
                                                </li>
                                                <li class="nav-item">
                                                    
                                                    @if(Auth::guard('web')->check())
                                                    <a class="nav-link" href="{{ route('zone.list') }}">Case study list</a>
                                                     @else
                                                     <a class="nav-link" href="{{ url('admin/zone/list') }}">Case study list</a>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    @endif
                                    <li class="nav-item ">
                                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-5" aria-controls="submenu-5">Activities of interest <span class="badge badge-success">6</span></a>
                                        <div id="submenu-5" class="collapse submenu" style="">
                                            <ul class="nav flex-column">
                                                @if(Auth::guard('admin')->check())
                                                <li class="nav-item">
                                                   <a class="nav-link" href="{{ url('admin/point/type') }}">Types</a>
                                                </li>
                                                
                                                @endif
                                                @if(Auth::guard('admin')->check())
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ url('admin/point/create') }}">Add new</a>
                                                </li>
                                                @else
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ url('point/create') }}">Add new</a>
                                                </li>
                                                @endif
                                                <li class="nav-item">
                                                    @if(Auth::guard('web')->check())
                                                     <a class="nav-link" href="{{ route('point.list') }}">Activities list</a>
                                                    @else
                                                     <a class="nav-link" href="{{ url('admin/point/list') }}">Activities list</a>
                                                    @endif
                                                   
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item ">
                                    @if(Auth::guard('web')->check())
                                    <a class="nav-link" href="{{ route('odour.uploadcsv') }}" aria-expanded="false">Upload CSV</a>
                                    @else
                                    <a class="nav-link" href="{{ route('admin.odour.uploadcsv') }}" aria-expanded="false">Upload CSV</a>
                                    @endif
                                    </li>
                                </ul>
                                
                            </div>
                           
                        </nav>
                    </div>
                </div>

                <main class="py-4">
                    @yield('content')
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
                    

                </main>
            </div>
        </div>

    </body>
</html>
