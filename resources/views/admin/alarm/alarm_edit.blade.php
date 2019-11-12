@extends('admin.common.app')

@section('styles')
    <!-- iCheck -->
    <link href="{{ asset('assets/admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                        <h5>修改告警事件</h5>
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
                    <form method="POST" action="{{ route('admin.alarm.update') }}" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{ $matter->id }}">

                        <div class="form-group">
                            @if( count($errors) >0)
                                @foreach($errors->all() as $error)
                                    <p class="text-danger text-center">{{ $error }}</p>
                                @endforeach
                            @endif
                        </div>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">标题：</label>

                            <div class="col-sm-6">
                                <input name="title"  type="text" class="form-control" value="{{ old('title',$matter->title) }}">
                            </div>
                        </div>
                        @if($user_id)
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">已分配执行人：</label>

                                <div class="col-sm-6">
                                    <select data-placeholder="选择执行人..." name="user_id" class="chosen-select" style="width:350px;" tabindex="2">
                                        @foreach($users as $user)
                                            <option value="{{ old('user_id',$user->id) }}" hassubinfo="true" {{$user->id == $user_id ? 'selected' : ''}} >{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">地址：</label>

                            <div class="col-sm-6">
                                <input name="address"  type="text" class="form-control" value="{{ old('address',$matter->address) }}">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">问题描述：</label>

                            <div class="col-sm-6">
                                <textarea name="content" class="form-control" rows="3" placeholder="请输入至少三个字符的内容">{{ old('content', $matter->content) }}</textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">问题分类：</label>

                            <div class="col-sm-6">
                                <select class="form-control" name="category_id" required>
                                    <option value="" hidden disabled selected>请选择分类</option>
                                    @foreach ($category as $value)
                                        <option value="{{ $value->id }}" {{ $matter->category_id == $value->id ? 'selected': '' }}>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">告警类型：</label>

                            <div class="col-sm-6">
                                <input type="text" name="alarm_type" class="form-control" value="{{ old('alarm_type',$matter->alarm_type) }}" >
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">告警时间：</label>

                            <div class="col-sm-6">
                                <div class="col-sm-6">
                                    <input class="form-control layer-date" name="alarm_start" placeholder="YYYY-MM-DD hh:mm:ss" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" value="{{ old('alarm_start', $matter->alarm_start) }}">
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">告警图片：</label>

                            <div class="col-sm-6">
                                <div id="file-pretty">
                                    <div id="prompt3">
                                        <input type="file" name="alarm_pic_url" class="form-control" id="file" onchange="changepic(this)" accept="image/*">
                                    </div>
                                    <a class="fancybox" id="img" href="{{ $matter->alarm_pic_url }}" >
                                        <img src="{{ old('alarm_pic_url', $matter->alarm_pic_url) }}" id="img3"  style="width: 160px;" />
                                    </a>
                                </div>
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
    <!-- iCheck -->
    <script src="{{ asset('assets/admin/js/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>
    <!-- Fancy box -->
    <script src="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/layer/laydate/laydate.js') }}"></script>

    <script src="{{ asset('assets/admin/js/demo/form-advanced-demo.js') }}"></script>
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

        function changepic() {
            // $("#prompt3").css("display", "none");
            var reads = new FileReader();
            f = document.getElementById('file').files[0];
            reads.readAsDataURL(f);
            reads.onload = function(e) {
                document.getElementById('img3').src = this.result;
                $("#img3").css("display", "block");
            };
        }
    </script>
@endsection

