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
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>基本 <small>分类，查找</small></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="table_data_tables.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="table_data_tables.html#">选项1</a>
                            </li>
                            <li><a href="table_data_tables.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>消息</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                        <a class="close-link">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="ibox-content ibox-heading">
                                    <h3><i class="fa fa-envelope-o"></i> 今日已完成任务消息</h3>
                                    <small><i class="fa fa-tim"></i> 今日总共有 <b>{{ $all }}</b> 条任务， 已完成 <b>{{ count($situations) }}</b> 条， 还剩 <b>{{ $all-count($situations) }}</b> 条待处理</small>
                                </div>
                                <div class="ibox-content">
                                    <div class="feed-activity-list">
                                        @foreach($situations as $situation)
                                        <div class="feed-element">
                                            <div>
                                                <small class="pull-right text-navy">{{ $situation->updated_at->diffForHumans() }}</small>
                                                <strong>{{ $situation->user->name }}</strong>
                                                <div>{{ $situation->matter->content }}</div>
                                                <small class="text-muted">{{ $situation->updated_at }}</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    {{ $situations->links() }}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-8">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5>任务情况</h5>
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
                                            <div id="allMatters" style="height:450px;"></div>
                                            <script type="text/javascript">
                                                window.onload = showDay();
                                                function showDay(){
                                                    $.ajax({
                                                        type: 'get',
                                                        url: '{{url('admin/allMatters')}}',
                                                        dataType: 'json',
                                                        success: function (data) {
                                                            // {all: 16, unfinish: 15, finish: 1}
                                                            // 基于准备好的dom，初始化echarts实例
                                                            var myChart = echarts.init(document.getElementById('allMatters'));
                                                            // 指定图表的配置项和数据

                                                            option = {
                                                                tooltip : {
                                                                    trigger: 'item',
                                                                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                                                                },
                                                                legend: {
                                                                    orient: 'vertical',
                                                                    left: 'left',
                                                                    data: ['总任务数','已完成','待完成']
                                                                },
                                                                series : [
                                                                    {
                                                                        name: '任务',
                                                                        type: 'pie',
                                                                        radius : '55%',
                                                                        center: ['50%', '60%'],
                                                                        data:[
                                                                            {value:data.all, name:'总任务数'},
                                                                            {value:data.unfinished, name:'待完成'},
                                                                            {value:data.finished, name:'已完成'},
                                                                        ],
                                                                        itemStyle: {
                                                                            emphasis: {
                                                                                shadowBlur: 10,
                                                                                shadowOffsetX: 0,
                                                                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                                                                            }
                                                                        }
                                                                    }
                                                                ]
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
                                            <div id="guiJi" style="height:450px;"></div>
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
                                {{--<div class="col-sm-6">--}}
                                    {{--<div class="ibox float-e-margins">--}}
                                        {{--<div class="ibox-title">--}}
                                            {{--<h5>个人任务统计</h5>--}}
                                            {{--<div class="ibox-tools">--}}
                                                {{--<a class="collapse-link">--}}
                                                    {{--<i class="fa fa-chevron-up"></i>--}}
                                                {{--</a>--}}
                                                {{--<a class="close-link">--}}
                                                    {{--<i class="fa fa-times"></i>--}}
                                                {{--</a>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="ibox-content">--}}
                                            {{--<div id="everyuserday" style="height:450px;"></div>--}}
                                            {{--<script type="text/javascript">--}}
                                                {{--window.onload = showUserDay();--}}
                                                {{--function showUserDay(){--}}
                                                    {{--$.ajax({--}}
                                                        {{--type: 'get',--}}
                                                        {{--url: '{{url('admin/everyUserDay')}}',--}}
                                                        {{--dataType: 'json',--}}
                                                        {{--success: function (data) {--}}
                                                            {{--var name = [];--}}
                                                            {{--var total = [];--}}
                                                            {{--var on = [];--}}
                                                            {{--var un = [];--}}
                                                            {{--for (var i = 0; i < data.length; i++) {--}}
                                                                {{--var name2 = data[i].name;--}}
                                                                {{--name.push(name2);--}}
                                                                {{--var total2 = data[i].total;--}}
                                                                {{--total.push(total2);--}}
                                                                {{--var on2 = data[i].on;--}}
                                                                {{--on.push(on2);--}}
                                                                {{--var un2 = data[i].un;--}}
                                                                {{--un.push(un2);--}}
                                                            {{--}--}}
                                                            {{--console.log(name);--}}
                                                            {{--// 基于准备好的dom，初始化echarts实例--}}
                                                            {{--var myChart = echarts.init(document.getElementById('everyuserday'));--}}
                                                            {{--// 指定图表的配置项和数据--}}

                                                            {{--option = {--}}
                                                                {{--angleAxis: {--}}
                                                                    {{--type: 'category',--}}
                                                                    {{--data: name,--}}
                                                                    {{--z: 10--}}
                                                                {{--},--}}
                                                                {{--tooltip : {--}}
                                                                    {{--trigger: 'item',--}}
                                                                    {{--formatter: "{a} <br/>{b} : {c} "--}}
                                                                {{--},--}}
                                                                {{--legend: {--}}
                                                                    {{--orient: 'vertical',--}}
                                                                    {{--left: 'left',--}}
                                                                    {{--data:  name,--}}
                                                                {{--},--}}
                                                                {{--radiusAxis: {--}}
                                                                {{--},--}}
                                                                {{--polar: {--}}
                                                                {{--},--}}
                                                                {{--series: [{--}}
                                                                    {{--type: 'bar',--}}
                                                                    {{--data: un,--}}
                                                                    {{--coordinateSystem: 'polar',--}}
                                                                    {{--name: '待处理',--}}
                                                                    {{--stack: 'a'--}}
                                                                {{--}, {--}}
                                                                    {{--type: 'bar',--}}
                                                                    {{--data: on,--}}
                                                                    {{--coordinateSystem: 'polar',--}}
                                                                    {{--name: '已完成',--}}
                                                                    {{--stack: 'a'--}}
                                                                {{--}, {--}}
                                                                    {{--type: 'bar',--}}
                                                                    {{--data: total,--}}
                                                                    {{--coordinateSystem: 'polar',--}}
                                                                    {{--name: '总量',--}}
                                                                    {{--stack: 'a'--}}
                                                                {{--}],--}}
                                                                {{--legend: {--}}
                                                                    {{--show: true,--}}
                                                                    {{--data: ['总量', '已完成', '待处理']--}}
                                                                {{--}--}}
                                                            {{--};--}}


                                                            {{--// 使用刚指定的配置项和数据显示图表。--}}
                                                            {{--myChart.setOption(option);--}}

                                                        {{--},--}}
                                                    {{--});--}}
                                                {{--}--}}
                                            {{--</script>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            </div>

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
    <script>

    </script>
@endsection