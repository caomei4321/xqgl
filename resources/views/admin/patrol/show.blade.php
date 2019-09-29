@extends('admin.common.app')

@section('styles')
    <!-- iCheck -->
    <link href="{{ asset('assets/admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/admin/css/animate.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/admin/css/plugins/blueimp/css/blueimp-gallery.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>巡查信息</h5>
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
                        <div class="form-group">
                            <label class="col-sm-1 control-label">姓名</label>

                            <div class="col-sm-2">
                                <p class="form-control-static">{{ $patrol->user->name }}</p>
                            </div>
                            <label class="col-sm-1 control-label">总里程</label>
                            <div class="col-sm-2">
                                <p class="form-control-static">{{ $tracks->distance }}</p>
                            </div>
                            <label class="col-sm-1 control-label">开始时间</label>
                            <div class="col-sm-2">
                                <p class="form-control-static">{{ $patrol->created_at }}</p>
                            </div>
                            <label class="col-sm-1 control-label">结束时间</label>

                            <div class="col-sm-2">
                                <p class="form-control-static">{{ $patrol->end_at }}</p>
                            </div>


                        </div>

                </div>
            </div>
        </div>
    </div>
    {{--<div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>巡查路线</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="graph_flot.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="graph_flot.html#">选项1</a>
                            </li>
                            <li><a href="graph_flot.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div style="height:600px" id="patrol1"></div>
                    <button class="btn btn-info " type="button" onclick="fullScreen()"><i class="fa fa-paste"></i> 全屏展示</button>
                </div>
            </div>
        </div>
    </div>--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 原始轨迹</a>
                    </li>
                    <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false"> 纠偏轨迹</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <div style="height:600px" id="patrol"></div>
                            <button class="btn btn-info " type="button" onclick="fullScreen()"><i class="fa fa-paste"></i> 全屏展示</button>
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div style="height:600px" id="process-patrol"></div>
                            <button class="btn btn-info " type="button" onclick="processFullScreen()"><i class="fa fa-paste"></i> 全屏展示</button>
                        </div>
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

    <!-- blueimp gallery -->
    <script src="{{ asset('assets/admin/js/plugins/blueimp/jquery.blueimp-gallery.min.js') }}"></script>

    <!-- 百度地图js -->
    <script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=F6subxg8j4A1f28mhgryfUs0dxO8PQ8o"></script>
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

            var map = new BMap.Map("patrol");
            var point = new BMap.Point({{ $tracks->start_point->longitude }}, {{ $tracks->start_point->latitude }});
            map.centerAndZoom(point, 15);
            map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
            /*map.setMapStyleV2({
                styleId: '4164dc3852e0db5655f892b8f46d98d6'
            });*/

            var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
            var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
            var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮

            var sy = new BMap.Symbol(BMap_Symbol_SHAPE_BACKWARD_OPEN_ARROW, {
                scale: 0.6,
                strokeColor: '#fff',
                strokeWeight: '2',
            });

            var icons = new BMap.IconSequence(sy, '10', '30');

            // 创建polyline对象

            var pois = [];
            var markerPoints = [];

            var  lastTime = 0;
            @foreach($tracks->points as $point)
                    @foreach($patrolMatters as $patrolMatter)
                        if ({{ strtotime($patrolMatter->created_at) }} > lastTime && {{ strtotime($patrolMatter->created_at) }} < {{ $point->loc_time }}) {

                            markerPoints.push(new BMap.Point({{$point->longitude}}, {{$point->latitude}}));
                        }
                    @endforeach
                    pois.push(new BMap.Point({{$point->longitude}}, {{$point->latitude}}));
                    lastTime = {{ $point->loc_time }}
            @endforeach

            var polyline = new BMap.Polyline(pois, {
                enableEditing: false,
                enableClicking: true,
                icons: [icons],
                strokeWeight: '8',
                strokeOpacity: '0.8',
                strokeColor: "#18a45b"
            });


            // 添加轨迹线
            map.addOverlay(polyline);


            var myIcon = new BMap.Icon("/assets/admin/img/icon_image.png", new BMap.Size(28,50));

            var markers = [];
            var sContent = [];
            var infoWindow = [];
            @foreach($patrolMatters as $patrolMatter)
                var point = markerPoints[{{$loop->index}}];

                markers[{{ $loop->index }}] = new BMap.Marker(point,{icon:myIcon});  // 创建标注
                map.addOverlay(markers[{{ $loop->index }}]);              // 将标注添加到地图中

                var patrolTitle = "{{ $patrolMatter->title }}";
                var patrolContent = "{{ $patrolMatter->content }}";
                var patrolImg = "{{ $patrolMatter->image }}";
                var patrolTime = "{{ $patrolMatter->created_at }}";


                //窗口信息
                sContent[{{ $loop->index }}] =
                    "<h4 style='margin-left: 13px; margin-bottom: 5px;'>"+ "处理记录" +" </h4></br>" +
                    "<span style='margin: 0 12px;'>事件标题 :" + patrolTitle + "</span></br>" +
                    "<p style='margin: 0 12px; font-size: 12px; color: rgb(77,77,77);'>"+"处理时间："+  patrolTime +"</p></br>" +
                    "<p style='margin: 0 12px; font-size: 12px; color: rgb(77,77,77);'>"+"问题描述："+  patrolContent +"</p></br>" +
                    "<img style='float: right;' id='patrol_img' src='" + patrolImg + "' width='300' title='处理记录'/>" +
                    "<p style=' margin: 0 12px;font-size: 12px; color: rgb(127,127,127); overflow: hidden;text-overflow: ellipsis;'>";
                infoWindow[{{ $loop->index }}] = new BMap.InfoWindow(sContent[{{ $loop->index }}]);  // 创建信息窗口对象

                // 监听标注点击事件
                markers[{{ $loop->index }}].addEventListener("click", function(){
                    this.openInfoWindow(infoWindow[{{ $loop->index }}]);
                    //图片加载完毕重绘infowindow
                    /*document.getElementById('patrol_img').onload = function (){
                        infoWindow.redraw();   //防止在网速较慢，图片未加载时，生成的信息框高度比图片的总高度小，导致图片部分被隐藏
                    }*/
                });
            @endforeach
/*===============================================================================================================*/

            var processMap = new BMap.Map("process-patrol");
            var point = new BMap.Point({{ $processTracks->start_point->longitude }}, {{ $processTracks->start_point->latitude }});
            processMap.centerAndZoom(point, 15);
            processMap.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放


            //var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
            //var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
            //var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮

            var sy = new BMap.Symbol(BMap_Symbol_SHAPE_BACKWARD_OPEN_ARROW, {
                scale: 0.6,
                strokeColor: '#fff',
                strokeWeight: '2',
            });

            var icons = new BMap.IconSequence(sy, '10', '30');

            // 创建polyline对象

            var processPois = [];
            var processMarkerPoints = [];

            var  lastTime = 0;
            @foreach($processTracks->points as $point)
                    @foreach($patrolMatters as $patrolMatter)
                        if ({{ strtotime($patrolMatter->created_at) }} > lastTime && {{ strtotime($patrolMatter->created_at) }} < {{ $point->loc_time }}) {
                            processMarkerPoints.push(new BMap.Point({{$point->longitude}}, {{$point->latitude}}));
                        }
                    @endforeach
                    processPois.push(new BMap.Point({{$point->longitude}}, {{$point->latitude}}));
                    lastTime = {{ $point->loc_time }}
            @endforeach

            var processPolyline = new BMap.Polyline(processPois, {
                    enableEditing: false,
                    enableClicking: true,
                    icons: [icons],
                    strokeWeight: '8',
                    strokeOpacity: '0.8',
                    strokeColor: "#18a45b"
                });


            // 添加轨迹线
            processMap.addOverlay(processPolyline);


            var myIcon = new BMap.Icon("/assets/admin/img/icon_image.png", new BMap.Size(28,50));

            var processMarkers = [];
            var processContent = [];
            var processInfoWindow = [];
            @foreach($patrolMatters as $patrolMatter)
                var point = processMarkerPoints[{{$loop->index}}];

                processMarkers[{{ $loop->index }}] = new BMap.Marker(point,{icon:myIcon});  // 创建标注
                processMap.addOverlay(processMarkers[{{ $loop->index }}]);              // 将标注添加到地图中

                var patrolTitle = "{{ $patrolMatter->title }}";
                var patrolContent = "{{ $patrolMatter->content }}";
                var patrolImg = "{{ $patrolMatter->image }}";
                var patrolTime = "{{ $patrolMatter->created_at }}";

                //窗口信息
                processContent[{{ $loop->index }}] =
                    "<h4 style='margin-left: 13px; margin-bottom: 5px;'>"+ "处理记录" +" </h4></br>" +
                    "<span style='margin: 0 12px;'>事件标题 :" + patrolTitle + "</span></br>" +
                    "<p style='margin: 0 12px; font-size: 12px; color: rgb(77,77,77);'>"+"处理时间："+  patrolTime +"</p></br>" +
                    "<p style='margin: 0 12px; font-size: 12px; color: rgb(77,77,77);'>"+"问题描述："+  patrolContent +"</p></br>" +
                    "<img style='float: right;' id='patrol_img' src='" + patrolImg + "' width='300' title='处理记录'/>" +
                    "<p style=' margin: 0 12px;font-size: 12px; color: rgb(127,127,127); overflow: hidden;text-overflow: ellipsis;'>";

                processInfoWindow[{{ $loop->index }}] = new BMap.InfoWindow(processContent[{{ $loop->index }}]);  // 创建信息窗口对象

                // 监听标注点击事件
                processMarkers[{{ $loop->index }}].addEventListener("click", function(){
                    this.openInfoWindow(processInfoWindow[{{ $loop->index }}]);
                    //图片加载完毕重绘infowindow
                    /*document.getElementById('patrol_img').onload = function (){
                        infoWindow.redraw();   //防止在网速较慢，图片未加载时，生成的信息框高度比图片的总高度小，导致图片部分被隐藏
                    }*/
                });
            @endforeach

            {{--@if(isset($patrol->patrol_matter))
            var point = new BMap.Point({{ $patrol->patrol_matter->longitude }}, {{ $patrol->patrol_matter->latitude }});
            var myIcon = new BMap.Icon("/assets/admin/img/icon_image.png", new BMap.Size(28,50));
            var marker2 = new BMap.Marker(point,{icon:myIcon});  // 创建标注
            map.addOverlay(marker2);              // 将标注添加到地图中

            var patrolImg = "{{ $patrol->patrol_matter->image }}";
            var patrolTime = "{{ $patrol->patrol_matter->created_at }}";
            //窗口信息
            var sContent =
                "<h4 style='margin-left: 13px; margin-bottom: 5px;'>"+ "处理记录" +" </h4>" +
                "<img style='float: right;' id='patrol_img' src='" + patrolImg + "' width='139' height='104' title='处理记录'/>" +
                "<p style='margin: 0 12px; font-size: 12px; color: rgb(77,77,77);'>"+"处理时间："+  patrolTime +"</p>" +
                "<p style=' margin: 0 12px;font-size: 12px; color: rgb(127,127,127); overflow: hidden;text-overflow: ellipsis;'>";
            var infoWindow = new BMap.InfoWindow(sContent);  // 创建信息窗口对象

            // 监听标注点击事件
            marker2.addEventListener("click", function(){
                this.openInfoWindow(infoWindow);
                //图片加载完毕重绘infowindow
                document.getElementById('patrol_img').onload = function (){
                    infoWindow.redraw();   //防止在网速较慢，图片未加载时，生成的信息框高度比图片的总高度小，导致图片部分被隐藏
                }
            });
            @endif--}}
            // 添加标注
            function addMarker(point,label){
                var marker = new BMap.Marker(point);
                map.addOverlay(marker);
                marker.setLabel(label);
            }


            // var point = new BMap.Point(120.76938270267108, 31.279379881924105);

            //var marker = new BMap.Marker(point);
            //map.addOverlay(marker);
            add_control();

            //marker.addEventListener("click",showImg);

            // 添加标注
            // function addMarker(point,label){
            //     var marker = new BMap.Marker(point);
            //     map.addOverlay(marker);
            //     marker.setLabel(label);
            // }

            //添加控件和比例尺
            function add_control(){
                map.addControl(top_left_control);
                map.addControl(top_left_navigation);
                map.addControl(top_right_navigation);
            }


        });

        // 地图全屏
        function fullScreen() {
            var el = document.getElementById("patrol");
            var rfs = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullScreen;
            if(typeof rfs != "undefined" && rfs) {
                rfs.call(el);
            } else if(typeof window.ActiveXObject != "undefined") {
                //for IE，这里其实就是模拟了按下键盘的F11，使浏览器全屏
                var wscript = new ActiveXObject("WScript.Shell");
                if(wscript != null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }

        function processFullScreen() {
            var el = document.getElementById("process-patrol");
            var rfs = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullScreen;
            if(typeof rfs != "undefined" && rfs) {
                rfs.call(el);
            } else if(typeof window.ActiveXObject != "undefined") {
                //for IE，这里其实就是模拟了按下键盘的F11，使浏览器全屏
                var wscript = new ActiveXObject("WScript.Shell");
                if(wscript != null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }

        function exitFullScreen() {
            var el = document;
            var cfs = el.cancelFullScreen || el.webkitCancelFullScreen ||
                el.mozCancelFullScreen || el.exitFullScreen;
            if(typeof cfs != "undefined" && cfs) {
                cfs.call(el);
            } else if(typeof window.ActiveXObject != "undefined") {
                //for IE，这里和fullScreen相同，模拟按下F11键退出全屏
                var wscript = new ActiveXObject("WScript.Shell");
                if(wscript != null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }
    </script>
@endsection