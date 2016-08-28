<!-- resources/views/essay.blade.php -->

@extends('layouts.navibar')

@section('title', '心情板')

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

        .tasksignadd-container {
            width: 100%;
            max-width: 680px;
            display: table;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 200px;
            padding-left: 15px;
            padding-right: 15px;
        }

        .task-group {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .label-title {
            font-size: 24px;
            color: #f4645f;
        }

        label {
            font-size: 16px;
            /*color: #fe8f81;*/
        }

        .description {
            font-size: 18px;
        }

        .submit-button {
            width: 200px;
            margin-top: 50px;
            margin-bottom: 50px;
        }

    </style>

    <div class="container">
        <div class="tasksignadd-container">
            <h1 class="center">
                心情板
            </h1>
            <div>
                {!! csrf_field() !!}
                @foreach ($essays as $essay)
                    <div class="task-group">
                        <div class="form-group">
                            <label class="label-title">{{ $essay->time }}</label>
                            <p class="description center">{{ $essay->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection