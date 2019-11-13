@extends('admin.common.app')

@section('styles')
    <style type="text/css">
        body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
        #allmap{width:100%;height:800px;}
        p{margin-left:5px; font-size:14px;}
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Marker</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="">
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
                    <div id="allmap"></div>
                    <div id="r-result">
                        <input type="button" class="btn-info" onclick="add_control();" value="添加控件" />
                        <input type="button" class="btn-info" onclick="delete_control();" value="删除控件" />
                        <span class="img">垃圾桶<img src="{{ asset('assets/admin/img/part1.png') }}" alt=""></span>
                        <span class="img">广告牌<img src="{{ asset('assets/admin/img/part2.png') }}" alt=""></span>
                        <span class="img">公厕<img src="{{ asset('assets/admin/img/part3.png') }}" alt=""></span>
                        <span class="img">路灯/摄像头<img src="{{ asset('assets/admin/img/part4.png') }}" alt=""></span>
                        <span class="img">井盖<img src="{{ asset('assets/admin/img/part5.png') }}" alt=""></span>
                        <span class="img">自行车停放点<img src="{{ asset('assets/admin/img/part6.png') }}" alt=""></span>
                        <span class="img">其他<img src="{{ asset('assets/admin/img/part7.png') }}" alt=""></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- 百度地图js -->
    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G"></script>
    {{--<script src="https://libs.baidu.com/jquery/1.9.0/jquery.js"></script>--}}
    <script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G"></script>
@endsection

@section('javascript')
    <script type="text/javascript">
        // 百度地图API功能
        map = new BMap.Map("allmap");
        map.centerAndZoom(new BMap.Point(117.009475,36.668982), 18);

        var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
        var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
        var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮
        /*缩放控件type有四种类型:
        BMAP_NAVIGATION_CONTROL_SMALL：仅包含平移和缩放按钮；BMAP_NAVIGATION_CONTROL_PAN:仅包含平移按钮；BMAP_NAVIGATION_CONTROL_ZOOM：仅包含缩放按钮*/

        var opts = {
            width : 350,     // 信息窗口宽度
            height: 200,     // 信息窗口高度
            // title : "信息窗口" , // 信息窗口标题
            enableMessage:true, //设置允许信息窗发送短息
        };

        var host = window.location.protocol+"//"+window.location.host;

        $(document).ready(function () {
            $.ajax({
                url: "/admin/partInfo",
                dataType: "json",
                type: "get",
                async: false,
                success: function (data) {
                    console.log(data[0]);
                    for (var i=0; i < data.length; i++) {
                        if ( data[i]['kind_id'] == '1') {
                            var myIcon = new BMap.Icon(host+"/assets/admin/img/part1.png", new BMap.Size(35,35));
                        }else if (data[i]['kind_id'] == '2'){
                            var myIcon = new BMap.Icon(host+"/assets/admin/img/part2.png", new BMap.Size(35,35));
                        } else if(data[i]['kind_id'] == '3') {
                            var myIcon = new BMap.Icon(host+"/assets/admin/img/part3.png", new BMap.Size(35,35));
                        } else if (data[i]['kind_id'] == '4') {
                            var myIcon = new BMap.Icon(host+"/assets/admin/img/part4.png", new BMap.Size(35,35));
                        }else if (data[i]['kind_id'] == '5') {
                            var myIcon = new BMap.Icon(host+"/assets/admin/img/part5.png", new BMap.Size(35,35));
                        }else if (data[i]['kind_id'] == '6') {
                            var myIcon = new BMap.Icon(host+"/assets/admin/img/part6.png", new BMap.Size(35,35));
                        } else {
                            var myIcon = new BMap.Icon(host+"/assets/admin/img/part7.png", new BMap.Size(35,35));
                        }
                        marker = new BMap.Marker(new BMap.Point(data[i]['longitude'], data[i]['latitude']), {
                            icon: myIcon
                        });  // 创建标注
                        content =
                            "<h4 style='margin-left: 13px; margin-bottom: 5px;'>"+ data[i]['things'] + data[i]['num'] +" </h4>" +
                            "<p style='margin-left: 0px; margin-bottom:0px; '>"+  "<img style='width: 324px;height: 101px; margin-left: 13px; margin-bottom: 5px;' src="+data[i]['image']+">" +"</p>" +
                            "<p style='margin: 0 12px; font-size: 12px; color: rgb(77,77,77);'>"+"地址："+  data[i]['address'] +"</p>" +
                            "<p style=' margin: 0 12px;font-size: 12px; color: rgb(127,127,127); overflow: hidden;text-overflow: ellipsis;'>"+"物品信息："+ data[i]['things'] +"</p>" +
                            "</div>";
                        map.addOverlay(marker);               // 将标注添加到地图中
                        addClickHandler(content,marker);
                    }

                }
            })
        });

        function addClickHandler(content,marker){
            marker.addEventListener("click",function(e){
                openInfo(content,e)}
            );
        }
        function openInfo(content,e){
            var p = e.target;
            var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
            var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象
            map.openInfoWindow(infoWindow,point); //开启信息窗口
        }

        //添加控件和比例尺
        function add_control(){
            map.addControl(top_left_control);
            map.addControl(top_left_navigation);
            map.addControl(top_right_navigation);
        }
        //移除控件和比例尺
        function delete_control(){
            map.removeControl(top_left_control);
            map.removeControl(top_left_navigation);
            map.removeControl(top_right_navigation);
        }

        // 点击方法
        map.enableScrollWheelZoom(); // 启用滚轮放大缩小
        map.enableInertialDragging();
        map.enableContinuousZoom();  // 启用地图惯性拖拽

        var size = new BMap.Size(10, 20);
        map.addControl(new BMap.CityListControl({
            anchor: BMAP_ANCHOR_TOP_LEFT,
            offset: size,
        }));

        // 覆盖区域图层测试
        // map.addTileLayer(new BMap.PanoramaCoverageLayer());
        var stCtrl = new BMap.PanoramaControl(); //构造全景控件
        stCtrl.setOffset(new BMap.Size(20, 20));
        map.addControl(stCtrl);//添加全景控件
    </script>
@endsection
