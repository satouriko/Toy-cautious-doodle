<!-- resources/views/profile.blade.php -->

@extends('layouts.navibar')

@section('title', '个人资料')

@section('content')

    <style>
        .profile-container {
            width: 100%;
            max-width: 1010px;
            display: table;
            margin-left: auto;
            margin-right: auto;
            /*padding-left: 15px;
            padding-right: 15px;*/
        }

        .left-section {
            min-width: 250px;
            float: left;
            padding-left: 10px;
            padding-right: 10px;
        }

        .right-section {
            max-width: 750px;
            float: left;
            padding-left: 10px;
            padding-right: 10px;
        }

        .avatar {
            margin-left: auto;
            margin-right: auto;
            display: table;
            width: 230px;
            height: 230px;
            border-radius: 6px;
        }

        .table-control {
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 3px;
            padding-right: 3px;
        }

        .expand {
            width: 100%;
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

    <div class="profile-container">
        <div class="left-section col-xs-12 col-sm-4">
            <img class="avatar" src="/img/avatar.gif" alt="Cautious doodle"/>
            <br/><br/>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        我的昵称
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="form-horizontal center">
                        <tbody id="nickname_ul"></tbody>
                        <tfoot>
                        <tr id="nickname_fm">
                            <td class="table-control expand">
                                {!! csrf_field() !!}
                                <input class="form-control" type="text" name="nickname"
                                       onkeydown="if(event.keyCode==13){$('#nickname_smt').click()}"/>
                            </td>
                            <td class="table-control">
                                <input class="btn btn-primary" type="button" id="nickname_smt" value="添加"/>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <script>
                function loadNicknameUl() {
                    $.ajax({
                        type: "GET",
                        url: "/profile/nickname",
                        success: function (msg) {
                            $("#nickname_ul").empty();
                            $("#nickname_fm input[type='text']").val("");
                            var dataObj = eval("(" + msg + ")");
                            for (i in dataObj) {
                                $("#nickname_ul").append("<tr><td class='expand table-control table-text'>" + dataObj[i].nickname + '</td><td class="table-control" id="nickname_del_id_' + dataObj[i].id + '">{{ csrf_field() }} {{ method_field("DELETE") }}<input class="btn btn-danger" type="button" onclick="delNicknameLi(' + dataObj[i].id + ')" value="删除" /></td></tr>');
                            }
                        }
                    });
                }
                function delNicknameLi(id) {
                    var str_query = "#nickname_del_id_" + id.toString() + " input";
                    var str_data = $(str_query).map(function () {
                        return ($(this).attr("name") + '=' + $(this).val());
                    }).get().join("&");
                    $.ajax({
                        type: "POST",
                        url: "/profile/nickname/" + id.toString(),
                        data: str_data,
                        success: function (msg) {
                            loadNicknameUl();
                        }
                    })
                }
                $(document).ready(function () {
                    loadNicknameUl();
                    $("#nickname_smt").click(function () {
                        var str_data = $("#nickname_fm input").map(function () {
                            return ($(this).attr("name") + '=' + $(this).val());
                        }).get().join("&");
                        $.ajax({
                            type: "POST",
                            url: "/profile/nickname",
                            data: str_data,
                            success: function (msg) {
                                loadNicknameUl();
                            }
                        });
                    })
                })
            </script>

        </div>
        <div class="right-section col-xs-12 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        常规任务
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="form-horizontal center">
                        <tbody id="rgtask_ul"></tbody>
                        <tfoot>
                        <tr>
                            <td class="table-control" colspan="3">
                                <input class="btn btn-primary expand" type="button" id="rgtask_smt" value="添加一个任务"/>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <script>
                function loadRgtaskUl() {
                    $.ajax({
                        type: "GET",
                        url: "/profile/rgtask",
                        success: function (msg) {
                            $("#rgtask_ul").empty();
                            var dataObj = eval("(" + msg + ")");
                            for (i in dataObj) {
                                $("#rgtask_ul").append("<tr><td class='expand table-control table-text'>" + dataObj[i].title + '</td><td class="table-control"><input class="btn btn-primary" type="button" value="详情" onclick="showRgtaskEdit(' + dataObj[i].id + ')"/></td><td class="table-control" id="rgtask_del_id_' + dataObj[i].id + '">{{ csrf_field() }} {{ method_field("DELETE") }}<input class="btn btn-danger" type="button" onclick="delRgtaskLi(' + dataObj[i].id + ')" value="删除" /></td></tr>');
                            }
                        }
                    });
                }
                function showRgtaskEdit(id) {
                    $("#rgtaskModalTitle").html("任务详情");
                    $("#rgtask_fm input[type='text']").val("");
                    $("#rgtaskEditId").val(id);
                    $("#startdate").attr("disabled", true);
                    $("#updateRgtask_smt").text("修改");
                    $.ajax({
                        type: "GET",
                        url: "/profile/rgtask",
                        success: function (msg) {
                            var dataObj = eval("(" + msg + ")");
                            for (i in dataObj) {
                                if (dataObj[i].id == id) {
                                    $("#tasktitle").val(dataObj[i].title);
                                    $("#taskdesc").html(dataObj[i].description);
                                    $("#startdate").val(dataObj[i].startdate);
                                    $("#period").val(dataObj[i].period);
                                    $("#taskday").val(dataObj[i].activeday);
                                }
                            }
                        }
                    });
                    $("#rgtaskEditModal").modal('show');
                }
                function delRgtaskLi(id) {
                    var str_query = "#rgtask_del_id_" + id.toString() + " input";
                    var str_data = $(str_query).map(function () {
                        return ($(this).attr("name") + '=' + $(this).val());
                    }).get().join("&");
                    $.ajax({
                        type: "POST",
                        url: "/profile/rgtask/" + id.toString(),
                        data: str_data,
                        success: function (msg) {
                            loadRgtaskUl();
                        }
                    })
                }
                $(document).ready(function () {
                    loadRgtaskUl();
                    $("#rgtask_smt").click(function () {
                        $("#rgtaskModalTitle").html("新增任务");
                        $("#rgtask_fm input[type='text']").val("");
                        $("#startdate").removeAttr("disabled");
                        $("#updateRgtask_smt").text("提交");
                        $("#rgtaskEditModal").modal('show');
                    });
                    $("#updateRgtask_smt").click(function () {
                        if ($("#rgtaskModalTitle").html() == "新增任务") {
                            var str_data1 = $("#rgtask_fm input").map(function () {
                                return ($(this).attr("name") + '=' + $(this).val());
                            }).get().join("&");
                            var str_data2 = $("#rgtask_fm textarea").map(function () {
                                return ($(this).attr("name") + '=' + $(this).val());
                            }).get().join("&");
                            var str_data = str_data1 + '&' + str_data2;
                            $.ajax({
                                type: "POST",
                                url: "/profile/rgtask",
                                data: str_data,
                                success: function (msg) {
                                    $("#rgtaskEditModal").modal('hide');
                                    loadRgtaskUl();
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
                            var str_data3 = "_method=PUT";
                            var str_data = str_data1 + '&' + str_data2 + '&' + str_data3;
                            alert(str_data);
                            var id = $("#rgtaskEditId").val();
                            $.ajax({
                                type: "POST",
                                url: "/profile/rgtask/" + id.toString(),
                                data: str_data,
                                success: function (msg) {
                                    $("#rgtaskEditModal").modal('hide');
                                    loadRgtaskUl();
                                }
                            });
                        }
                    })
                })
            </script>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        我的签名
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="form-horizontal center">
                        <tbody id="signature_ul"></tbody>
                        <tfoot>
                        <tr id="signature_fm">
                            <td class="table-control expand" colspan="2">
                                {!! csrf_field() !!}
                                <input class="form-control" type="text" name="signature"
                                       onkeydown="if(event.keyCode==13){$('#signature_smt').click()}"/>
                            </td>
                            <td class="table-control">
                                <input class="btn btn-primary" type="button" id="signature_smt" value="添加"/>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <script>
                function loadSignatureUl() {
                    $.ajax({
                        type: "GET",
                        url: "/profile/signature",
                        success: function (msg) {
                            $("#signature_ul").empty();
                            $("#signature_fm input[type='text']").val("");
                            var dataObj = eval("(" + msg + ")");
                            for (i in dataObj) {
                                $("#signature_ul").append("<tr><td class='expand table-control table-text' id='signature_id_" + dataObj[i].id + "'>" + dataObj[i].signature + '</td><td class="table-control"><input class="btn btn-primary" type="button" value="修改" onclick="showSignatureEdit(' + dataObj[i].id + ')"/></td><td class="table-control" id="signature_del_id_' + dataObj[i].id + '">{{ csrf_field() }} {{ method_field("DELETE") }}<input class="btn btn-danger" type="button" onclick="delSignatureLi(' + dataObj[i].id + ')" value="删除" /></td></tr>');
                            }
                        }
                    });
                }
                function showSignatureEdit(id) {
                    var str_query = "#signature_id_" + id.toString();
                    var str = $(str_query).html();
                    $("#signatureEditId").val(id);
                    $("#signatureEditInput").val(str);
                    $("#signatureEditModal").modal('show');
                }
                function delSignatureLi(id) {
                    var str_query = "#signature_del_id_" + id.toString() + " input";
                    var str_data = $(str_query).map(function () {
                        return ($(this).attr("name") + '=' + $(this).val());
                    }).get().join("&");
                    $.ajax({
                        type: "POST",
                        url: "/profile/signature/" + id.toString(),
                        data: str_data,
                        success: function (msg) {
                            loadSignatureUl();
                        }
                    })
                }
                $(document).ready(function () {
                    loadSignatureUl();
                    $("#signature_smt").click(function () {
                        var str_data = $("#signature_fm input").map(function () {
                            return ($(this).attr("name") + '=' + $(this).val());
                        }).get().join("&");
                        $.ajax({
                            type: "POST",
                            url: "/profile/signature",
                            data: str_data,
                            success: function (msg) {
                                loadSignatureUl();
                            }
                        });
                    });
                    $("#updateSignature_smt").click(function () {
                        var str_data = $("#updateSignature_fm input").map(function () {
                            return ($(this).attr("name") + '=' + $(this).val());
                        }).get().join("&");
                        var id = $("#signatureEditId").val();
                        $.ajax({
                            type: "POST",
                            url: "/profile/signature/" + id.toString(),
                            data: str_data,
                            success: function (msg) {
                                $("#signatureEditModal").modal('hide');
                                loadSignatureUl();
                            }
                        });
                    })
                })
            </script>
        </div>
    </div>
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
    <!-- 模态框（Modal）signatureEditModal -->
    <div class="modal fade" id="signatureEditModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        修改签名
                    </h4>
                </div>
                <div class="modal-body">
                    <div id="updateSignature_fm" class="form-horizontal">
                        {{ csrf_field() }}
                        {{ method_field("PUT") }}
                        <input type="hidden" name="id" id="signatureEditId"/>
                        <input class="form-control expand" name="signature" id="signatureEditInput"
                               onkeydown="if(event.keyCode==13){$('#updateSignature_smt').click()}"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                    <button id="updateSignature_smt" type="button" class="btn btn-primary">
                        提交
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
@endsection