@extends('admin.common.app')

@section('styles')
    {{--<link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">--}}

    <link href="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>报修信息</h5>
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
                            <label class="col-sm-2 control-label">报修人：</label>
                            <div class="col-sm-6">
                                <input type="text" id="user" name="user" class="form-control" value="{{ $repair->user->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">联系方式：</label>
                            <div class="col-sm-6">
                                <input type="text" id="user_phone" name="user_phone" class="form-control" value="{{ $repair->user->phone }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">地址：</label>
                            <div class="col-sm-6">
                                <input type="text" id="address" name="address" class="form-control" value="{{ $repair->address }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">描述：</label>
                            <div class="col-sm-6">
                                <input type="text" id="description" name="description" class="form-control" value="{{ $repair->description }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">报修时间：</label>
                            <div class="col-sm-6">
                                <input type="text" id="created_at" name="created_at" class="form-control" value="{{ $repair->created_at }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">维修状态：</label>
                            <div class="col-sm-6">
                                <input type="text" id="status" name="status" class="form-control" value="{{ $repair->repair_status }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">报修图片：</label>
                            @if($repair->bad_img)
                                <a class="fancybox" href="{{ $repair->bad_img_url }}" >
                                    <img alt="image" src="{{ $repair->bad_img_url }}" />
                                </a>
                                {{--<div class="col-sm-6">--}}
                                {{--<img alt="image" src="{{ $user->image }}" width="200">--}}
                                {{--</div>--}}
                            @endif
                            {{--<button class="btn btn-info" type="button"><i class="fa fa-paste"></i> 上传图片</button>--}}
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">维修完成图片：</label>
                            @if($repair->good_img)
                                <a class="fancybox" href="{{ $repair->good_img_url }}" >
                                    <img alt="image" src="{{ $repair->good_img_url }}" />
                                </a>
                                {{--<div class="col-sm-6">--}}
                                {{--<img alt="image" src="{{ $user->image }}" width="200">--}}
                                {{--</div>--}}
                            @endif
                            {{--<button class="btn btn-info" type="button"><i class="fa fa-paste"></i> 上传图片</button>--}}
                        </div>
                        <div class="hr-line-dashed"></div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Fancy box -->
    <script src="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.js') }}"></script>
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
        $(document).ready(function () {
            /*$("#add_device").onclick(function () {
                var data = {
                    'name': $("#name").value,
                    'device_no': $("#device_no").value,
                    'address': $("#address").value,
                    'remark' :$("#remark").value
                };
                console.log(data);
            })*/
            $('.fancybox').fancybox({
                openEffect: 'none',
                closeEffect: 'none'
            });
            $('.dataTables-example').dataTable({
                "lengthChange": false,
                "paging": false,
                "order": [[ 3, 'desc' ]],
            });
        });

    </script>
@endsection
