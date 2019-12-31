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
        var myIcon = new BMap.Icon("/assets/admin/img/user_icon2.png", new BMap.Size(48,85),{
            imageSize: new BMap.Size(40,40),
            imageOffset:new BMap.Size(5,5)
        });
        reloadMap();
        // setInterval('reloadMap()',5000);  5秒
        //setInterval('reloadMap()',1000);
        var data = [];
        function reloadMap() {
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"get",
                url: '/admin/users/entityList',
                async: false,
                success:function (res) {
                    console.log(res);
                    map.clearOverlays();
                    $.each(res,function (index,value) {

                        sContent =
                            "<h4 style='margin-left: 13px; margin-bottom: 5px;'>"+ "人员信息" +" </h4></br>" +
                            "<span style='margin: 0 12px;'>姓名 :" + value.latest_location.desc_name + "</span></br>" +
                            "<p style='margin: 0 12px; font-size: 12px; color: rgb(77,77,77);'>"+"更新时间："+ UnixToDate(value.latest_location.loc_time)  +"</p></br>" +
                            "<p style=' margin: 0 12px;font-size: 12px; color: rgb(127,127,127); overflow: hidden;text-overflow: ellipsis;'>";
                        var point = new BMap.Point(value.latest_location.longitude, value.latest_location.latitude);
                        points[index] = [];
                        points[index]['color'] = getColor();
                        points[index]['point'] = [];
                        points[index]['sContent'] = sContent;
                        points[index]['lastTime'] = value.latest_location.loc_time;
                        points[index]['startTime'] = parseInt(new Date().getTime()/1000) ;
                        points[index]['entityName'] = value.entity_name;
                        points[index]['name'] = value.latest_location.desc_name;
                        points[index]['infoWindow'] = new BMap.InfoWindow(points[index]['sContent']);
                        points[index]['marker'] = new BMap.Marker(point,{icon:myIcon});
                        map.addOverlay( points[index]['marker']);
                        points[index]['marker'].addEventListener("click", function () {
                            this.openInfoWindow(points[index]['infoWindow']);
                        });
                    });
                    /*document.getElementById('users-address').onload = function (){
                        infoWindow.redraw();   //防止在网速较慢，图片未加载时，生成的信息框高度比图片的总高度小，导致图片部分被隐藏
                    }*/
                },
            });
            $.ajax();
        }


        var pointsLength = points.length;

        var timer = points.length * 5000;  // 所有人员循环一遍所用的时间（单位毫秒）

        for (let a = 0; a < pointsLength; a++) {
            setTimeout(function () {
                userAddress(a, points[a].entityName, pointsLength)
            }, a*5000);
        }
        setInterval(function () {
            for (let a = 0; a < pointsLength; a++) {
                setTimeout(function () {
                    userAddress(a, points[a].entityName, pointsLength)
                }, a*5000);
            }
        },timer);  //5秒

        function userAddress(index, entityName, pointsLength) {
            console.log(index,entityName,pointsLength);
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"post",
                data: {
                    'entityName' : entityName,
                    'startTime'  : points[index]['startTime']
                },
                url: '/admin/users/latestPoint',
                async: false,
                success:function (res) {
                    res = JSON.parse(res);
                    console.log(res);
                    if (res.size > 0) {  // 结果数大于 0
                        map.clearOverlays();
                        // 计算两次的时间间隔 ， 
                        var time = res.end_point.loc_time - points[index]['lastTime'];  // 计算两次的时间间隔 ( 单位秒)

                        console.log(time,timer/1000+200);
                        if (time  > (timer/1000+200)) {  // 间隔时间超过正常时间后的200秒算不正常, 清空之前的轨迹点
                            console.log(5555);
                            points[index]['point'] = [];
                        }
                        /*map.removeOverlay(new BMap.Polyline(points[index]['point'], {
                            enableEditing: false,
                            enableClicking: true,
                            strokeWeight: '5',
                            strokeOpacity: '1',
                            strokeColor: points[index]['color']
                        }));
                        map.removeOverlay(points[index]['marker']);*/
                        $.each(res.points, function (key, value) {
                            var point = new BMap.Point(value.longitude, value.latitude);
                            console.log(point);
                            console.log(111);
                            points[index]['point'].push(point);
                        });
                        var endPoint = new BMap.Point(res.end_point.longitude, res.end_point.latitude);
                        var sContent =
                            "<h4 style='margin-left: 13px; margin-bottom: 5px;'>"+ "人员信息" +" </h4></br>" +
                            "<span style='margin: 0 12px;'>姓名 :" + points[index]['name'] + "</span></br>" +
                            "<p style='margin: 0 12px; font-size: 12px; color: rgb(77,77,77);'>"+"更新时间："+ UnixToDate(res.end_point.loc_time)  +"</p></br>" +
                            "<p style=' margin: 0 12px;font-size: 12px; color: rgb(127,127,127); overflow: hidden;text-overflow: ellipsis;'>";
                        points[index]['sContent'] = sContent;
                        points[index]['infoWindow'] = new BMap.InfoWindow(points[index]['sContent']);
                        points[index]['marker'] = new BMap.Marker(endPoint,{icon:myIcon});
                        points[index]['lastTime'] = res.end_point.loc_time

                        $.each(points, function (key, value) {
                            /*if (value['point']) {
                                var sy = new BMap.Symbol(BMap_Symbol_SHAPE_BACKWARD_OPEN_ARROW, {
                                    scale: 0.6,//图标缩放大小
                                    strokeColor:'#fff',//设置矢量图标的线填充颜色
                                    strokeWeight: '0.6',//设置线宽
                                });
                                var icons = new BMap.IconSequence(sy, '5', '5');*/
                                var polyline = new BMap.Polyline(value['point'], {
                                    enableEditing: false,
                                    enableClicking: true,
                                    strokeWeight: '5',
                                    strokeOpacity: '1',
                                    strokeColor: value['color'],
                                    //icons: [icons]
                                });
                            //}

                            map.addOverlay( value['marker']);
                            value['marker'].addEventListener("click", function () {
                                this.openInfoWindow(value['infoWindow']);
                            });
                            map.addOverlay(polyline);
                        })

                    }
                    console.log(points);
                },
            });
            $.ajax();
        }

        var markers = [];
        var sContent = [];
        var infoWindow = [];
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
