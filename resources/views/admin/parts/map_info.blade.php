@extends('admin.common.app')

@section('styles')
    <style type="text/css">
        body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
        #allmap{width:100%;height:500px;}
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
                    <div id="data">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>经度</th>
                                <th>纬度</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($parts as $part)
                            <tr class="data">
                                <td class="t1">{{ $part->longitude }}</td>
                                <td class="t2">{{ $part->latitude }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>经度</th>
                                <th>纬度</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div id="allmap"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- 百度地图js -->
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G"></script>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
@endsection

@section('javascript')
    <script type="text/javascript">
        // 百度地图API功能
        map = new BMap.Map("allmap");
        map.centerAndZoom(new BMap.Point(120.7777399679,31.3505530086), 14);
        var data_info = [[120.7777399679,31.3505530086,"公厕"],
            [120.7711588605,31.3505530086,"垃圾桶"],
            [120.785287,31.3505530086,"井盖"],
            [120.61990711,31.31798731,"石墩"]
        ];
        var opts = {
            width : 250,     // 信息窗口宽度
            height: 80,     // 信息窗口高度
            title : "信息窗口" , // 信息窗口标题
            enableMessage:true//设置允许信息窗发送短息
        };
        for(var i=0;i<data_info.length;i++){
            var marker = new BMap.Marker(new BMap.Point(data_info[i][0],data_info[i][1]));  // 创建标注
            var content = data_info[i][2];
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
    </script>
@endsection
