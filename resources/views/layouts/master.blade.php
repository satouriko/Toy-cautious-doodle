<!-- 文件保存于 resources/views/layouts/master.blade.php -->

<html>
<head>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap.min.css') }}">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
    <title>@yield('title') - Toy Cautious Doodle</title>
</head>
<body>

@yield('navibar')

<div class="container">
    @yield('content')
</div>
</body>
</html>