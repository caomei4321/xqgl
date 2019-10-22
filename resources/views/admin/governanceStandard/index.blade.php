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
                    <a id="add_standard" data-toggle="modal" class="btn btn-primary" href="#modal-form">添加标准</a>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>类别</th>
                            <th>治理标准</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($governanceStandards as $governanceStandard)
                            <tr class="gradeC">
                                <td>{{ $governanceStandard->id }}</td>
                                <td>{{ $governanceStandard->category }}</td>
                                <td>{{ $governanceStandard->standard }}</td>
                                <td>{{ $governanceStandard->created_at }}</td>
                                <td class="center">
                                    {{--<a href="{{ route('admin.users.edit',['user' => $governanceStandard->id]) }}"><button type="button" class="btn btn-primary btn-xs">编辑</button></a>
                                    <a href="{{ route('admin.users.show',['user' => $user->id]) }}"><button type="button" class="btn btn-danger btn-xs">查看</button></a>--}}
                                    <button class="btn btn-primary btn-xs edit"  data-toggle="modal"  href="#modal-form" data-id="{{ $governanceStandard->id }}">编辑</button>
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $governanceStandard->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>类别</th>
                            <th>治理标准</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $governanceStandards->links() }}
            </div>
        </div>
    </div>
    <div id="modal-form" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" id="">
                            <h3 class="m-t-none">上传图片</h3>

                            <p class="entity">上传图片</p>

                            <form id="form" role="form" method="post" action="{{ route('admin.governanceStandard.store') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>类别：</label>
                                <input id="category" name="category" type="text" placeholder="类别" class="form-control col-sm-6">
                            </div>
                            <div class="form-group">
                                <label>治理标准：</label>
                                <input id="standard" name="standard" type="text" placeholder="治理标准" class="form-control">
                            </div>
                            <div>
                                <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit" id="add-entity"><strong>添加</strong>
                                </button>

                            </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                    url: '/admin/governanceStandard/'+id,
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
        $('.edit').click(function () {
            var id = $(this).data('id');
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"get",
                url: '/admin/governanceStandard/'+id+'/edit',
                success:function (res) {
                    $("#form").append('<input class="put" type="hidden" name="_method" value="PUT">');
                    $("#form").attr('action','/admin/governanceStandard/'+id);
                    $("#category").val(res.data.category);
                    $("#standard").val(res.data.standard);
                },
            });
            $.ajax();
        });
        $('#add_standard').click(function () {
            //$("#form").append('<input type="hidden" name="_method" value="PUT">');
            $("#form").attr('action',"{{ route('admin.governanceStandard.store') }}");
            $("#category").val('');
            $("#standard").val('');
            $(".put").remove();
        });
    </script>
@endsection