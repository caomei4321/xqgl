@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>人员详情</h5>
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
                        <form method="get" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">姓名</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">联系电话</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->phone }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">职务</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->position }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">责任区域</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->responsible_area }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">派驻机构</label>

                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $user->resident_institution }}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </form>
                    </div>
                    <div class="ibox-content">
                        <h3>执行任务记录</h3>
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>任务ID</th>
                                <th>地点</th>
                                <th>完成情况</th>
                                <th>开始时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="gradeX">
                                <td>1</td>
                                <td class="center">xxx街道</td>
                                <td class="center">已完成</td>
                                <td class="center"> 2018-07-25 14:03:40</td>
                            </tr>
                            <tr class="gradeX">
                                <td>1</td>
                                <td class="center">xxx街道</td>
                                <td class="center">已完成</td>
                                <td class="center"> 2018-07-26 13:03:40</td>
                            </tr>
                            <tr class="gradeX">
                                <td>1</td>
                                <td class="center">xxx街道</td>
                                <td class="center">已完成</td>
                                <td class="center"> 2018-07-27 14:243:40</td>
                            </tr>
                            <tr class="gradeX">
                                <td>1</td>
                                <td class="center">xxx街道</td>
                                <td class="center">已完成</td>
                                <td class="center"> 2018-07-28 18:45:40</td>
                            </tr>
                            <tr class="gradeX">
                                <td>1</td>
                                <td class="center">xxx街道</td>
                                <td class="center">已完成</td>
                                <td class="center"> 2018-07-30 12:03:44</td>
                            </tr>
                            <tr class="gradeX">
                                <td>1</td>
                                <td class="center">xxx街道</td>
                                <td class="center">已完成</td>
                                <td class="center"> 2018-08-5 12:03:40</td>
                            </tr>
                            <tr class="gradeX">
                                <td>1</td>
                                <td class="center">xxx街道</td>
                                <td class="center">已完成</td>
                                <td class="center"> 2018-08-6 09:45:40</td>
                            </tr>
                            <tr class="gradeX">
                                <td>1</td>
                                <td class="center">xxx街道</td>
                                <td class="center">已完成</td>
                                <td class="center"> 2018-08-25 14:03:40</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>任务ID</th>
                                <th>地点</th>
                                <th>完成情况</th>
                                <th>开始时间</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
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

    </script>
@endsection