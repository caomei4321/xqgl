@extends('admin.common.app')

@section('styles')
    <!-- Data Tables -->
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('assets/admin/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">

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
                    <a data-toggle="modal" class="btn btn-primary" href="#modal-form">添加图片</a>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>图片</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($programImages as $programImage)
                            <tr class="gradeC">
                                <td>{{ $programImage->id }}</td>

                                <td>
                                    <a class="fancybox" id="img" href="{{ $programImage->img_url }}" >
                                        <img src="{{ $programImage->img_url }}"  style="width: 40px;" />
                                    </a>
                                </td>
                                <td class="center">{{ $programImage->created_at }}</td>
                                <td class="center">
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $programImage->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>图片</th>
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
                            <h3 class="m-t-none">上传图片</h3>

                            <p class="entity">上传图片</p>

                            {{--<form role="form">--}}
                            <label class="col-sm-2 control-label">图片依据：</label>

                            <form id="programImage">
                            <div class="col-sm-6">
                                <div id="file-pretty">
                                    <div id="prompt3">
                                        <input type="file" name="image" class="form-control" id="file" onchange="changepic(this)" accept="image/*">
                                    </div>
                                    <a class="fancybox" id="img" href="" >
                                        <img src="" id="img3"  style="width: 160px;" />
                                    </a>
                                </div>
                            </div>
                            </form>

                                {{--<div class="form-group">
                                    <label>设备名：</label>
                                    <input id="entity_name" name="entity_name" type="text" placeholder="设备名不可重复" class="form-control col-sm-6">
                                </div>
                                <div class="form-group">
                                    <label>说明：</label>
                                    <input id="entity_desc" name="entity_desc" type="text" placeholder="设备说明" class="form-control">
                                </div>--}}
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
    <!-- Fancy box -->
    <script src="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.js') }}"></script>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            $('.fancybox').fancybox({
                openEffect: 'none',
                closeEffect: 'none'
            });
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
                    url: '/admin/programImage/'+id,
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

        function changepic() {
            var reads = new FileReader();
            f = document.getElementById('file').files[0];
            reads.readAsDataURL(f);
            reads.onload = function(e) {
                document.getElementById('img3').src = this.result;
                $("#img3").css("display", "block");
            };
        }

        $('#add-entity').click(function () {
            var formData = new FormData($("#programImage")[0]);
            formData.append('image', $('#file')[0].files[0]);
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type:"POST",
                url: '/admin/programImage',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
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