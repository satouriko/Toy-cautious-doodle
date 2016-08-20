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
            margin-right:10px;
        }
    </style>
    <!-- Navigation -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">Toy Cautious Doodle</a>
        </div>
        <div>
            <ul class="nav navbar-nav navbar-top-links navbar-right">
                <li>
                <form class="form-inline form-nav" method="POST" action="/auth/login">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <input type="email" class="form-control" id="navi_email" name="email" placeholder="护照">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="navi_pass" name="password" placeholder="口令">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember">记住我
                        </label>
                    </div>
                    <button type="submit" class="btn btn-default">登录</button>
                </form>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        语言
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">简体中文</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>



@endsection
