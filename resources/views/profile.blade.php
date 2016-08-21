<!-- resources/views/profile.blade.php -->

@extends('layouts.navibar')

@section('title', '个人资料')

@section('content')

    <style>
        .container {
            width: 1010px;
            display: table;
            margin-left: auto;
            margin-right: auto;
            padding-left: 15px;
            padding-right: 15px;
        }

        .left-section {
            width: 250px;
            float: left;
            padding-left: 10px;
            padding-right: 10px;
        }

        .avatar {
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
        <div class="left-section">
            <img class="avatar" src="/img/0820_2.gif" alt="Cautious doodle"/>
            <br/><br/>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        我的昵称
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="form-horizontal">
                        <tbody id="nickname_ul"></tbody>
                        <tfoot>
                        <tr id="nickname_fm">
                            <td class="table-control">
                                {!! csrf_field() !!}
                                <input type="hidden" name="uid" value="{{ $uid }}"/>
                                <input class="form-control" type="text" name="nickname"/>
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
                                $("#nickname_ul").append("<tr><td class='table-control table-text'>" + dataObj[i].nickname + '</td><td class="table-control" id="nickname_del_id_' + dataObj[i].id + '">{{ csrf_field() }} {{ method_field("DELETE") }}<input class="btn btn-danger" type="button" onclick="delNicknameLi(' + dataObj[i].id + ')" value="删除" /></td></tr>');
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
    </div>
@endsection