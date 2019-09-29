@extends('admin.common.app')

@section('styles')
    <style type="text/css">
        body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
        #allmap{width:100%;height:600px;}
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
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($parts as $part)
                            <tr class="data">
                                <td class="t1">{{ $part->longitude }}</td>
                                <td class="t2">{{ $part->latitude }}</td>
                                <td class="t3">
                                    {{ $part->things }} {{ $part->num }}
                                    <span style="float: right; margin-right: 20px;">
                                    @if($part->status == 0)
                                        正在使用
                                    @else
                                        已损坏
                                    @endif
                                    </span>
                                </td>
                                <td class="t4">{{ $part->address }}</td>
                                <td class="t5">{{ $part->info }}</td>
                                <td class="t6"><image style='width: 324px;height: 101px; margin-left: 13px; margin-bottom: 5px;' src="{{ $part->image }}" /></td>
                                <td class="t7">
                                    {{ $part->kind_id }}
                                </td>
                            </tr>
                            @endforeach
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

                    <div id="allmap"></div>
                    <div id="r-result">
                        <input type="button" class="btn-info" onclick="add_control();" value="添加控件" />
                        <input type="button" class="btn-info" onclick="delete_control();" value="删除控件" />
                        <span class="img">垃圾桶<img src="{{ asset('assets/admin/img/t1.png') }}" alt=""></span>
                        <span class="img">公厕<img src="{{ asset('assets/admin/img/t2.png') }}" alt=""></span>
                        <span class="img">广告牌<img src="{{ asset('assets/admin/img/t3.png') }}" alt=""></span>
                        <span class="img">路灯<img src="{{ asset('assets/admin/img/t4.png') }}" alt=""></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- 百度地图js -->
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G"></script>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
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

        var lng = $('.t1'); // 经度
        var lat = $('.t2'); // 纬度
        var things = $('.t3'); // 物品+编号
        var address = $('.t4'); // 地址
        var info = $('.t5'); // 信息
        var img = $('.t6'); //图片
        var kind = $('.t7'); // 种类
        var lenght = $('.t1').length;
        var data_info = [];
        for (var i = 0; i < lenght; i++) {
            var data_array = [lng[i].innerHTML, lat[i].innerHTML, things[i].innerHTML, address[i].innerHTML, info[i].innerHTML, img[i].innerHTML, kind[i].innerHTML];
            data_info.push(data_array);
        }
        console.log(data_info);
        var opts = {
            width : 350,     // 信息窗口宽度
            height: 200,     // 信息窗口高度
            // title : "信息窗口" , // 信息窗口标题
            enableMessage:true, //设置允许信息窗发送短息
        };

        var host = window.location.protocol+"//"+window.location.host;

        for(var i=0;i<data_info.length;i++){
            if ( data_info[i][6].indexOf('1') != '-1') {
                var myIcon = new BMap.Icon(host+"/assets/admin/img/t1.png", new BMap.Size(35,35));
            }else if (data_info[i][6].indexOf('2') != '-1'){
                var myIcon = new BMap.Icon(host+"/assets/admin/img/t3.png", new BMap.Size(35,35));
            } else if(data_info[i][6].indexOf('3') != '-1') {
                var myIcon = new BMap.Icon(host+"/assets/admin/img/t2.png", new BMap.Size(35,35));
            } else if (data_info[i][6].indexOf('4') != '-1') {
                var myIcon = new BMap.Icon(host+"/assets/admin/img/t4.png", new BMap.Size(35,35));
            } else {
                var myIcon = new BMap.Icon(host+"/assets/admin/img/al1.png", new BMap.Size(35,35));
            }
            var marker = new BMap.Marker(new BMap.Point(data_info[i][0],data_info[i][1]), {
                icon: myIcon
            });  // 创建标注
            console.log(marker);
            var content =
                "<h4 style='margin-left: 13px; margin-bottom: 5px;'>"+ data_info[i][2] +" </h4>" +
                "<p style='margin-left: 0px; margin-bottom:0px; '>"+  data_info[i][5] +"</p>" +
                "<p style='margin: 0 12px; font-size: 12px; color: rgb(77,77,77);'>"+"地址："+  data_info[i][3] +"</p>" +
                "<p style=' margin: 0 12px;font-size: 12px; color: rgb(127,127,127); overflow: hidden;text-overflow: ellipsis;'>"+"物品信息："+ data_info[i][4] +"</p>" +
                "</div>";
            // var content = data_info[i][2];
            map.addOverlay(marker);               // 将标注添加到地图中
            addClickHandler(content,marker);
        }
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
