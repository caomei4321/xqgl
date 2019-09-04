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
                    <h5>事项责任指导 <small>查找</small></h5>
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
                    <a href="{{ route('admin.responsibility.create') }}"><button class="btn btn-info " type="button"><i class="fa fa-paste"></i> 添加指导</button>
                    </a>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>所属分类</th>
                            <th>具体事项</th>
                            <th>县级部门职责</th>
                            <th>乡镇（街道）职责</th>
                            <th>法律依据</th>
                            <th>部门</th>
                            <th>镇街</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($responsibility as $res)
                            <tr class="gradeC">
                                <td>{{ $res->id }}</td>
                                <td style="width: 70px">{{ $res->category->name }}</td>
                                <td>{{ $res->item }}</td>
                                <td>{{ $res->county }}</td>
                                <td>{{ $res->town }}</td>
                                <td>{{ $res->legal_doc }}</td>
                                <td style="width: 70px">{{ $res->subject_duty ===0 ? '主体责任' : '配合责任' }}</td>
                                <td style="width: 70px">{{ $res->cooperate_duty ===1 ? '配合责任' : '主体责任' }}</td>
                                <td class="center">
                                    <a href="{{ route('admin.responsibility.edit',['responsibility' => $res->id]) }}"><button type="button" class="btn btn-primary btn-xs">编辑</button></a>
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $res->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>所属分类</th>
                            <th>具体事项</th>
                            <th>主体责任</th>
                            <th>配合责任</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $responsibility->links() }}
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
                    url: '/admin/responsibility/'+id,
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