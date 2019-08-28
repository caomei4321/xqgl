@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
    <style type="text/css">
        #map_container {
            height:600px;
            width: auto;
        }
        #control {
            position: absolute;
            bottom: 15px;
        }
        .button {
            margin: 5px;
            padding: 13px 23px;
            border-radius: 10px;
            box-shadow: 0 3px 14px rgba(0, 0, 0, 0.4);
            font: 16px/14px Tahoma, Verdana, sans-serif;
            text-align: center;
            color: #fefefe;
            background: #1e90ff;
        }
        .popup {
            z-index: 1;
            text-align: center;
            border: none;
            text-align: center;
            width: 100%;
            height: 60px;
            font: 16px/60px Tahoma, Verdana, sans-serif;
            box-shadow: 0 10px 14px rgba(0, 0, 0, 0.4);
            color: #fefefe;
            background: #1e90ff;
        }

    </style>
    <link rel="stylesheet" href="//api.map.baidu.com/library/SearchInfoWindow/1.4/src/SearchInfoWindow_min.css" />
    <link rel="stylesheet" href="//api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.css" />
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
                    <div id="map_container"></div>
                    <div id="control">
                        {{--<div class="popup">点击Marker显示信息</div>--}}
                        <div onclick="addMarker()" class="button">查看物品定位</div>
                        <div onclick="removeMarker()" class="button">删除物品定位</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chosen -->
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>
    <!-- 百度地图js -->
    <script type="text/javascript" src="//api.map.baidu.com/api?ak=HzdI6uW2xAsdwGmxQbdWitq0ZGGhO02G&type=lite&v=1.0"></script>
@endsection

@section('javascript')
    <script type="text/javascript">

        var liteMap = new BMap.Map('map_container');
        var pt = new BMap.Point(120.785287,31.350246);
        liteMap.centerAndZoom(pt, 12);
        liteMap.addControl(new BMap.ZoomControl());

        var arrFeatures = [];
        var lng = $('.t1');
        var lat = $('.t2')
        var length = $('.t1').length;
        for (var i = 0; i < length; i ++) {
            // 初始化位置
            var mpt = new BMap.Point(lng[i].innerHTML ,lat[i].innerHTML);
            // 变换icon
            var iconOffsetX = 42;
            var iconOffsetY = 66;

            var massFeature = new BMap.MassFeature(mpt, {data: 'MassFeature' + i});
            arrFeatures.push(massFeature);
        }

        // 添加海量覆盖物
        function addMarker() {
            liteMap.addMassFeatures(arrFeatures);
        }
        // 移除海量覆盖物
        function removeMarker() {
            liteMap.removeMassFeatures(arrFeatures);
            document.querySelector('.popup').innerHTML = '点击Marker显示信息';
        }

        // 为海量覆盖物(新版Marker)添加监听
        liteMap.addEventListener('massfeaturesclick', function (evt) {
            var popup = document.querySelector('.popup');
            var massFeatures = evt.massFeatures;
            if (massFeatures.length > 0) {
                var feature = massFeatures[0];
                var name = feature.getData();
                var point = feature.getPosition();
                popup.innerHTML = name + ': ' + point.lng.toFixed(0) + ', ' + point.lat.toFixed(0);
            } else {
                popup.innerHTML = '点击Marker显示信息';
            }
        });
    </script>
@endsection
