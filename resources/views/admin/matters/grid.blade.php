@extends('admin.common.app')

@section('styles')
    <style type="text/css">
        body, html{width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
        #allmap {height:500px; width: 100%;}
        #control{width:100%;}
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Grid</h5>
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
                    <div id="allmap"></div>
                    <div id="control">
                        <button onclick = "polyline.enableEditing();polygon.enableEditing();">开启线、面编辑功能</button>
                        <button onclick = "polyline.disableEditing();polygon.disableEditing();">关闭线、面编辑功能</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- 百度地图js -->
    <script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G"></script>
@endsection

@section('javascript')
    <script type="text/javascript">
        // 百度地图API功能
        var map = new BMap.Map("allmap");
        map.centerAndZoom(new BMap.Point(116.404, 39.915), 15);
        map.enableScrollWheelZoom();

        var polyline = new BMap.Polyline([
            new BMap.Point(116.399, 39.910),
            new BMap.Point(116.405, 39.920),
            new BMap.Point(116.423493, 39.907445)
        ], {strokeColor:"blue", strokeWeight:2, strokeOpacity:0.5});   //创建折线
        map.addOverlay(polyline);   //增加折线
        var polygon = new BMap.Polygon([
            new BMap.Point(116.387112,39.920977),
            new BMap.Point(116.385243,39.913063),
            new BMap.Point(116.394226,39.917988),
            new BMap.Point(116.401772,39.921364),
            new BMap.Point(116.41248,39.927893)
        ], {strokeColor:"blue", strokeWeight:2, strokeOpacity:0.5});  //创建多边形
        map.addOverlay(polygon);   //增加多边形
    </script>
@endsection
