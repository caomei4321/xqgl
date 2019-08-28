@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                        <h5>派发任务</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="form_basic.html#">选项1</a>
                            </li>
                            <li><a href="form_basic.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="POST" action="{{ route('admin.matters.allocates') }}" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group">
                            @if( count($errors) >0)
                                @foreach($errors->all() as $error)
                                    <p class="text-danger text-center">{{ $error }}</p>
                                @endforeach
                            @endif
                        </div>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">任务信息：</label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-6">
                                任务标题：{{ $matterInfo->title }} <br/>
                                任务地址：{{ $matterInfo->address }} <br/>
                                内容描述：{{ $matterInfo->content }} <br/>
                            </div>
                        </div>
                        {{--隐藏任务id--}}
                        <input type="hidden" name="matter_id" value="{{ $matterInfo->id }}">
                        <input type="hidden" name="category_id" value="{{ $matterInfo->category_id }}">
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">选择执行人：</label>
                            <div class="col-sm-6">
                                <select class="form-control chosen-select" name="user_id" tabindex="2" required>
                                    <option value="" hidden disabled selected>请选择人员</option>
                                    @foreach ($users as $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary" id="add_device">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('assets/admin/js/demo/form-advanced-demo.js') }}"></script>
    <script>

    </script>
@endsection

