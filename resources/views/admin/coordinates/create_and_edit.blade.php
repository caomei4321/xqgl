@extends('admin.common.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    @if($coordinate->id)
                        <h5>修改坐标</h5>
                    @else
                        <h5>添加坐标</h5>
                    @endif
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="">选项1</a>
                            </li>
                            <li><a href="">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @if(empty($coordinate->id))
                        <form method="post" action="{{ route('admin.coordinates.store') }}" class="form-horizontal" enctype="multipart/form-data">
                            @else
                                <form method="POST" action="{{ route('admin.coordinates.update',$coordinate->id) }}" class="form-horizontal" enctype="multipart/form-data">
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
                                        <label class="col-sm-2 control-label">编号：</label>

                                        <div class="col-sm-6">
                                            <input name="number"  type="text" class="form-control" value="{{ old('number',$coordinate->number) }}" required>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group" id="inputArray">
                                        <label class="col-sm-2 control-label">坐标：</label>

                                        <div class="col-sm-6">
                                            <input style="display: inline-block; width: 200px;" name="lng[]"  type="text" class="form-control" value="{{ old('lng') }}" placeholder="请输入经度坐标" required>
                                            <input style="display: inline-block; width: 200px;" name="lat[]"  type="text" class="form-control" value="{{ old('lat') }}" placeholder="请输入维度坐标" required>
                                        </div>

                                        <div class="col-sm-6" style="margin-left: 275px; margin-top: 10px;">
                                            <input style="display: inline-block; width: 200px;" name="lng[]"  type="text" class="form-control" value="{{ old('lng') }}" placeholder="请输入经度坐标" required>
                                            <input style="display: inline-block; width: 200px;" name="lat[]"  type="text" class="form-control" value="{{ old('lat') }}" placeholder="请输入维度坐标" required>
                                        </div>

                                        <div class="col-sm-6" style="margin-left: 275px; margin-top: 10px;">
                                            <input style="display: inline-block; width: 200px;" name="lng[]"  type="text" class="form-control" value="{{ old('lng') }}" placeholder="请输入经度坐标" required>
                                            <input style="display: inline-block; width: 200px;" name="lat[]"  type="text" class="form-control" value="{{ old('lat') }}" placeholder="请输入维度坐标" required>
                                        </div>

                                        <div class="col-sm-6" style="margin-left: 275px; margin-top: 10px;">
                                            <input style="display: inline-block; width: 200px;" name="lng[]"  type="text" class="form-control" value="{{ old('lng') }}" placeholder="请输入经度坐标" required>
                                            <input style="display: inline-block; width: 200px;" name="lat[]"  type="text" class="form-control" value="{{ old('lat') }}" placeholder="请输入维度坐标" required>
                                            <button type="button" class="btn btn-info addInput">+</button>
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
        var MaxInput = 46;
        var InputArray = $('#inputArray');
        var AddButton = $('.addInput');
        var x = InputArray.length;
        var FieldCount = 1;
        $(AddButton).click(function (e) {
            if (x <= MaxInput){
                FieldCount ++;
                $(InputArray).append('<div class="col-sm-6" style="margin-left: 275px; margin-top: 10px;">\n' +
                    '                                            <input style="display: inline-block; width: 200px;" name="lng[]"  type="text" class="form-control" value="{{ old("lng") }}" placeholder="请输入经度坐标" required>\n' +
                    '                                            <input style="display: inline-block; width: 200px;" name="lat[]"  type="text" class="form-control" value="{{ old("lat") }}" placeholder="请输入维度坐标" required>\n' +
                    '                                            <button type="button" class="btn btn-info removeInput">-</button>\n' +
                    '                                        </div>');
                x++;
            }
            return false;
        });
        $("body").on("click", ".removeInput", function (e) {
            if ( x > 1 ) {
                $(this).parent('div').remove();
                x--;
            }
            return false;
        });
    </script>
@endsection

