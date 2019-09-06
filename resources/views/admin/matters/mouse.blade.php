@extends('admin.common.app')

@section('styles')
    <link rel="stylesheet" href="http://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.css" />
    <link rel="stylesheet" href="http://api.map.baidu.com/library/SearchInfoWindow/1.4/src/SearchInfoWindow_min.css" />
    <style type="text/css">
        body, html{width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
        #allmap {width: 100%; height:800px; overflow: hidden;}
        #result {width:100%;font-size:12px;}
        dl,dt,dd,ul,li{
            margin:0;
            padding:0;
            list-style:none;
        }
        p{font-size:12px;}
        dt{
            font-size:14px;
            font-family:"微软雅黑";
            font-weight:bold;
            border-bottom:1px dotted #000;
            padding:5px 0 5px 5px;
            margin:5px 0;
        }
        dd{
            padding:5px 0 0 5px;
        }
        li{
            line-height:28px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Mouse</h5>
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
                    <div id="data" style="display: none">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>经度</th>
                                <th>纬度</th>
                                <th>物品+编号</th>
                                <th>地址</th>
                                <th>描述</th>
                                <th>图片</th>
                                <th>图标</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>经度</th>
                                <th>纬度</th>
                                <th>物品+编号</th>
                                <th>地址</th>
                                <th>描述</th>
                                <th>图片</th>
                                <th>图标</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="allmap" style="overflow:hidden;zoom:1;position:relative;">
                        <div id="map" style="height:100%;-webkit-transition: all 0.5s ease-in-out;transition: all 0.5s ease-in-out;"></div>
                    </div>
                    <div id="result">
                        <input type="button" value="获取绘制的覆盖物个数" onclick="alert(overlays.length)"/>
                        <input type="button" value="清除所有覆盖物" onclick="clearAll()"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- 百度地图js -->
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G"></script>
    <!--加载鼠标绘制工具-->
    <script type="text/javascript" src="http://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.js"></script>
    <!--加载检索信息窗口-->
    <script type="text/javascript" src="http://api.map.baidu.com/library/SearchInfoWindow/1.4/src/SearchInfoWindow_min.js"></script>


@endsection

@section('javascript')
    <script type="text/javascript">
        // 百度地图API功能
        var map = new BMap.Map('map');
        var poi = new BMap.Point(117.008532,36.674077);
        map.centerAndZoom(poi, 18);
        map.enableScrollWheelZoom();
        var overlays = [];
        var overlaycomplete = function(e){
            overlays.push(e.overlay);
            var path = e.overlay.getPath(); // Array<Point> 返回多边型的点数组
            var data = [];
            for (var i =0; i<path.length; i++) {
                // console.log('lng:' + path[i].lng+"\n lat:"+path[i].lat)
                data.push('lng:' + path[i].lng+"\n lat:"+path[i].lat);
            }
            if (data) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('admin/matters/ajax') }}",
                    type: "POST",
                    data: data,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        if (res.status == 1){
                            console.log(res.msg);
                        }
                    },
                    error: function (res) {
                        console.log('fail');
                    }
                });
            }
        };

        var styleOptions = {
            strokeColor:"red",    //边线颜色。
            fillColor:"red",      //填充颜色。当参数为空时，圆形将没有填充效果。
            strokeWeight: 3,       //边线的宽度，以像素为单位。
            strokeOpacity: 0.8,	   //边线透明度，取值范围0 - 1。
            fillOpacity: 0.6,      //填充的透明度，取值范围0 - 1。
            strokeStyle: 'solid' //边线的样式，solid或dashed。
        }
        //实例化鼠标绘制工具
        var drawingManager = new BMapLib.DrawingManager(map, {
            isOpen: false, //是否开启绘制模式
            enableDrawingTool: true, //是否显示工具栏
            drawingMode:BMAP_DRAWING_POLYGON,
            drawingToolOptions: {
                anchor: BMAP_ANCHOR_TOP_RIGHT, //位置
                offset: new BMap.Size(5, 5), //偏离值
                drawingModes:[
                    BMAP_DRAWING_POLYLINE
                ]
            },
            // circleOptions: styleOptions, //圆的样式
            polylineOptions: styleOptions, //线的样式
            // polygonOptions: styleOptions, //多边形的样式
            // rectangleOptions: styleOptions //矩形的样式
        });
        //添加鼠标绘制工具监听事件，用于获取绘制结果
        drawingManager.addEventListener('overlaycomplete', overlaycomplete);
        function clearAll() {
            for(var i = 0; i < overlays.length; i++){
                map.removeOverlay(overlays[i]);
            }
            overlays.length = 0
        }
    </script>
@endsection
