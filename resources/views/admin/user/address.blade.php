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
        //var point = new BMap.Point(120.76938270267108, 31.279379881924105);
        var point = new BMap.Point(117.010813,36.671952);
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



        points = [];
        entityList = [];
        reloadMap();

        setInterval('reloadMap()',5000);  //5秒
        //setInterval('reloadMap()',1000);

        var data = [];
        function reloadMap() {
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"get",
                url: '/admin/users/ajaxAddress',
                async: false,
                success:function (res) {
                    //console.log(res);
                    map.clearOverlays();
                    $.each(res,function (index,value) {

                        //entityList.push(value.entity_name);
                        if(value.latest_location){  // 如果有最新轨迹点信息则标注
                            var point = new BMap.Point(value.latest_location.longitude, value.latest_location.latitude);
                            // 如果points数组已经有值则直接push，没有则先创建数组再push
                            if (points.hasOwnProperty(index)) {
                                var lastPoint = points[index]['point'].slice(-1);   // 获取上个坐标点
                                try {
                                    var distance = map.getDistance(lastPoint[0],point).toFixed(2);  // 计算两点距离，单位米，保留两位小数
                                } catch (e) {
                                    points[index]['point'].push(lastPoint[0]);
                                }

                                if (distance > 150) { // 距离大于 150认为飘点，则把上一次的点当作当前次的点
                                    points[index]['point'].push(lastPoint[0]);
                                } else {
                                    points[index]['point'].push(point);
                                }

                            } else {
                                points[index] = [];
                                points[index]['color'] = getColor();
                                points[index]['point'] = [];
                                points[index]['point'].push(point);
                            }

                            if (points[index]['point'].length > 1) {
                                //console.log(points[index]);
                                var polyline = new BMap.Polyline(points[index]['point'], {
                                    enableEditing: false,
                                    enableClicking: true,
                                    strokeWeight: '5',
                                    strokeOpacity: '1',
                                    strokeColor: points[index]['color']
                                });
                                console.log(points[index]['color'])
                                map.addOverlay(polyline);
                            }
                            var myIcon = new BMap.Icon("/assets/admin/img/user_icon.png", new BMap.Size(48,85));
                            if (value.latest_location.hasOwnProperty('desc_name')) {
                                var label = new BMap.Label(value.latest_location.desc_name+';'+UnixToDate(value.latest_location.loc_time), {offset:new BMap.Size(-30,-20)});
                            } else {
                                var label = new BMap.Label(value.desc_name+';'+UnixToDate(value.latest_location.loc_time), {offset:new BMap.Size(-30,-20)});
                            }
                            //var label = new BMap.Label(value.entity_name+';上次更新时间：'+UnixToDate(value.latest_location.loc_time), {offset:new BMap.Size(-30,-20)});
                            label.setStyle({ color : "red", fontSize : "15px" });
                            addMarker(point,myIcon,label);
                            console.log(points);
                        }
                    });
                    //console.log(points);
                    //console.log(points);
                    /*$.each(points,function (index,value) {
                        var polyline = new BMap.Polyline(value, {
                            enableEditing: false,
                            enableClicking: true,
                            icons: [icons],
                            strokeWeight: '8',
                            strokeOpacity: '0.8',
                            strokeColor: "#18a45b"
                        });
                        map.addOverlay(polyline);
                    });*/


                    /*document.getElementById('users-address').onload = function (){
                        infoWindow.redraw();   //防止在网速较慢，图片未加载时，生成的信息框高度比图片的总高度小，导致图片部分被隐藏
                    }*/
                },
            });
            $.ajax();
        }

        /*for (var i = 0; ; i++) {
            for (var j = 0; j < entityList.length; j++) {
                latestPoint(j,entityList[j]);
                sleep(5000);
            }

        }

        function sleep(numberMillis) {
            var now = new Date();
            var exitTime = now.getTime() + numberMillis;
            while (true) {
                now = new Date();
                if (now.getTime() > exitTime)
                    return;
            }
        }




        function latestPoint(index,entity_name) {
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"post",
                url: '/admin/users/latestPoint',
                async: false,
                data: {
                    'entity_name' : entity_name
                },
                success:function (res) {
                    res = JSON.parse(res);*/
                    /*console.log(points);
                    map.clearOverlays();

                    var point = new BMap.Point(res.latest_point.longitude, res.latest_point.latitude);
                    points[index].push(point);
                    var polyline = new BMap.Polyline(points[index], {
                        enableEditing: false,
                        enableClicking: true,
                        strokeWeight: '3',
                        strokeOpacity: '0.5',
                        strokeColor: "red"
                    });
                    map.addOverlay(polyline);*/
                    /*$.each(points, function (index, value) {
                        var polyline = new BMap.Polyline(value, {
                            enableEditing: false,
                            enableClicking: true,
                            strokeWeight: '3',
                            strokeOpacity: '0.5',
                            strokeColor: "red"
                        });
                        map.addOverlay(polyline);

                    });*/
                    /*var myIcon = new BMap.Icon("/assets/admin/img/user_icon.png", new BMap.Size(48,85));
                    var label = new BMap.Label(entity_name+';上次更新时间：'+UnixToDate(res.loc_time), {offset:new BMap.Size(-30,-20)});
                    label.setStyle({ color : "red", fontSize : "15px" });
                    addMarker(point,myIcon,label);*/
                    /*document.getElementById('users-address').onload = function (){
                        infoWindow.redraw();   //防止在网速较慢，图片未加载时，生成的信息框高度比图片的总高度小，导致图片部分被隐藏
                    }*/
             /*   },
            });
            $.ajax();
        }*/

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
            /*ymdhis += time.getUTCFullYear() + "-";
            ymdhis += ((time.getUTCMonth()+1) < 10 ? "0" + (time.getUTCMonth()+1) : (time.getUTCMonth()+1)) + "-";
            ymdhis += (time.getUTCDate() < 10 ? "0" + time.getUTCDate() : time.getUTCDate()) + " ";*/
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

        // 随机颜色
        function getColor(){
            //定义字符串变量colorValue存放可以构成十六进制颜色值的值
            var colorValue="0,1,2,3,4,5,6,7,8,9,a,b,c,d,e,f";
            //以","为分隔符，将colorValue字符串分割为字符数组["0","1",...,"f"]
            var colorArray = colorValue.split(",");
            var color="#";//定义一个存放十六进制颜色值的字符串变量，先将#存放进去
            //使用for循环语句生成剩余的六位十六进制值
            for(var i=0;i<6;i++){
                //colorArray[Math.floor(Math.random()*16)]随机取出
                // 由16个元素组成的colorArray的某一个值，然后将其加在color中，
                //字符串相加后，得出的仍是字符串
                color+=colorArray[Math.floor(Math.random()*16)];
            }
            return color;
        }

    </script>
@endsection
