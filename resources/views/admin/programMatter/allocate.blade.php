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
                                任务标题：{{ $matter->title }} <br/>
                                任务地址：{{ $matter->address }} <br/>
                                内容描述：{{ $matter->content }} <br/>
                            </div>
                        </div>
                        {{--隐藏任务id--}}
                        <input type="hidden" name="matter_id" value="{{ $matter->id }}">
                        <input type="hidden" name="category_id" value="{{ $matter->category_id }}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">任务分类(大类)：</label>
                            <div class="col-sm-6">
                                <select id="category">
                                    <option>----请选择任务分类----</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">任务分类(小类)：</label>
                            <div class="col-sm-6">
                                <select id="responsibly">

                                </select>
                                {{--<select class="form-control chosen-select" id="responsibility" name="" tabindex="2" required>
                                    <option value="" hidden disabled selected>请选择任务分类</option>
                                    @foreach ($responsibilities as $responsibility)
                                        <option class="responsibility"  date-category="{{ $responsibility->category_id }}" value="{{ $responsibility->id }}">{{ $responsibility->item }}</option>
                                    @endforeach
                                </select>--}}
                                {{--<select class="responsibility">
                                    <option>----请选择城市----</option>
                                </select>
                                <select class="responsibility">
                                    @foreach ($responsibilities as $responsibility)
                                        <option  data-category="{{ $responsibility->category_id }}" value="{{ $responsibility->id }}">{{ $responsibility->item }}</option>
                                    @endforeach
                                </select>--}}
                            </div>
                        </div>
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
{{--    <script src="{{ asset('assets/admin/js/demo/form-advanced-demo.js') }}"></script>--}}
    <script>
        var currentShowResponsibility=0;
        $(document).ready(function(){
            $("#category").change(function(){
                $("#category option").each(function(i,o){
                    if($(this).attr("selected"))
                    {
                        console.log(111);

                        $(".responsibility").hide();
                        $(".responsibility").data('').show();
                        currentShowCity=i;
                    }
                });
            });
            $("#category").change();
        });
        function getSelectValue(){
            alert("1级="+$("#province").val());

            $(".city").each(function(i,o){

                if(i == currentShowCity){
                    alert("2级="+$(".city").eq(i).val());
                }
            });
        }

    </script>
@endsection

