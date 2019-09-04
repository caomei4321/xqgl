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

        var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
        var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
        var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮

        add_control();

        var i = 0;
        /*
        var lng = "" ;
        var lat = "" ;
        var marker = new BMap.Marker(new BMap.Point(lng, lat)); */// 创建点
        /*var marker2 = new BMap.Marker(new BMap.Point(116.399, 39.910)); // 创建点*/
        // map.addOverlay(marker);            //增加点
        /*map.addOverlay(marker2);            //增加点*/

        /*marker.addEventListener("click", function(){
            alert("您点击了标注");
        });*/
        //清除覆盖物 map.clearOverlays();

        @forEach($entities as $entity)
            @if(isset($entity->latest_location))
            var point = new BMap.Point({{ $entity->latest_location->longitude }}, {{ $entity->latest_location->latitude }});
            var myIcon = new BMap.Icon("/assets/admin/img/user_icon.png", new BMap.Size(48,48));
            var label = new BMap.Label("{{ $entity->entity_name.';'.'上次更新时间：'. date('Y-m-d H:i:s',$entity->latest_location->loc_time) }}", {offset:new BMap.Size(-30,-20)});
            addMarker(point,myIcon,label);
            @endif
        @endforeach



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


        //全屏代码
        function launchFullscreen(element) {
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
        }
        //退出全屏
        function exitFullscreen() {
            var de = document;
            if (de.exitFullscreen) {
                de.exitFullscreen();
            } else if (de.mozCancelFullScreen) {
                de.mozCancelFullScreen();
            } else if (de.webkitCancelFullScreen) {
                de.webkitCancelFullScreen();
            }
        }


    </script>
@endsection
