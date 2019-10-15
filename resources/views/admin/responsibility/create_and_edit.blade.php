@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    @if($responsibility->id)
                        <h5>修改责任指导</h5>
                    @else
                        <h5>添加责任指导</h5>
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
                    @if(empty($responsibility->id))
                        <form method="post" action="{{ route('admin.responsibility.store') }}" class="form-horizontal">
                            @else
                                <form method="POST" action="{{ route('admin.responsibility.update',$responsibility->id) }}" class="form-horizontal">
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
                                        <label class="col-sm-2 control-label">选择分类：</label>

                                        <div class="col-sm-6">
                                            <select class="form-control" name="category_id" required>
                                                <option value="" hidden disabled selected>请选择分类</option>
                                                @foreach ($category as $value)
                                                    <option value="{{ $value->id }}" {{ $responsibility->category_id == $value->id ? 'selected': '' }}>{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">具体事项：</label>

                                        <div class="col-sm-6">
                                            <input name="item"  type="text" class="form-control" value="{{ old('item',$responsibility->item) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">县级部门职责：</label>

                                        <div class="col-sm-6">
                                            <textarea name="county" class="form-control" id=""  rows="6" placeholder="请输入至少三个字符的内容" required>{{ old('county', $responsibility->county) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">乡镇(街道)职责：</label>

                                        <div class="col-sm-6">
                                            <textarea name="town" class="form-control" id=""  rows="6" placeholder="请输入至少三个字符的内容" required>{{ old('town', $responsibility->town) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">法律法规及文件依据：</label>

                                        <div class="col-sm-6">
                                            <input name="legal_doc"  type="text" class="form-control" value="{{ old('legal_doc',$responsibility->legal_doc) }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">处理时限(小时)：</label>

                                        <div class="col-sm-6">
                                            <input name="deadline"  type="number" class="form-control" value="{{ old('deadline',$responsibility->deadline) }}">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">主体责任：</label>

                                        <div class="col-sm-6">
                                            <select class="form-control" name="subject_duty" required>
                                                <option value="0" {{ $responsibility->subject_duty == 0 ? 'selected' : '' }}>部门</option>
                                                <option value="1" {{ $responsibility->subject_duty == 1 ? 'selected' : '' }}>镇街</option>
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

@section('javascript')
    <script>
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
