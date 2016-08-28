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

        .text-center td {
            text-align: center;
            vertical-align: middle !important;
        }

        .text-center th {
            text-align: center;
            vertical-align: middle !important;
        }

        .expand {
            width: 100%;
        }

        label {
            font-size: 16px;
            /*color: #fe8f81;*/
        }

        p {
            font-size: 18px;
            color: #777777;
            text-align: center;
        }

        .little-table {
            border-collapse: separate;
            border-spacing: 2px;
        }

    </style>

    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    打卡记录
                </h3>
            </div>
            <div id="tasksign_ul" class="panel-body table-responsive">
                <table class="form-horizontal center">
                    <tbody></tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

    <script>
        function showDetails(reason, comment) {
            reason = decodeURI(reason);
            comment = decodeURI(comment);
            $('#reason_p').text(reason);
            $('#comment_p').text(comment);
            $('#showDetailModal').modal('show');
        }
        function showTptask(id, description) {
            description = decodeURI(description);
            $('#delTptaskId').val(id);
            $('#description_p').text(description);
            $('#delTptask_smt').attr('style', 'display: inline-block');
            $('#showTptaskModal').modal('show');
        }
        function showTptask_nodel(description) {
            description = decodeURI(description);
            $('#delTptaskId').val();
            $('#description_p').text(description);
            $('#delTptask_smt').attr('style', 'display: none');
            $('#showTptaskModal').modal('show');
        }
        $(document).ready(function () {
            $("#delTptask_smt").click(function () {
                var str_data = $("#delTptask_fm input").map(function () {
                    return ($(this).attr("name") + '=' + $(this).val());
                }).get().join("&");
                var id = $('#delTptaskId').val();
                $.ajax({
                    type: "POST",
                    url: "/task/tptask/" + id,
                    data: str_data,
                    success: function (msg) {
                        $('#showTptaskModal').modal('hide');
                        location.reload();
                    }
                });
            });
            $.ajax({
                type: "GET",
                url: "/task/tasksign",
                success: function (msg) {
                    $("#tasksign_ul").empty();
                    var dataObj = eval("(" + msg + ")");
                    for (i in dataObj) {
                        var append_str = '<table class="text-center table table-bordered table-striped form-horizontal expand">';
                        append_str += '<tr>';
                        append_str += '<th>日期</th>';
                        for (j in dataObj[i].header) {
                            append_str += '<th>' + dataObj[i].header[j].task.title + '</th>';
                        }
                        append_str += '<th>临时任务</th>';
                        append_str += '</tr>';
                        for (day_loop in dataObj[i].tasksigns) {
                            append_str += '<tr>';
                            append_str += '<td>' + day_loop + '</td>';
                            for (j in dataObj[i].header) {
                                var flag = false;
                                for (tasksign_loop in dataObj[i].tasksigns[day_loop]) {
                                    if (dataObj[i].tasksigns[day_loop][tasksign_loop].task_id == dataObj[i].header[j].task_id) {
                                        append_str += '<td>';
                                        append_str += '<button class="btn expand ';
                                        if (dataObj[i].tasksigns[day_loop][tasksign_loop].grade != 'Pending') {
                                            switch (dataObj[i].tasksigns[day_loop][tasksign_loop].grade) {
                                                case "Checked":
                                                    append_str += 'btn-success';
                                                    break;
                                                case "Unchecked":
                                                    append_str += 'btn-danger';
                                                    break;
                                                case "Delayed":
                                                    append_str += 'btn-warning';
                                                    break;
                                                case "Cancelled":
                                                    append_str += 'btn-warning';
                                                    break;
                                            }
                                            append_str += '" onclick="showDetails(\'' + encodeURI(dataObj[i].tasksigns[day_loop][tasksign_loop].reason) + '\',\'' + encodeURI(dataObj[i].tasksigns[day_loop][tasksign_loop].comment) + '\')">';
                                        }
                                        else {
                                            append_str += 'btn-info">'
                                        }
                                        append_str += dataObj[i].tasksigns[day_loop][tasksign_loop].grade;
                                        append_str += '</button>';
                                        append_str += '</td>';
                                        flag = true;
                                        break;
                                    }
                                }
                                if (!flag) {
                                    append_str += '<td></td>';
                                }
                            }
                            append_str += '<td><table class="expand little-table">';
                            for (tasksign_loop in dataObj[i].tasksigns[day_loop]) {
                                var flag = false;
                                for (j in dataObj[i].header) {
                                    if (dataObj[i].tasksigns[day_loop][tasksign_loop].task_id == dataObj[i].header[j].task_id)
                                        flag = true;
                                }
                                if (!flag) {
                                    append_str += '<tr>';
                                    append_str += '<td><button class="btn btn-link"';
                                    if (dataObj[i].tasksigns[day_loop][tasksign_loop].grade != 'Pending') {
                                        append_str += 'onclick="showTptask_nodel(\'' + encodeURI(dataObj[i].tasksigns[day_loop][tasksign_loop].task.description) + '\')">';

                                    }
                                    else {
                                        append_str += 'onclick="showTptask(' + dataObj[i].tasksigns[day_loop][tasksign_loop].task_id + ',\'' + encodeURI(dataObj[i].tasksigns[day_loop][tasksign_loop].task.description) + '\')">';
                                    }
                                    append_str += dataObj[i].tasksigns[day_loop][tasksign_loop].task.title;
                                    append_str += '</button></td><td>';
                                    append_str += '<button class="btn expand ';
                                    if (dataObj[i].tasksigns[day_loop][tasksign_loop].grade != 'Pending') {
                                        switch (dataObj[i].tasksigns[day_loop][tasksign_loop].grade) {
                                            case "Checked":
                                                append_str += 'btn-success';
                                                break;
                                            case "Unchecked":
                                                append_str += 'btn-danger';
                                                break;
                                            case "Delayed":
                                                append_str += 'btn-warning';
                                                break;
                                            case "Cancelled":
                                                append_str += 'btn-warning';
                                                break;
                                        }
                                        append_str += '" onclick="showDetails(\'' + encodeURI(dataObj[i].tasksigns[day_loop][tasksign_loop].reason) + '\',\'' + encodeURI(dataObj[i].tasksigns[day_loop][tasksign_loop].comment) + '\')">';
                                    }
                                    else {
                                        append_str += 'btn-info">'
                                    }
                                    append_str += dataObj[i].tasksigns[day_loop][tasksign_loop].grade;
                                    append_str += '</button>';
                                    append_str += '</td></tr>';
                                }
                            }
                            append_str += '</table></td>';
                        }
                        append_str += '</table>';
                        $("#tasksign_ul").append(append_str);
                    }
                }
            });
        })
    </script>
    <!-- 模态框（Modal）showDetailModal -->
    <div class="modal fade" id="showDetailModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        打卡详情
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <label>评论</label>
                        <p id="comment_p"></p>
                        <label>（未完成/延期/取消）原因</label>
                        <p id="reason_p"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
    <!-- 模态框（Modal）showTptaskModal -->
    <div class="modal fade" id="showTptaskModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        任务详情
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <label>任务描述</label>
                        <p id="description_p"></p>
                    </div>
                </div>
                <div id="delTptask_fm">
                    {{ csrf_field() }}
                    {{ method_field("DELETE") }}
                    <input type="hidden" name="id" id="delTptaskId"/>
                </div>
                <div class="modal-footer">
                    <button id="delTptask_smt" type="button" class="btn btn-danger">
                        删除此任务
                    </button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
@endsection