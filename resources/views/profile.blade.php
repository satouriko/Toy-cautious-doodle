<!-- resources/views/profile.blade.php -->

@extends('layouts.navibar')

@section('title', '个人资料')

@section('content')


    <div class="container">
        <div class="row" style="margin-top: 20px">
            <center><img src="/img/0820_2.gif" alt="Cautious doodle" /></center>
        </div>
        <div class="row">
            <center><h1>Toy Cautious Doodle</h1></center>
        </div>
        <script>
            function loadNicknameUl () {
                $.ajax({
                    type: "GET",
                    url: "/profile/nickname",
                    success: function (msg) {
                        $("#nickname_ul").empty();
                        $("#nickname_fm input[type='text']").val("");
                        var dataObj=eval("("+msg+")");
                        for(i in dataObj)
                        {
                            $("#nickname_ul").append("<tr><td>" + dataObj[i].nickname + '</td><td><form id="nickname_del_id_'+dataObj[i].id+'">{{ csrf_field() }} {{ method_field("DELETE") }}<button onclick="delNicknameLi('+dataObj[i].id+')">删除</button></form></td></tr>');
                        }
                    }
                });
            }
            function delNicknameLi(id) {
                var str_query="#nickname_del_id_"+id.toString()+" input";
                var str_data=$(str_query).map(function(){
                    return ($(this).attr("name")+'='+$(this).val());
                }).get().join("&") ;
                $.ajax({
                    type: "POST",
                    url: "/profile/nickname/" + id.toString(),
                    data: str_data,
                    success: function (msg) {
                        loadNicknameUl();
                    }
                })
            }
            $(document).ready(function(){
                loadNicknameUl();
                $("#nickname_smt").click(function(){
                    var str_data=$("#nickname_fm input").map(function(){
                        return ($(this).attr("name")+'='+$(this).val());
                    }).get().join("&") ;
                    $.ajax({
                        type: "POST",
                        url: "/profile/nickname",
                        data: str_data,
                        success: function(msg){
                            loadNicknameUl();
                        }
                    });
                })
            })
        </script>
        <div id="nickname_ul"></div>
                <form id="nickname_fm" onkeydown="if(event.keyCode==13){$('#nickname_smt').click(); return false;}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="uid" value="{{ $uid }}"/>
                    <input type="text" name="nickname" />
                    </td>
                    <td>
                        <input type="button" id="nickname_smt" value="add" />
                    </td>
                </form>

    </div>

@endsection