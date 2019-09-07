@extends('admin.common.app')

@section('styles')
    <!-- Data Tables -->
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('assets/admin/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
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

                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>设备编号</th>
                            <th>告警时间</th>
                            <th>告警类型</th>
                            <th>经度</th>
                            <th>纬度</th>
                            <th>所属网格</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($alarms as $alarm)
                            <tr class="gradeC">
                                <td>{{ $alarm->id }}</td>
                                <td>{{ $alarm->device_serial }}</td>
                                <td>{{ $alarm->alarm_start }}</td>
                                <td>{{ $alarm->alarm_type }}</td>
                                <td>{{ $alarm->longitude }}</td>
                                <td>{{ $alarm->latitude }}</td>
                                <td>{{ $alarm->number }}号网格</td>
                                <td class="center">
                                    <a href="{{ route('admin.alarm.detail', ['id' => $alarm->id]) }}" class="btn btn-info btn-xs">查看</a>
                                    <button class="btn btn-warning btn-xs delete" data-id="">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>设备编号</th>
                            <th>告警时间</th>
                            <th>告警类型</th>
                            <th>经度</th>
                            <th>纬度</th>
                            <th>所属网格</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $alarms->links() }}
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
@endsection

@section('javascript')
    <script>
        // $('.delete').click(function () {
        //     var id = $(this).data('id');
        //     swal({
        //         title: "您确定要删除这条信息吗",
        //         text: "删除后将无法恢复，请谨慎操作！",
        //         type: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#DD6B55",
        //         confirmButtonText: "删除",
        //         cancelButtonText: "取消",
        //         closeOnConfirm: false
        //     }, function () {
        //         $.ajaxSetup({
        //             headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //             type:"delete",
        //             url: '/admin/alarm/'+id,
        //             success:function (res) {
        //                 if (res.status == 1){
        //                     swal(res.msg, "您已经永久删除了这条信息。", "success");
        //                     location.reload();
        //                 }else {
        //                     swal(res.msg, "请稍后重试。", "waring");
        //                 }
        //             },
        //         });
        //         $.ajax();
        //     });
        // });
    </script>
@endsection