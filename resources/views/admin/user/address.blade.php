@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>人员位置分布</h5>
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
                    <div style="height:600px" id="users-address"></div>
                    <button class="btn btn-info " type="button" onclick="fullScreen()"><i class="fa fa-paste"></i> 全屏展示</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chosen -->
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>
    <!-- 百度地图js -->

    <script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=F6subxg8j4A1f28mhgryfUs0dxO8PQ8o"></script>
    {{--<script type="text/javascript" src="//api.map.baidu.com/api?v=3.0&ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G"></script>--}}

@endsection
<!-- 自定义js -->
{{--<script src="{{ asset('assets/admin/js/content.js?v=1.0.0') }}"></script>--}}
@section('javascript')
    <script>
        // 百度地图API功能
        var map = new BMap.Map("users-address");
        var point = new BMap.Point(120.76938270267108, 31.279379881924105);
        map.centerAndZoom(point, 15);
        map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
        /*map.setMapStyleV2({
            styleId: '4164dc3852e0db5655f892b8f46d98d6'
        });*/

        var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
        var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
        var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮

        add_control();

        var i = 0;




        reloadMap();
        setInterval('reloadMap()', 10000);  //10秒
        //setInterval('reloadMap()',1000);

        var data = [];
        function reloadMap() {
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"get",
                url: '/admin/users/ajaxAddress',
                async: false,
                success:function (res) {
                    map.clearOverlays();
                    $.each(res,function (index,value) {

                        if(value.latest_location){
                            var point = new BMap.Point(value.latest_location.longitude, value.latest_location.latitude);
                            var myIcon = new BMap.Icon("/assets/admin/img/user_icon.png", new BMap.Size(48,48));
                            var label = new BMap.Label(value.entity_name+';上次更新时间：'+UnixToDate(value.latest_location.loc_time), {offset:new BMap.Size(-30,-20)});
                            label.setStyle({ color : "red", fontSize : "15px" });
                            addMarker(point,myIcon,label);
                        }
                    });
                    /*document.getElementById('users-address').onload = function (){
                        infoWindow.redraw();   //防止在网速较慢，图片未加载时，生成的信息框高度比图片的总高度小，导致图片部分被隐藏
                    }*/
                },
            });
            $.ajax();
        }

        // 添加标注
        function addMarker(point,myIcon,label){
            var marker = new BMap.Marker(point,{icon:myIcon});
            map.addOverlay(marker);
            marker.setLabel(label);
        }

        //添加控件和比例尺
        function add_control(){
            map.addControl(top_left_control);
            map.addControl(top_left_navigation);
            map.addControl(top_right_navigation);
        }


        //content是一块div，全屏的方法通过一个button调用
        //var el = document.getElementById("content");


        // 地图全屏
        function fullScreen() {
            var el = document.getElementById("users-address");
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

        // 时间戳转日期格式 2019-09-05 10:13:30
        function UnixToDate(unixTime, isFull, timeZone) {
            if (typeof (timeZone) == 'number')
            {
                unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
            }
            var time = new Date(unixTime * 1000);
            var ymdhis = "";
            ymdhis += time.getUTCFullYear() + "-";
            ymdhis += ((time.getUTCMonth()+1) < 10 ? "0" + (time.getUTCMonth()+1) : (time.getUTCMonth()+1)) + "-";
            ymdhis += (time.getUTCDate() < 10 ? "0" + time.getUTCDate() : time.getUTCDate()) + " ";
            ymdhis += (time.getHours() < 10 ? "0" + time.getHours() : time.getHours()) + ":";
            ymdhis += (time.getUTCMinutes() < 10 ? "0" + time.getUTCMinutes() : time.getUTCMinutes()) + ":";
            ymdhis += (time.getUTCSeconds() < 10 ? "0" + time.getUTCSeconds() : time.getUTCSeconds());
            if (isFull === true)
            {
                ymdhis += (time.getHours() < 10 ? "0" + time.getHours() : time.getHours()) + ":";
                ymdhis += (time.getUTCMinutes() < 10 ? "0" + time.getUTCMinutes() : time.getUTCMinutes()) + ":";
                ymdhis += (time.getUTCSeconds() < 10 ? "0" + time.getUTCSeconds() : time.getUTCSeconds());
            }
            return ymdhis;
        }

    </script>
@endsection
