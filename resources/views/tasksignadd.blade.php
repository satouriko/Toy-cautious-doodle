<!-- resources/views/tasksignadd.blade.php -->

@extends('layouts.navibar')

@section('title', '打卡')

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
            color: #777777;
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
                每日打卡
            </h1>
            <form class="form-horizontal" method="post" action="/task/tasksign">
                {!! csrf_field() !!}
                @foreach ($tasks as $task)
                    <input type="hidden" name="task_id[{{ $task->id }}]" value="{{ $task->id }}">
                    <div class="task-group">
                        <div class="form-group">
                            <label class="label-title">{{ $task->title }}</label>
                            <p class="description center">{{ $task->description }}</p>
                        </div>
                        <div class="form-group">
                            <label>完成情况</label>
                            <div class="center">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-success">
                                        <input type="radio" name="grade[{{ $task->id }}]" value="Checked"/> 完成
                                    </label>
                                    <label class="btn btn-danger">
                                        <input type="radio" name="grade[{{ $task->id }}]" value="Unchecked"/> 未完成
                                    </label>
                                    <label class="btn btn-warning">
                                        <input type="radio" name="grade[{{ $task->id }}]" value="Delayed"/> 因故推迟
                                    </label>
                                    <label class="btn btn-info">
                                        <input type="radio" name="grade[{{ $task->id }}]" value="Cancelled"/> 取消
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reasonInput[{{ $task->id }}]">（未完成/推迟/取消）原因</label>
                            <input class="form-control" id="reasonInput[{{ $task->id }}]"
                                   name="reason[{{ $task->id }}]">
                        </div>
                        <div class="form-group">
                            <label for="commentInput[{{ $task->id }}]">评论</label>
                            <textarea class="form-control" id="commentInput[{{ $task->id }}]"
                                      name="comment[{{ $task->id }}]"></textarea>
                        </div>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary center submit-button">打卡</button>
            </form>
        </div>
    </div>

@endsection