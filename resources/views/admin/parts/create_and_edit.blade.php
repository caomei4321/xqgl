@extends('admin.common.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    @if($part->id)
                        <h5>修改部件</h5>
                    @else
                        <h5>添加部件</h5>
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
                    @if(empty($part->id))
                        <form method="post" action="{{ route('admin.part.store') }}" class="form-horizontal" enctype="multipart/form-data">
                            @else
                                <form method="POST" action="{{ route('admin.part.update',$part->id) }}" class="form-horizontal" enctype="multipart/form-data">
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
                                        <label class="col-sm-2 control-label">物品：</label>

                                        <div class="col-sm-6">
                                            <input name="things"  type="text" class="form-control" value="{{ old('things',$part->things) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">编号：</label>

                                        <div class="col-sm-6">
                                            <input name="num"  type="text" class="form-control" value="{{ old('num',$part->num) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">位置：</label>

                                        <div class="col-sm-6">
                                            <input name="address"  type="text" class="form-control" value="{{ old('address',$part->address) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">经度：</label>

                                        <div class="col-sm-6">
                                            <input name="longitude"  type="text" class="form-control" value="{{ old('longitude',$part->longitude) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">纬度：</label>

                                        <div class="col-sm-6">
                                            <input name="latitude"  type="text" class="form-control" value="{{ old('latitude',$part->latitude) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">种类：</label>

                                        <div class="col-sm-6">
                                            <select class="form-control" name="kind_id" required>
                                                <option value="" hidden disabled selected>请选择种类</option>
                                                <option value="1" {{ $part->kind_id == 1 ? 'selected' : '' }}>垃圾桶</option>
                                                <option value="2" {{ $part->kind_id == 2 ? 'selected' : '' }}>广告牌</option>
                                                <option value="3" {{ $part->kind_id == 3 ? 'selected' : '' }}>公厕</option>
                                                <option value="4" {{ $part->kind_id == 4 ? 'selected' : '' }}>路灯</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">物品信息：</label>

                                        <div class="col-sm-6">
                                            <textarea name="info" class="form-control" id="editor"  rows="6" placeholder="请输入至少三个字符的内容">{{ old('info', $part->info) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">物品图片：</label>

                                        <div class="col-sm-6">
                                            <div id="file-pretty">
                                                <div id="prompt3">
                                                    <input type="file" name="image" class="form-control" id="file" onchange="changepic(this)" accept="image/*">
                                                </div>
                                                <img src="{{ old('image', $part->image) }}" id="img3" style="width: 160px;" />
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

@section('javascript')
    <script>
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

