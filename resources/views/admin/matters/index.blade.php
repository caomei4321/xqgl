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
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">选项1</a>
                            </li>
                            <li><a href="#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <a href="{{ route('admin.matters.create') }}"><button class="btn btn-info" type="button"><i class="fa fa-paste"></i> 添加任务</button>
                    </a>
                    <button class="btn btn-info" type="button" data-toggle="modal" data-target="#importModal" data-whatever="@mdo"><i class="fa fa-paste"></i> 12345任务导入</button>

                    <form action="{{ route('admin.matters.export') }}" method="get">
                        <div class="col-sm-2" style="display: inline-block">
                            <input class="form-control inline" type="date" name="timeStart">
                        </div>
                        <div class="col-sm-2" style="display: inline-block">
                            <input class="form-control inline" type="date" name="timeEnd">
                        </div>
                        <button class="btn btn-info" type="submit" style="display: inline-block"><i class="fa fa-paste"></i>12345任务统计</button>
                    </form>
                    {{--导入model start--}}
                    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="exampleModalLabel">New message</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <span style="font-size: 12px; color: red; opacity: 0.5; margin-bottom: 5px;">***导入之前请先下载模板，按指定格式填写数据***</span> <br>
                                        <a href="{{ route('admin.matters.download') }}">《Word导入模板》下载</a>
                                    </div>
                                    <form id="form1" action="{{ route('admin.matters.import') }}"  method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="import_file" class="control-label">选择指定格式Word文件</label>
                                            <input type="file" class="form-control"  name="import_file" value=""  required>
                                        </div>
                                        <button type="reset" class="btn btn-default" data-dismiss="modal">关闭</button>
                                        <button type="submit" class="btn btn-primary">确定</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--导入model end--}}
                    <div class="form-group">
                        @if( count($errors) >0)
                            @foreach($errors->all() as $error)
                                <p class="text-danger text-center">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                    <table class="table table-striped table-bordered table-hover dataTables-example" id="editable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>工单编号</th>
                            <th>问题描述</th>
                            <th>紧急程度</th>
                            <th>现场查看</th>
                            <th>添加时间</th>
                            <th>办结时限</th>
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
                                <td>{{ $matter->work_num }}</td>
                                <td style="max-width: 580px;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $matter->content }}</td>
                                <td>{{ $matter->level }}</td>
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
                                <td>{{ $matter->time_limit }}</td>
                                <td>
                                    @if($matter->allocate == 0)
                                        <a href="{{ route('admin.matters.allocate', ['id' => $matter->id]) }}"><button type="button" class="btn btn-warning btn-xs">未分配</button></a>
                                    @else
                                        <button type="button" class="btn btn-primary btn-xs" disabled>已分配</button>
                                    @endif
                                </td>
                                <td>
                                    @if($matter->open == 0)
                                        <a href="{{ route('admin.matters.open', ['id' => $matter->id, 'open' => '1']) }}"><button type="button" class="btn btn-warning btn-xs">隐藏</button></a>
                                    @else
                                        <a href="{{ route('admin.matters.open', ['id' => $matter->id, 'open' => '0']) }}"><button type="button" class="btn btn-primary btn-xs">公开</button></a>
                                    @endif
                                </td>
                                <td class="center">
                                    <a href="{{ route('admin.matters.edit',['matter' => $matter->id]) }}"><button type="button" class="btn btn-primary btn-xs">编辑</button></a>
                                    <button class="btn btn-warning btn-xs delete" data-id="{{ $matter->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>工单编号</th>
                            <th>问题描述</th>
                            <th>紧急程度</th>
                            <th>现场查看</th>
                            <th>添加时间</th>
                            <th>办结时限</th>
                            <th>是否分配</th>
                            <th>是否公开</th>
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