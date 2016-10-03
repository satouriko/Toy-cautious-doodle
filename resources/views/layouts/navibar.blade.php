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
    <!-- Navigation -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
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
                            <li><a href="#" id="ddm_adrgtask">创建常规任务</a></li>
                            <li><a href="#" id="ddm_adtptask">创建临时任务</a></li>
                            <li class="divider"></li>
                            <li><a href="#" id="ddm_tasksignadd">打卡</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            心情
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#" id="ddm_adessay">记录一条心情</a></li>
                            <li class="divider"></li>
                            <li><a href="/essay">心情板</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ $user }}
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/profile">个人资料</a></li>
                            <li class="divider"></li>
                            <li><a href="/auth/logout">注销</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script>
        $(document).ready(function () {
            $("#ddm_adrgtask").click(function () {
                $.ajax({
                    type: "GET",
                    url: "/task/family",
                    success: function (msg) {
                        $("#taskfamily").empty();
                        var dataObj = eval("(" + msg + ")");
                        for (i in dataObj) {
                            $("#taskfamily").append("<option value=" + dataObj[i].id + ">" + dataObj[i].title + '</option>');
                        }
                    }
                });
                $("#rgtaskModalTitle").html("新增任务");
                $("#rgtask_fm input[type='text']").val("");
                $("#rgtask_fm textarea").val("");
                $("#rgtask_fm input[type='date']").val("{{ $date_today }}");
                $("#rgtask_fm input[type='number']").val("");
                $("#startdate").removeAttr("disabled");
                $("#updateRgtask_smt").text("提交");
                $("#rgtaskEditModal").modal('show');
            });
            $("#ddm_adtptask").click(function () {
                $.ajax({
                    type: "GET",
                    url: "/task/family",
                    success: function (msg) {
                        $("#tp_taskfamily").empty();
                        var dataObj = eval("(" + msg + ")");
                        for (i in dataObj) {
                            $("#tp_taskfamily").append("<option value=" + dataObj[i].id + ">" + dataObj[i].title + '</option>');
                        }
                    }
                });
                $("#tptaskModalTitle").html("新增任务");
                $("#tptask_fm input[type='text']").val("");
                $("#tptask_fm textarea").val("");
                $("#tptask_fm input[type='date']").val("{{ $date_today }}");
                $("#updateTptask_smt").text("提交");
                $("#tptaskEditModal").modal('show');
            });
            $("#ddm_tasksignadd").click(function () {
                $.ajax({
                    type: "GET",
                    url: "/task/tasksign/check",
                    success: function (msg) {
                        if (msg == "signed") {
                            $("#tasksignAddModal").modal('show');
                        }
                        else {
                            location.href = "/task/tasksign/create";
                        }
                    }
                });
            });
            $("#ddm_adessay").click(function () {
                $("#updateEssay_fm textarea").val("");
                $("#essayEditModal").modal('show');
            });
            $("#updateRgtask_smt").click(function () {
                if ($("#rgtaskModalTitle").html() == "新增任务") {
                    var str_data1 = $("#rgtask_fm input").map(function () {
                        return ($(this).attr("name") + '=' + $(this).val());
                    }).get().join("&");
                    var str_data2 = $("#rgtask_fm textarea").map(function () {
                        return ($(this).attr("name") + '=' + $(this).val());
                    }).get().join("&");
                    var str_data3 = $("#rgtask_fm select").map(function () {
                        return ($(this).attr("name") + '=' + $(this).val());
                    }).get().join("&");
                    var str_data = str_data1 + '&' + str_data2 + '&' + str_data3;
                    $.ajax({
                        type: "POST",
                        url: "/task/rgtask",
                        data: str_data,
                        success: function (msg) {
                            $("#rgtaskEditModal").modal('hide');
                            //loadRgtaskUl();
                            location.reload();
                        }
                    });
                }
                else if ($("#rgtaskModalTitle").html() == "任务详情") {
                    var str_data1 = $("#rgtask_fm input").map(function () {
                        return ($(this).attr("name") + '=' + $(this).val());
                    }).get().join("&");
                    var str_data2 = $("#rgtask_fm textarea").map(function () {
                        return ($(this).attr("name") + '=' + $(this).val());
                    }).get().join("&");
                    var str_data4 = $("#rgtask_fm select").map(function () {
                        return ($(this).attr("name") + '=' + $(this).val());
                    }).get().join("&");
                    var str_data3 = "_method=PUT";
                    var str_data = str_data1 + '&' + str_data2 + '&' + str_data3 + '&' + str_data4;
                    var id = $("#rgtaskEditId").val();
                    $.ajax({
                        type: "POST",
                        url: "/task/rgtask/" + id.toString(),
                        data: str_data,
                        success: function (msg) {
                            $("#rgtaskEditModal").modal('hide');
                            loadRgtaskUl();
                        }
                    });
                }
            });
            $("#updateTptask_smt").click(function () {
                var str_data1 = $("#tptask_fm input").map(function () {
                    return ($(this).attr("name") + '=' + $(this).val());
                }).get().join("&");
                var str_data2 = $("#tptask_fm textarea").map(function () {
                    return ($(this).attr("name") + '=' + $(this).val());
                }).get().join("&");
                var str_data3 = $("#tptask_fm select").map(function () {
                    return ($(this).attr("name") + '=' + $(this).val());
                }).get().join("&");
                var str_data = str_data1 + '&' + str_data2 + '&' + str_data3;
                $.ajax({
                    type: "POST",
                    url: "/task/tptask",
                    data: str_data,
                    success: function (msg) {
                        $("#tptaskEditModal").modal('hide');
                        location.reload();
                    }
                });
            });
            $("#delTasksign_smt").click(function () {
                var str_data = $("#delTasksign_fm input").map(function () {
                    return ($(this).attr("name") + '=' + $(this).val());
                }).get().join("&");
                $.ajax({
                    type: "POST",
                    url: "/task/tasksign/reset",
                    data: str_data,
                    success: function (msg) {
                        $("#tasksignAddModal").modal('hide');
                        location.reload();
                    }
                });
            });
            $("#updateEssay_smt").click(function () {
                var str_data1 = $("#updateEssay_fm input").map(function () {
                    return ($(this).attr("name") + '=' + $(this).val());
                }).get().join("&");
                var str_data2 = $("#updateEssay_fm textarea").map(function () {
                    return ($(this).attr("name") + '=' + $(this).val());
                }).get().join("&");
                var str_data = str_data1 + '&' + str_data2;
                $.ajax({
                    type: "POST",
                    url: "/essay",
                    data: str_data,
                    success: function (msg) {
                        $("#essayEditModal").modal('hide');
                        location.reload();
                    }
                });
            });
        })
    </script>
    <!-- 模态框（Modal）RgtaskEditModal -->
    <div class="modal fade" id="rgtaskEditModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="rgtaskModalTitle">
                    </h4>
                </div>
                <div class="modal-body">
                    <div id="rgtask_fm" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" id="rgtaskEditId"/>
                        <div class="form-group">
                            <label for="tasktitle" class="col-sm-2 col-sm-offset-1 control-label">任务名称</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="tasktitle" name="title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="taskdesc" class="col-sm-2 col-sm-offset-1 col-xs-12 control-label">任务描述</label>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="taskdesc" name="description"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="taskfamily" class="col-sm-2 col-sm-offset-1 col-xs-12 control-label">任务分类</label>
                            <div class="col-sm-7">
                                <select class="form-control" id="taskfamily" name="family_id">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tasktype" class="col-sm-2 col-sm-offset-1 col-xs-12 control-label">任务类型</label>
                            <div class="col-sm-7">
                                <select class="form-control" id="tasktype" name="type">
                                    <option value="state">状态类</option>
                                    <option value="activity">任务类</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="startdate" class="col-sm-2 col-sm-offset-1 control-label">起始日</label>
                            <div class="col-sm-6">
                                <input type="date" class="form-control" id="startdate" name="startdate">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="period" class="col-sm-2 col-sm-offset-1 control-label">周期</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" id="period" name="period" placeholder="天">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="taskday" class="col-sm-2 col-sm-offset-1 control-label">任务日</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="taskday" name="activeday"
                                       placeholder="请填入数字 用英文逗号分隔">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-1">
                                例如：
                                <ul>
                                    <li>创建一个每日任务 则周期为1 任务日为1</li>
                                    <li>创建一个任务为每周一、周三、周五 若起始日为周一 则周期为7 任务日为1,3,5</li>
                                    <li>创建一个任务为单周周二、周四 若起始日为单周周一 则周期为14 任务日为2,4</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                    <button id="updateRgtask_smt" type="button" class="btn btn-primary">
                        提交
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
    <!-- 模态框（Modal）TptaskEditModal -->
    <div class="modal fade" id="tptaskEditModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="tptaskModalTitle">
                    </h4>
                </div>
                <div class="modal-body">
                    <div id="tptask_fm" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="tp_tasktitle" class="col-sm-2 col-sm-offset-1 control-label">任务名称</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="tp_tasktitle" name="title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="taskfamily" class="col-sm-2 col-sm-offset-1 col-xs-12 control-label">任务分类</label>
                            <div class="col-sm-7">
                                <select class="form-control" id="tp_taskfamily" name="family_id">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tp_taskdesc"
                                   class="col-sm-2 col-sm-offset-1 col-xs-12 control-label">任务描述</label>
                            <div class="col-sm-7">
                                <textarea class="form-control" id="tp_taskdesc" name="description"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tp_startdate" class="col-sm-2 col-sm-offset-1 control-label">任务日</label>
                            <div class="col-sm-6">
                                <input type="date" class="form-control" id="tp_startdate" name="startdate">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                    <button id="updateTptask_smt" type="button" class="btn btn-primary">
                        提交
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
    <!-- 模态框（Modal）tasksignAddModal -->
    <div class="modal fade" id="tasksignAddModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        打卡
                    </h4>
                </div>
                <div class="modal-body">
                    <p class="center">您今天已打卡。</p>
                    <div id="delTasksign_fm">
                        {{ csrf_field() }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="delTasksign_smt" type="button" class="btn btn-danger">
                        撤销今天打卡
                    </button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
    <!-- 模态框（Modal）essayEditModal -->
    <div class="modal fade" id="essayEditModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        写心情
                    </h4>
                </div>
                <div class="modal-body">
                    <div id="updateEssay_fm" class="form-horizontal">
                        {{ csrf_field() }}
                        <textarea class="form-control expand" name="essaycontent" id="essayEditInput"
                                  rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                    <button id="updateEssay_smt" type="button" class="btn btn-primary">
                        提交
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
@endsection
