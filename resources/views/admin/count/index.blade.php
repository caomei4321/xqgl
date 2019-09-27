@extends('admin.common.app')

@section('styles')
    <!-- Data Tables -->
    <link href="{{ asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('assets/admin/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection
<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">已完成任务</span>
                        <h5>完成任务总量</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $AllMatter['finished'] }}</h1>

                        <div class="stat-percent font-bold text-success">{{ ceil(sprintf("%.2f", round(($AllMatter['finished'] / $AllMatter['all']),2) * 100)).'%'}} <i class="fa fa-bolt"></i>
                        </div>
                        <small>{{ date('Y-m-d H:i:s', time()) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-danger pull-right">待处理</span>
                        <h5>待处理任务总量</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $AllMatter['unfinished'] }}</h1>
                        <div class="stat-percent font-bold text-danger">{{ ceil(sprintf("%.2f", round(($AllMatter['unfinished'] / $AllMatter['all']),2) * 100)).'%'}}<i class="fa fa-level-down"></i>
                        </div>
                        <small>{{ date('H:i:s', time()) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">总任务量</span>
                        <h5>总任务量</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $AllMatter['all'] }}</h1>
                        <div class="stat-percent font-bold text-info">100% <i class="fa fa-level-up"></i>
                        </div>
                        <small>{{ date('Y-m-d', time()) }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">今日完成任务</span>
                        <h5>今日完成任务</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $all - $unfinished }}</h1>

                        <div class="stat-percent font-bold text-success">{{ ceil(sprintf("%.2f", round((($all - $unfinished) / $all),2) * 100)).'%'}} <i class="fa fa-bolt"></i>
                        </div>
                        <small>{{ date('Y-m-d H:i:s', time()) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-danger pull-right">今日待处理任务</span>
                        <h5>今日待处理任务</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $unfinished }}</h1>
                        <div class="stat-percent font-bold text-danger">{{ ceil(sprintf("%.2f", round((($unfinished) / $all),2) * 100)).'%'}}<i class="fa fa-level-down"></i>
                        </div>
                        <small>{{ date('H:i:s', time()) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">今日总任务</span>
                        <h5>今日总任务</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $all }}</h1>
                        <div class="stat-percent font-bold text-info">100% <i class="fa fa-level-up"></i>
                        </div>
                        <small>{{ date('Y-m-d', time()) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>今日巡查记录</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div id="guiJi" style="height:330px;"></div>
                    <script type="text/javascript">
                        window.onload = guiJi();
                        function guiJi(){
                            $.ajax({
                                type: 'get',
                                url: '{{url('admin/guiJi')}}',
                                dataType: 'json',
                                success: function (data) {
                                    console.log(data);
                                    var name = [];
                                    var number = [];
                                    for (var key in data) {
                                        var name2 = key;
                                        name.push(name2);
                                        var number2 = data[key];
                                        number.push(number2);
                                    }
                                    console.log(name);
                                    console.log(number);
                                    // {all: 16, unfinish: 15, finish: 1}
                                    // 基于准备好的dom，初始化echarts实例
                                    var myChart = echarts.init(document.getElementById('guiJi'));
                                    // 指定图表的配置项和数据

                                    option = {
                                        xAxis: {
                                            type: 'category',
                                            data: name
                                        },
                                        yAxis: {
                                            type: 'value'
                                        },
                                        series: [{
                                            data: number,
                                            type: 'line'
                                        }]
                                    };

                                    // 使用刚指定的配置项和数据显示图表。
                                    myChart.setOption(option);

                                },
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/echarts.js') }}"></script>
@endsection

@section('javascript')
    <script>

    </script>
@endsection