@extends('admin.common.app')

@section('styles')
    <!-- iCheck -->
    <link href="{{ asset('assets/admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet">
    <style>
        body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
        #allmap{width:100%;height:600px;}
        p{margin-left:5px; font-size:14px;}
        #iframe{width:100%; height: 600px;}
    </style>
@endsection

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>群众上报任务详情</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="form_basic.html#">选项1</a>
                                </li>
                                <li><a href="form_basic.html#">选项2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <form method="get" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">问题</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static deviceSerial">{{ $ret->matter->title }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">问题图片</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static channelName">
                                        <a class="fancybox" id="img" href="{{ $ret->matter->image }}" >
                                            <img src="{{ $ret->matter->image }}"  style="width: 150px;" />
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">问题描述</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static deviceSerial">{{ $ret->matter->content }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">办结时限</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static deviceSerial">{{ $ret->matter->time_limit }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">执行人</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static alarmId">{{ $ret->user->name }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">现场图片</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static channelName">
                                        <a class="fancybox" id="img" href="{{ $ret->see_image }}" >
                                            <img src="{{ $ret->see_image }}"  style="width: 150px;" />
                                        </a>
                                        @foreach($ret->getImagesAttributes() as $image)
                                            <a class="fancybox" id="img" href="{{ $image }}" >
                                                <img src="{{ $image }}"  style="width: 150px;" />
                                            </a>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">处理信息</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static alarmTyspe">
                                        {{ $ret->information }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">时间</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static alarmStart">{{ $ret->created_at }}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <!-- iCheck -->
    <script src="{{ asset('assets/admin/js/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- Fancy box -->
    <script src="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.js') }}"></script>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
            $('.fancybox').fancybox({
                openEffect: 'none',
                closeEffect: 'none'
            });
            function showImg(){
                $('#img').click();
            }
        });
    </script>
@endsection
