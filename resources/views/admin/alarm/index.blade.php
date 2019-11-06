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
                            <th>告警时间</th>
                            <th>告警类型</th>
                            <th>告警位置</th>
                            <th>是否分配</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($matters as $matter)
                            <tr class="gradeC">
                                <td>{{ $matter->id }}</td>
                                <td>{{ $matter->device_serial }}</td>
                                <td>{{ $matter->alarm_start }}</td>
                                <td>
                                    @if($matter->alarm_type == 'leftdetection')
                                        物品遗留
                                    @elseif($matter->alarm_type == 'enterareadetection')
                                        进入区域
                                    @else
                                        {{ $matter->alarm_type }}
                                    @endif
                                </td>
                                <td>{{ $matter->address }}</td>
                                <td>
                                    @if($matter->allocate == 0)
                                        <a href="{{ route('admin.alarm.allocate', ['id' => $matter->id]) }}"><button type="button" class="btn btn-warning btn-xs">未分配</button></a>
                                    @else
                                        <button type="button" class="btn btn-primary btn-xs" disabled>已分配</button>
                                    @endif
                                </td>
                                <td class="center">
                                    <button class="btn btn-warning btn-xs delete" data-id="{{$matter->id}}">删除</button>
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
                            <th>告警位置</th>
                            <th>是否分配</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $matters->links() }}
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
                    url: '/admin/matters/'+id,
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