@extends('admin.common.app')

@section('styles')
    {{--<link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                        <h5>角色信息</h5>
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
                        <form method="" action="" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">标识：</label>
                                <div class="col-sm-6">
                                    <input type="text" id="phone" name="phone" class="form-control" value="{{ $role->name }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">标识：</label>
                                <div class="col-sm-6">
                                    <input type="text" id="phone" name="phone" class="form-control" value="{{ $role->name }}">
                                </div>
                            </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">拥有权限：</label>

                                        <div class="col-sm-6">
                                            <p>
                                                @foreach($rolePermissions as $permissionName)
                                                {{--{{ $administrator->getRoleNames() }}--}}
                                                <button type="button" class="btn btn-outline btn-info">{{ $permissionName->mark }}</button>
                                                @endforeach
                                            </p>
                                            {{--<select class="chosen-select" data-placement="所属角色" name="administrator_roles[]" multiple style="width: 350px;" tabindex="2">
                                                @foreach($roles as $role)
                                                     <option value="{{ $role->id }}" @if (in_array($role->name,$administrator_roles)) selected="selected" @endif>{{ $role->name }}</option>
                                                @endforeach
                                            </select>--}}
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

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

    </script>
@endsection
