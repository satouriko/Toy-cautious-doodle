<!-- 文件 resources/views/emails/password.blade.php -->

点击此处重置你的密码：{{ url('password/reset/'.$token) }}