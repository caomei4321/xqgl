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

            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">今日完成任务</span>
                        <h5>12345任务</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $numMatter['numfinished'] }}</h1>

                        <div class="stat-percent font-bold text-success">
                            <i class="fa fa-bolt"></i>
                        </div>
                        <small>{{ date('Y-m-d H:i:s', time()) }}</small>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">今日完成任务</span>
                        <h5>群众举报</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $people['all'] - $people['unfinished'] }}</h1>

                        <div class="stat-percent font-bold text-success">

                            <i class="fa fa-bolt"></i>
                        </div>
                        <small>{{ date('Y-m-d H:i:s', time()) }}</small>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">已完成任务</span>
                        <h5>总任务量</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $AllMatter['finished'] }}</h1>

                        <div class="stat-percent font-bold text-success">
                            @if( $AllMatter['all'] == 0 )
                                0%
                            @else
                                {{ ceil(sprintf("%.2f", round(($AllMatter['finished'] / $AllMatter['all']),2) * 100)).'%'}}
                            @endif
                            <i class="fa fa-bolt"></i>
                        </div>
                        <small>{{ date('Y-m-d H:i:s', time()) }}</small>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">在巡查人数</span>
                        <h5>在巡查人数</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $userNum }}</h1>
                        <div class="stat-percent font-bold text-success">

                            <i class="fa fa-bolt"></i>
                        </div>
                        <small>{{ date('Y-m-d H:i:s', time()) }}</small>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-sm-12">

            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">总任务量</span>
                        <h5>12345任务</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $numMatter['numall'] }}</h1>

                        <div class="stat-percent font-bold text-success">
                            <i class="fa fa-bolt"></i>
                        </div>
                        <small>{{ date('Y-m-d H:i:s', time()) }}</small>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">今日总任务</span>
                        <h5>群众举报</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $people['all'] }}</h1>
                        <div class="stat-percent font-bold text-info">100% <i class="fa fa-level-up"></i>
                        </div>
                        <small>{{ date('Y-m-d', time()) }}</small>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
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

            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">总人数</span>
                        <h5>总人数</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $userAll }}</h1>
                        <div class="stat-percent font-bold text-success">

                            <i class="fa fa-bolt"></i>
                        </div>
                        <small>{{ date('Y-m-d H:i:s', time()) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>正在巡查人员</h5>
                </div>
                <div class="ibox-content" style="height: 400px;">
                    <div class="col-sm-12">
                        <div class="flot-chart-content" id="allUserPatrol"></div>
                        <script type="text/javascript">
                            window.onload = allUserPatrol();
                            function allUserPatrol(){
                                $.ajax({
                                    type: 'get',
                                    url: '{{url('admin/dataInfo')}}',
                                    dataType: 'json',
                                    success: function (data) {
                                        console.log(data);
                                        var timedate = [];
                                        var alldata = [];
                                        var ondata = [];
                                        var undata = [];
                                        for (let key in data) {
                                            var timedate2 =  data[key]['date'];
                                            console.log(data[key]['date']);
                                            timedate.push(timedate2);
                                            var alldata2 =  data[key]['total'];
                                            alldata.push(alldata2);
                                            var ondata2 =  data[key]['finished'];
                                            ondata.push(ondata2);
                                            var undata2 =  data[key]['unfinished'];
                                            undata.push(undata2);
                                        }

                                        // {all: 16, unfinish: 15, finish: 1}
                                        // 基于准备好的dom，初始化echarts实例
                                        var myChart = echarts.init(document.getElementById('allUserPatrol'));
                                        // 指定图表的配置项和数据

                                        option = {
                                            title: {
                                                text: '任务情况'
                                            },
                                            tooltip: {
                                                trigger: 'axis'
                                            },
                                            legend: {
                                                data:['总量','未完成','已完成']
                                            },
                                            grid: {
                                                left: '3%',
                                                right: '4%',
                                                bottom: '3%',
                                                containLabel: true
                                            },
                                            toolbox: {
                                                feature: {
                                                    saveAsImage: {}
                                                }
                                            },
                                            xAxis: {
                                                type: 'category',
                                                data: timedate
                                            },
                                            yAxis: {
                                                type: 'value'
                                            },
                                            series: [
                                                {
                                                    name:'总量',
                                                    type:'line',
                                                    stack: '总量',
                                                    data:alldata
                                                },
                                                {
                                                    name:'未完成',
                                                    type:'line',
                                                    stack: '未完成',
                                                    data:undata
                                                },
                                                {
                                                    name:'已完成',
                                                    type:'line',
                                                    stack: '已完成',
                                                    data:ondata
                                                }
                                            ]
                                        };

                                        // 使用刚指定的配置项和数据显示图表。
                                        myChart.setOption(option);
                                        setTimeout(function () {

                                        })
                                    },
                                });
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/echarts.js') }}"></script>
@endsection

@section('javascript')

@endsection