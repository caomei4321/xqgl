@extends('admin.common.app')

@section('styles')
    <!-- Data Tables -->
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('assets/admin/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ asset('assets/admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>基本 <small>分类，查找</small></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="table_data_tables.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="table_data_tables.html#">选项1</a>
                            </li>
                            <li><a href="table_data_tables.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form action="{{ route('admin.alarm.export') }}" method="get">
                        <div class="col-sm-2" style="display: inline-block">
                            <input class="form-control inline" type="date" name="timeStart">
                        </div>
                        <div class="col-sm-2" style="display: inline-block">
                            <input class="form-control inline" type="date" name="timeEnd">
                        </div>
                        <button class="btn btn-info" type="submit" style="display: inline-block"><i class="fa fa-paste"></i>智能告警事件报表生成</button>
                    </form>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>设备编号</th>
                            <th>报警信息</th>
                            <th>识别人数</th>
                            <th>报警时间</th>
                            <th>标识图片</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($hats as $hat)
                            <tr class="gradeC">
                                <td>{{ $hat->id }}</td>
                                <td>{{ $hat->device_serial }}</td>
                                <td>{{ $hat->alarm_info }}</td>
                                <td>{{ $hat->sum }}</td>
                                <td>{{ $hat->alarm_time }}</td>
                                <td>
                                    <a class="fancybox" id="img" href="{{ $hat->alarm_img_url }}" >
                                        <img src="{{ $hat->alarm_img_url }}"  style="width: 40px;" />
                                    </a>
                                </td>
                                <td class="center">
                                    <button class="btn btn-warning btn-xs delete" data-id="{{$hat->id}}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>设备编号</th>
                            <th>报警信息</th>
                            <th>识别人数</th>
                            <th>报警时间</th>
                            <th>标识图片</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $hats->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Data Tables -->
    <script src="{{ asset('assets/admin/js/plugins/dataTables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>

    <!-- Sweet alert -->
    <script src="{{ asset('assets/admin/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('assets/admin/js/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- Fancy box -->
    <script src="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.js') }}"></script>
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
            function showImg(){
                $('#img').click();
            }
        });


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
                    url: '/admin/hats/'+id,
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
    </script>
@endsection