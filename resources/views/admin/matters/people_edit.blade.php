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
                    @if($matter->id)
                        <h5>修改问题</h5>
                    @else
                        <h5>添加问题</h5>
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
                    <form method="POST" action="{{ route('admin.people.update') }}" class="form-horizontal" enctype="multipart/form-data">
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
                                <input type="hidden" name="old_user_id" value="{{ $user_id }}">
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
                                <textarea name="content" class="form-control" rows="6" placeholder="请输入至少三个字符的内容">{{ old('content', $matter->content) }}</textarea>
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
                            <label class="col-sm-2 control-label">图片依据：</label>

                            <div class="col-sm-6">
                                <div id="file-pretty">
                                    <div id="prompt3">
                                        <input type="file" name="image" class="form-control" id="file" onchange="changepic(this)" accept="image/*">
                                    </div>
                                    <a class="fancybox" id="img" href="{{ $matter->image }}" >
                                        <img src="{{ old('image', $matter->image) }}" id="img3"  style="width: 160px;" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>

                            <div class="col-sm-6">
                                <div id="file-pretty">
                                    <div id="prompt3">

                                    </div>
                                    @for( $i = 1; $i < count($many_images); $i++)
                                    <a class="fancybox" id="img" href="{{ $many_images[$i] }}" >
                                        <img src="{{ $many_images[$i] }}" id="img3"  style="width: 160px;" />
                                    </a>
                                    @endfor
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

