@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    @if($user->id)
                        <h5>修改用户</h5>
                    @else
                        <h5>添加用户</h5>
                    @endif
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
                    @if(empty($user->id))
                        <form method="post" action="{{ route('admin.users.store') }}" class="form-horizontal">
                            @else
                                <form method="POST" action="{{ route('admin.users.update',$user->id) }}" class="form-horizontal">
                                    <input type="hidden" name="_method" value="PUT">
                                    @endif
                                    <div class="form-group">
                                        @if( count($errors) >0)
                                            @foreach($errors->all() as $error)
                                                <p class="text-danger text-center">{{ $error }}</p>
                                            @endforeach
                                        @endif
                                    </div>
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">姓名：</label>

                                        <div class="col-sm-6">
                                            <input name="name" id="name" type="text" class="form-control" value="{{ old('name',$user->name) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">手机号：</label>

                                        <div class="col-sm-6">
                                            <input name="phone" id="phone" type="phone" class="form-control" value="{{ old('phone',$user->phone) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">密码：</label>

                                        <div class="col-sm-6">
                                            <input name="password" id="password" type="password" class="form-control" value="{{ old('password',$user->password) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">用户角色：</label>
                                        <div class="col-sm-6">
                                            {{--multiple--}}
                                            <select class="chosen-select" data-placement="选择用户角色" name="role" style="width: 350px;" tabindex="2">
                                                <option value="">选择角色</option>
                                                <option value="1" @if($user->role == 1) selected="selected" @endif>小区管理员</option>
                                                <option value="2" @if($user->role == 2) selected="selected" @endif>小区住户</option>
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

@section('scripts')
    <!-- Chosen -->
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>
@endsection
<!-- 自定义js -->
{{--<script src="{{ asset('assets/admin/js/content.js?v=1.0.0') }}"></script>--}}
@section('javascript')
    <script>
        /*$(document).ready(function () {
            $("#add_device").onclick(function () {
                var data = {
                    'truck_pass' : $('#truck_name').val(),
                };
                console.log(data);
            })
        });*/
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
        }
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
    </script>
@endsection
