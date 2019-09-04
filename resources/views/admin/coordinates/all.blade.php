@extends('admin.common.app')

@section('styles')
    <link rel="stylesheet" href="http://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.css" />
    <link rel="stylesheet" href="http://api.map.baidu.com/library/SearchInfoWindow/1.4/src/SearchInfoWindow_min.css" />
    <style type="text/css">
        body, html {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
        #allmap { height: 700px;}
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>LINE</h5>
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
                            </tr>
                            </thead>

                                @for($i = 0; $i <  $len; $i++)
                                <tbody class="tbody{{$i}} item">
                                    @foreach($all[$i] as $value)
                                <tr>
                                    <td class="t1">{{$value[0]}}</td>
                                    <td class="t2">{{$value[1]}}</td>
                                </tr>
                                    @endforeach
                                </tbody>
                                @endfor

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
    <script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G"></script>
    {{--<script type="text/javascript" src="//api.map.baidu.com/api?v=3.0&ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G"></script>--}}
@endsection

@section('javascript')
    <script type="text/javascript">
        // 百度地图API功能
        var map = new BMap.Map("allmap");    // 创建Map实例
        map.centerAndZoom(new BMap.Point(117.005693, 36.674489), 18);  // 初始化地图,设置中心点坐标和地图级别
        map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
        var item = $('.item').length;

        // map.setMapStyleV2({
        //     styleId: '4164dc3852e0db5655f892b8f46d98d6'
        // });

        for (var n = 0; n < item; n++) {
            var poissss = [];
            $('.table').find('.tbody'+ n).each(function (index, item) {
                $(this).find('tr').each(function () {
                    var tdArr = $(this).children();
                    var lng = tdArr.eq(0).text();
                    var lat = tdArr.eq(1).text();
                    var length = lng.length;
                    for (var i = 0; i < length; i++){
                        poissss.push(new BMap.Point(lng,lat));
                    }
                })
            });

            var polyliness =new BMap.Polyline(poissss, {
                enableEditing: false,//是否启用线编辑，默认为false
                enableClicking: true,//是否响应点击事件，默认为true
                // icons:[icons],
                strokeStyle: 'dashed',
                strokeWeight:'2',//折线的宽度，以像素为单位
                strokeOpacity: 0.8,//折线的透明度，取值范围0 - 1
                strokeColor:"#f34747" //折线颜色
            });

            map.addOverlay(polyliness);
        }

    </script>
@endsection
