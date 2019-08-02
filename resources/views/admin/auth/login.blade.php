<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>公司考勤管理后台</title>
    <meta name="keywords" content="三晖科技">
    <meta name="description" content="三晖科技">

    <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}"> <link href="{{ asset('assets/admin/css/bootstrap.min.css?v=3.3.6') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/font-awesome.css?v=4.4.0') }}" rel="stylesheet">

    <link href="{{ asset('assets/admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/style.css?v=4.1.0') }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">SH</h1>

        </div>
        <h3>欢迎使用</h3>

        <form class="m-t" role="form" action="{{ route('admin.login') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="tel" class="form-control" placeholder="用户名" required="" name="phone">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="密码" required="" name="password">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>

            <div>Copyright &copy;2019 苏州三晖信息科技有限公司 版权所有
                <p><a href="http://www.beian.miit.gov.cn" target="_blank">备案号：苏ICP备18055553号-3</a></p>
            </div>

            {{--<p class="text-muted text-center"> <a href="login.html#"><small>忘记密码了？</small></a> | <a href="register.html">注册一个新账号</a>
            </p>--}}

        </form>
    </div>
</div>

<!-- 全局js -->
<script src="{{ asset('assets/admin/js/jquery.min.js?v=2.1.4') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap.min.js?v=3.3.6') }}"></script>

</body>

</html>
