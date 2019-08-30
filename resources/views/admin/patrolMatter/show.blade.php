@extends('admin.common.app')

@section('styles')
    <!-- iCheck -->
    <link href="{{ asset('assets/admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>巡查发现问题</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="form_basic.html#">选项1</a>
                            </li>
                            <li><a href="form_basic.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="get" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">上报人</label>

                            <div class="col-sm-10">
                                <p class="form-control-static">{{ $patrolMatter->user->name }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">问题标题</label>

                            <div class="col-sm-10">
                                <p class="form-control-static">{{ $patrolMatter->title }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">问题描述</label>

                            <div class="col-sm-10">
                                <p class="form-control-static">{{ $patrolMatter->content }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">处理意见</label>

                            <div class="col-sm-10">
                                <p class="form-control-static">{{ $patrolMatter->suggest }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">是否已处理</label>

                            <div class="col-sm-10">
                                @if( $patrolMatter->status === 1)
                                    <p class="form-control-static" style="color: red">已处理</p>
                                @else
                                    <p class="form-control-static" style="color: red">未处理</p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">问题现场图片</label>

                            <div class="col-sm-10">
                                <a class="fancybox" id="img" href="{{ $patrolMatter->img_url }}" >
                                    <img alt="image" src="{{ $patrolMatter->img_url }}" />
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>问题位置</h5>
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
                    <div style="height:600px" id="matter-address"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <!-- iCheck -->
    <script src="{{ asset('assets/admin/js/plugins/iCheck/icheck.min.js') }}"></script>

    <!-- Fancy box -->
    <script src="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.js') }}"></script>

    <!-- 百度地图js -->
    <script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=F6subxg8j4A1f28mhgryfUs0dxO8PQ8o"></script>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            $('.fancybox').fancybox({
                openEffect: 'none',
                closeEffect: 'none'
            });

            var map = new BMap.Map("matter-address");
            var point = new BMap.Point(120.76938270267108, 31.279379881924105);
            map.centerAndZoom(point, 15);
            map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放

            var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
            var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
            var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮

            var point = new BMap.Point({{ $patrolMatter->longitude }}, {{ $patrolMatter->latitude }});

            // var point = new BMap.Point(120.76938270267108, 31.279379881924105);

            var marker = new BMap.Marker(point);
            map.addOverlay(marker);
            add_control();

            marker.addEventListener("click",showImg);

            // 添加标注
            // function addMarker(point,label){
            //     var marker = new BMap.Marker(point);
            //     map.addOverlay(marker);
            //     marker.setLabel(label);
            // }

            //添加控件和比例尺
            function add_control(){
                map.addControl(top_left_control);
                map.addControl(top_left_navigation);
                map.addControl(top_right_navigation);
            }

            function showImg(){
                $('#img').click();
            }









            $('.delete').click(function () {
                var id = $(this).data('id');
                swal({
                    title: "您确定要删除这条信息吗",
                    text: "删除后将无法恢复，请谨慎操作！",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    cancelButtonText: "取消",
                    closeOnConfirm: false
                }, function () {
                    $.ajaxSetup({
                        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type:"delete",
                        url: '/admin/patrolMatters/'+id,
                        success:function (res) {
                            if (res.status == 1){
                                swal(res.msg, "您已经永久删除了这条信息。", "success");
                                location.reload();
                            }else {
                                swal(res.msg, "请稍后重试。", "waring");
                            }
                        },
                    });
                    $.ajax();
                });
            });

        });
    </script>
@endsection