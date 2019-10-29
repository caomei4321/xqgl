@extends('admin.common.app')

@section('styles')
    <!-- Data Tables -->
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('assets/admin/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <!-- Data picker -->
    <link href="{{ asset('assets/admin/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><small>巡查上报问题</small></h5>
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
                    {{--<a href="{{ route('admin.patrolMatters.export') }}"><button class="btn btn-info " type="button"><i class="fa fa-paste"></i> Excel导出</button>
                    </a>--}}
                    <button class="btn btn-info " id="download" type="button"><i class="fa fa-paste"></i> 巡查事件上报统计</button>
                    <form id="form" method="get" action="">
                        <div class="form-group form-inline row text-left" id="data_5">
                            <label class="font-noraml">范围选择</label>
                            {{ csrf_field() }}
                            <div class="input-daterange input-group" id="datepicker">

                                <input type="text" class="input-sm form-control" name="start_time" value="{{ isset($filter['start_time']) ? $filter['start_time'] : '' }}" />
                                <span class="input-group-addon">到</span>
                                <input type="text" class="input-sm form-control" name="end_time" value="{{ isset($filter['end_time']) ? $filter['end_time'] : date("Y-m-d",time()) }}" />

                            </div>
                            <div class="form-group">

                                <input type="submit" class="btn btn-primary" id="search" value="搜索">
                            </div>

                        </div>
                    </form>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>问题描述</th>
                            <th>现场图片</th>
                            <th>添加时间</th>
                            <th>是否处理完成</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($patrolMatters as $patrolMatter)
                            <tr class="gradeC">
                                <td>{{ $patrolMatter->id }}</td>
                                <td>{{ $patrolMatter->title }}</td>
                                <td>{{ $patrolMatter->content }}</td>
                                <td><image src="{{ $patrolMatter->img_url }}"  style="width: 40px;"/></td>
                                <td class="center">{{ $patrolMatter->created_at }}</td>
                                <td class="center">
                                    @if( $patrolMatter->status  == 0)
                                        <button class="btn btn-sm btn-warning btn-circle" type="button"><i class="fa fa-times"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-info btn-circle" type="button"><i class="fa fa-check"></i>
                                        </button>
                                    @endif
                                </td>
                                <td class="center">
                                    <a href="{{ route('admin.patrolMatters.show',['patrolMatters' => $patrolMatter->id]) }}"><button type="button" class="btn btn-danger btn-xs">查看</button></a>
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $patrolMatter->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>问题描述</th>
                            <th>现场图片</th>
                            <th>添加时间</th>
                            <th>是否处理完成</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $patrolMatters->links() }}
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
    <!-- Data picker -->
    <script src="{{ asset('assets/admin/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            $('#datepicker').datepicker();
            var config = {
                '.chosen-select': {},
                '.chosen-select-deselect': {
                    allow_single_deselect: true
                },
                '.chosen-select-no-single': {
                    disable_search_threshold: 10
                },
                '.chosen-select-no-results': {
                    no_results_text: 'Oops, nothing found!'
                },
                '.chosen-select-width': {
                    width: "95%"
                }
            };
            $('.dataTables-example').dataTable({
                "lengthChange": false,
                "paging": false,
                "order": [[ 3, 'desc' ]],
            });
            $('#download').click(function () {
                $("#form").attr('action',"{{ route('admin.patrolMatters.export') }}");
                $("#form").submit();
            });
            $('#search').click(function () {
                $("#form").attr('action',"{{ route('admin.patrolMatters.search') }}");
                $("#form").submit();
            })
        })
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
                    url: '/admin/patrolMatter/'+id,
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