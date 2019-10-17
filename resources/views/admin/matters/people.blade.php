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
                    <h5>群众上报任务 <small>分类，查找</small></h5>
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
                            <th>标题</th>
                            <th>问题描述</th>
                            <th>地址</th>
                            <th>现场查看</th>
                            <th>添加时间</th>
                            <th>是否分配</th>
                            <th>是否公开</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($matters as $matter)
                            <tr class="gradeC">
                                <td class="check_id">
                                    {{ $matter->id }}
                                </td>
                                <td>{{ $matter->title }}</td>
                                <td style="max-width: 580px;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $matter->content }}</td>
                                <td>{{ $matter->address }}</td>
                                <td>
                                    @if( $matter->status  == 0)
                                        <button class="btn btn-sm btn-warning btn-circle" type="button"><i class="fa fa-times"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-info btn-circle" type="button"><i class="fa fa-check"></i>
                                        </button>
                                    @endif
                                </td>
                                <td>{{ $matter->created_at }}</td>
                                <td>
                                    @if($matter->allocate == 0)
                                        <a href="{{ route('admin.people.allocate', ['id' => $matter->id]) }}"><button type="button" class="btn btn-warning btn-xs">未分配</button></a>
                                    @else
                                        <button type="button" class="btn btn-primary btn-xs" disabled>已分配</button>
                                    @endif
                                </td>
                                <td>
                                    @if($matter->open == 0)
                                        <a href="{{ route('admin.people.open', ['id' => $matter->id, 'open' => '1']) }}"><button type="button" class="btn btn-warning btn-xs">隐藏</button></a>
                                    @else
                                        <a href="{{ route('admin.people.open', ['id' => $matter->id, 'open' => '0']) }}"><button type="button" class="btn btn-primary btn-xs">公开</button></a>
                                    @endif
                                </td>
                                <td class="center">
                                    <a href="{{ route('admin.people.edit',['id' => $matter->id]) }}"><button type="button" class="btn btn-primary btn-xs">编辑</button></a>
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $matter->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>问题描述</th>
                            <th>地址</th>
                            <th>现场查看</th>
                            <th>添加时间</th>
                            <th>是否分配</th>
                            <th>是否公开</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                    <div id="outerdiv" style="position:fixed;top:135px;left:330px;background:rgba(0,0,0,0);z-index:2;width:100%;height:100%;display:none;">
                    <div id="innerdiv" style="position:absolute;">
                        <img id="bigimg" style="border:5px solid #fff;" src="" />
                    </div>
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

        $('#fp-btn').click(function () {
            $.ajax({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                url: 'matters/users',
                datatype: 'json',
                success: function (data) {
                    for (var i = 0; i < data.length; i++) {
                        var option = document.createElement("option");
                        $(option).val(data[i].id);
                        $(option).text(data[i].name);
                        $('#userId').append(option);
                    }
                }
            });
        });

        $('#model_reset').click(function () {
           location.reload();
        });

        function fun(){
            obj = document.getElementsByName("matter");
            check_val = [];
            for(k in obj){
                if(obj[k].checked)
                    check_val.push(obj[k].value);
            }
            $("#matter_id").val(check_val.join(","));
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