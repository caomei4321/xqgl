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
                    <a data-toggle="modal" class="btn btn-primary" href="#modal-form">添加设备</a>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>设备名称</th>
                            <th>设备描述</th>
                            <th>上次更新时间</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($entities as $entity)
                            <tr class="gradeC">
                                <td>{{ $entity->entity_name }}</td>
                                    <td>{{ isset($entity->entity_desc) ? $entity->entity_desc : ''}}</td>

                                <td>{{ $entity->modify_time }}</td>
                                <td class="center">{{ $entity->create_time }}</td>
                                <td class="center">
                                    <button class="btn btn-warning btn-xs delete" data-entity_name="{{ $entity->entity_name }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>设备名称</th>
                            <th>设备描述</th>
                            <th>上次更新时间</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-form" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" id="entity">
                            <h3 class="m-t-none">添加设备</h3>

                            <p class="entity">欢迎添加设备</p>

                            {{--<form role="form">--}}
                                <div class="form-group">
                                    <label>设备名：</label>
                                    <input id="entity_name" name="entity_name" type="text" placeholder="设备名不可重复" class="form-control col-sm-6">
                                </div>
                                <div class="form-group">
                                    <label>说明：</label>
                                    <input id="entity_desc" name="entity_desc" type="text" placeholder="设备说明" class="form-control">
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="button" id="add-entity"><strong>添加</strong>
                                    </button>

                                </div>
                            {{--</form>--}}
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
            var entity_name = $(this).data('entity_name');
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
                    type:"get",
                    url: '/admin/entities/'+entity_name+'/destroy',
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

        $('#add-entity').click(function () {
            var entity_name = $('#entity_name').val();
            var entity_desc = $('#entity_desc').val();
            var data = {
                'entity_name':entity_name,
                'entity_desc':entity_desc
            };
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"POST",
                url: '/admin/entities',
                data: data,
                success:function (res) {
                    if (res.status == 1){
                        swal(res.msg, "添加成功。", "success");
                        location.reload();
                    }else {
                        swal(res.msg, "请稍后重试。", "waring");
                    }
                },
                error:function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    if (XMLHttpRequest.responseJSON.errors) {
                        var errors = XMLHttpRequest.responseJSON.errors;
                        $.each(errors,function (i,item) {
                            $.each(item,function (j,items) {
                                console.log(items);
                                $('.entity').append('<p class="text-danger text-center">'+items+'</p>');
                            })
                        })
                    }
                }
            });
            $.ajax();
        })
    </script>
@endsection