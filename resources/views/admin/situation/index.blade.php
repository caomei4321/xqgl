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
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>工单编号</th>
                            <th>执行人</th>
                            <th>现场处理图片</th>
                            <th>处理信息</th>
                            <th>时间</th>
                            <th>截止日期</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($situations as $situation)
                            <tr class="gradeC">
                                <td>{{ $situation->matter->id }}</td>
                                <td>{{ $situation->matter->work_num }}</td>
                                <td>{{ $situation->user->name }}</td>
                                <td>
                                    <a class="fancybox" id="img" href="{{ $situation->see_image }}" >
                                        <img src="{{ $situation->see_image }}"  style="width: 40px;" />
                                    </a>
                                    @foreach($situation->getImagesAttributes() as $image)
                                    <a class="fancybox" id="img" href="{{ $image }}" >
                                        <img src="{{ $image }}"  style="width: 40px;" />
                                    </a>
                                    @endforeach
                                </td>
                                <td>{{ $situation->information }}</td>
                                <td>{{ $situation->created_at }}</td>
                                <td>{{ $situation->matter->time_limit }}</td>
                                <td>
                                    @if($situation->status  == 0)
                                        <button class="btn btn-default btn-xs" type="button"><i class="fa fa-map-marker"></i>&nbsp;&nbsp;未处理</button>
                                    @elseif( $situation->status  == 2)
                                        <button class="btn btn-xs btn-warning" type="button" readonly><i class="fa fa-warning"></i> <span class="bold">配合</span>
                                        </button>

                                    @elseif($situation->status == 3)
                                        <button class="btn btn-xs btn-info " type="button"><i class="fa fa-paste"></i> 处理中</button>
                                    @else
                                        <button class="btn btn-xs btn-info " type="button"><i class="fa fa-paste"></i> 完成</button>
                                    @endif
                                </td>
                                <td>
                                    @if($situation->status  == 2)
                                        <a href="{{ route('admin.situations.export', ['id' => $situation->id]) }}" class="btn btn-xs btn-danger"><i class="fa fa-warning"></i>转办单</a>
                                    @else
                                        <a href="{{ route('admin.situations.show', ['id' => $situation->id]) }}"><button class="btn btn-xs btn-success " type="button"><i class="fa fa-paste"></i> 查看</button></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>工单编号</th>
                            <th>执行人</th>
                            <th>现场处理图片</th>
                            <th>处理信息</th>
                            <th>时间</th>
                            <th>截止日期</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $situations->links() }}
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
                    url: '/admin/situation/'+id,
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