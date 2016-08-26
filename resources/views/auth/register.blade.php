<!-- resources/views/auth/register.blade.php -->

@extends('layouts.navibar_guest')

@section('title', '加入')

@section('content')

    <style>
        .loginpanel {
            /*position:absolute;*/
            z-index: 1;
            top: 20%;
            height: 270px;
            /* max-width:350px;*/
            margin-top: 10px;
            background-color: #FFFFFF;
            border-radius: 5px;
            border: 1px solid #2aabd2;
        }

        .center {
            width: auto;
            display: table;
            margin-left: auto;
            margin-right: auto;
        }

        .text-center {
            text-align: center;
        }
    </style>

    <div class="container">
        <div class="col-xs-12" style="margin-top: 20px">
            <img class="center" src="/img/0820_2.gif" alt="Cautious doodle"/>
        </div>
        <div class="col-xs-12">
            <h1 class="center">Join Toy Cautious Doodle</h1>
        </div>
        @if (count($errors) > 0)
            <div class="col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3 col-xs-12">
                @foreach ($errors->all() as $error)
                    <div class="alert alert-warning">
                        <a href="#" class="close" data-dismiss="alert">
                            &times;
                        </a>
                        {{ $error }}
                    </div>
                @endforeach
            </div>

        @endif
        <div class="loginpanel col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3 col-xs-12">
            <form method="POST" action="/auth/register" class="form-horizontal">
                {!! csrf_field() !!}

                <div class="col-xs-12"><br/>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}" required="true"
                           placeholder="电子邮箱">
                    <br/>
                    <input class="form-control" type="password" name="password" required="true" placeholder="口令">
                    <br/>
                    <input class="form-control" type="password" name="password_confirmation" required="true"
                           placeholder="确认口令">
                    <br/>
                    <!--<input class="btn btn-default" type="button" id="Button2" value="注册" onclick="window.location.href='reg'"/>-->
                    <input class="btn btn-primary" type="submit" id="Button1" style="width:100%; margin-bottom:10px;"
                           value="加入"/>
                    <a href="/password/email">忘记密码？</a></div>
            </form>
        </div>
    </div>

@endsection