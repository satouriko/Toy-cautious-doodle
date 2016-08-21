@extends('layouts.master')

@section('navibar')

    <style>
        .form-nav {
            margin-top: 0;
            margin-bottom: 0;
            padding-top: 7.5px;
            padding-bottom: 7.5px;
            padding-right: 7.5px
        }

        .navbar-top-links {
            margin-right: 10px;
        }
    </style>
    <!-- Navigation -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">Toy Cautious Doodle</a>
        </div>
        <div>
            <ul class="nav navbar-nav navbar-left">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        任务
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">创建任务</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        心情
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">记录一条心情</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right navbar-top-links">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ $user }}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/profile">个人资料</a></li>
                        <li><a href="/auth/logout">登出</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>



@endsection
