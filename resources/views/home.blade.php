<!-- resources/views/home.blade.php -->

@extends('layouts.navibar')

@section('title', '主页')

@section('content')

    <style>
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
        <div class="row" style="margin-top: 20px">
            <img class="center" src="/img/0820_2.gif" alt="Cautious doodle"/>
        </div>
        <div class="row">
            <h1 class="center">Toy Cautious Doodle</h1>
        </div>

    </div>

@endsection