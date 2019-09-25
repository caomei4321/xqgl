@extends('admin.common.app')

@section('styles')
    <!-- iCheck -->
    <link href="{{ asset('assets/admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet">

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
                    @if(empty($matter->id))
                        <form method="post" action="{{ route('admin.matters.store') }}" class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-group">
                                @if( count($errors) >0)
                                    @foreach($errors->all() as $error)
                                        <p class="text-danger text-center">{{ $error }}</p>
                                    @endforeach
                                @endif
                            </div>
                            {{ csrf_field() }}
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
                                <label class="col-sm-2 control-label">标题：</label>

                                <div class="col-sm-6">
                                    <input name="title"  type="text" class="form-control" value="{{ old('title',$matter->title) }}">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">联系地址：</label>

                                <div class="col-sm-6">
                                    <input name="address"  type="text" class="form-control" value="{{ old('address',$matter->address) }}">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">问题描述：</label>

                                <div class="col-sm-6">
                                    <textarea name="content" class="form-control" id="editor"  rows="6" placeholder="请输入至少三个字符的内容">{{ old('content', $matter->content) }}</textarea>
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
                                        <img src="{{ old('image', $matter->image) }}" id="img3" style="width: 160px;" />
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
                            @else
                                <form method="POST" action="{{ route('admin.matters.update',$matter->id) }}" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="_method" value="PUT">

                                    <div class="form-group">
                                        @if( count($errors) >0)
                                            @foreach($errors->all() as $error)
                                                <p class="text-danger text-center">{{ $error }}</p>
                                            @endforeach
                                        @endif
                                    </div>
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">受理员编号：</label>

                                        <div class="col-sm-6">
                                            <input name="accept_num"  type="text" class="form-control" value="{{ old('accept_num',$matter->accept_num) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">办结时限：</label>

                                        <div class="col-sm-6">
                                            <input name="time_limit"  type="text" class="form-control" value="{{ old('time_limit',$matter->time_limit) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">工单编号：</label>

                                        <div class="col-sm-6">
                                            <input name="work_num"  type="text" class="form-control" value="{{ old('work_num',$matter->work_num) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">紧急程度：</label>

                                        <div class="col-sm-6">
                                            <input name="level"  type="text" class="form-control" value="{{ old('level',$matter->level) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">来电类别：</label>

                                        <div class="col-sm-6">
                                            <input name="type"  type="text" class="form-control" value="{{ old('type',$matter->type) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">信息来源：</label>

                                        <div class="col-sm-6">
                                            <input name="source"  type="text" class="form-control" value="{{ old('source',$matter->source) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否回复：</label>

                                        <div class="col-sm-6">
                                            <input name="is_reply"  type="text" class="form-control" value="{{ old('is_reply',$matter->is_reply) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">是否保密：</label>

                                        <div class="col-sm-6">
                                            <input name="is_secret"  type="text" class="form-control" value="{{ old('is_secret',$matter->is_secret) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">联系人：</label>

                                        <div class="col-sm-6">
                                            <input name="contact_name"  type="text" class="form-control" value="{{ old('contact_name',$matter->contact_name) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">联系电话：</label>

                                        <div class="col-sm-6">
                                            <input name="contact_phone"  type="text" class="form-control" value="{{ old('contact_phone',$matter->contact_phone) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">回复备注：</label>

                                        <div class="col-sm-6">
                                            <input name="reply_remark"  type="text" class="form-control" value="{{ old('reply_remark',$matter->reply_remark) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">转办意见：</label>

                                        <div class="col-sm-6">
                                            <input name="suggestion"  type="text" class="form-control" value="{{ old('suggestion',$matter->suggestion) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">转办单位领导批示：</label>

                                        <div class="col-sm-6">
                                            <input name="approval"  type="text" class="form-control" value="{{ old('approval',$matter->approval) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">办理结果：</label>

                                        <div class="col-sm-6">
                                            <input name="result"  type="text" class="form-control" value="{{ old('result',$matter->result) }}">
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
                                        <label class="col-sm-2 control-label">标题：</label>

                                        <div class="col-sm-6">
                                            <input name="title"  type="text" class="form-control" value="{{ old('title',$matter->title) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">联系地址：</label>

                                        <div class="col-sm-6">
                                            <input name="address"  type="text" class="form-control" value="{{ old('address',$matter->address) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">问题描述：</label>

                                        <div class="col-sm-6">
                                            <textarea name="content" class="form-control" id="editor"  rows="6" placeholder="请输入至少三个字符的内容">{{ old('content', $matter->content) }}</textarea>
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
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary" id="add_device">提交</button>
                                        </div>
                                    </div>
                                </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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

