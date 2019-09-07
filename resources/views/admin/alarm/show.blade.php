@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
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
                        <h5>告警详情</h5>
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
                                <label class="col-sm-2 control-label">告警设备序列号</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static deviceSerial">{{ $alarms->device_serial }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">消息ID</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static alarmId">{{ $alarms->alarm_id }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">告警源名称</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static channelName">{{ $alarms->channel_name }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">告警类型</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static alarmTyspe">
                                        @if($alarms->alarm_type == 'enterareadetection')
                                            进入区域
                                        @elses
                                            物品遗留
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">告警开始时间</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static alarmStart">{{ $alarms->alarm_start }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">告警网格</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static number">{{ $alarms->number }}号网格</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">告警位置(经纬度)</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static"><span class="lng">{{ $alarms->longitude }}</span>---<span class="lat">{{ $alarms->latitude }}</span></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">告警图片</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static pic">
                                        <a class="fancybox" href="{{ $alarms->alarm_pic_url }}" title="图片">
                                            <img alt="image" src="{{ $alarms->alarm_pic_url }}" />
                                        </a>
                                        {{--<image class="image" width="120" src="{{ $alarms->alarm_pic_url }}" />--}}
                                    </p>

                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </form>
                        {{--<div class="iframe">--}}
                            {{--<iframe id="iframe" src="https://open.ys7.com/jssdk/monitor.html"></iframe>--}}
                        {{--</div>--}}
                        <div class="jiankong">
                            <p>ezopen://open.ys7.com/D35853947/1.hd.live</p>
                            <p>at.1bpbsy1q7s63s782blkzfgdf2pzzz8vx-8ab77e2v1w-0g54vuc-bibmlvmor</p>
                        </div>
                        <div id="allmap"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Chosen -->
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>

    <script src="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.js') }}"></script>

    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G"></script>
@endsection

@section('javascript')
    <script>
        var deviceSerial = $('.deviceSerial'); // 设备序列号
        var alarmType = $('.alarmType'); // 告警类型
        var alarmStart = $('.alarmStart'); // 告警开始时间
        // 百度地图API功能
        var sContent = "<div class='iframe' style='width:800px; height: 600px; margin-top: -50px;'>" +
            "<iframe style='width:800px; height: 600px;' src='https://open.ys7.com/jssdk/monitor.html'></iframe>" +
            "</div>";
        var lng = $('.lng');
        var lat = $('.lat');
        console.log(lng.text());
        console.log(lat.text());
        var map = new BMap.Map("allmap");
        var point = new BMap.Point(lng.text(), lat.text());
        var marker = new BMap.Marker(point);
        var infoWindow = new BMap.InfoWindow(sContent);  // 创建信息窗口对象
        map.centerAndZoom(point, 15);
        map.addOverlay(marker);
        marker.addEventListener("click", function(){
            this.openInfoWindow(infoWindow);
            //图片加载完毕重绘infowindow
            document.getElementById('imgDemo').onload = function (){
                infoWindow.redraw();   //防止在网速较慢，图片未加载时，生成的信息框高度比图片的总高度小，导致图片部分被隐藏
            }
        });


        $(document).ready(function () {
            $('.fancybox').fancybox({
                openEffect: 'none',
                closeEffect: 'none'
            });
        });
    </script>
@endsection
