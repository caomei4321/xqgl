<!--左侧导航开始-->
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
{{--                    <span><img alt="image" class="img-circle" src="{{ asset('assets/admin/img/profile_small.jpg') }}" /></span>--}}
                    <span><img alt="image" style="height: 60px;" class="img-circle" src="{{ asset('assets/admin/img/zhwjz.png') }}" /></span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold">admin</strong></span>
                                <span class="text-muted text-xs block">超级管理员<b class="caret"></b></span>
                                </span>
                    </a>
                </div>
                <div class="logo-element">H+
                </div>
            </li>
            <li>
                <a class="J_menuItem" href="{{ url('admin/count') }}" data-index="0"><i class="fa fa-home"></i>首页</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-table"></i> <span class="nav-label">人员管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a class="J_menuItem" href="{{ route('admin.users.index') }}"><i class="fa fa-user"></i> <span class="nav-label">人员信息</span></a></li>
                    <li><a class="J_menuItem" href="{{ route('admin.users.address') }}"><i class="fa fa-map-marker"></i> <span class="nav-label">位置分布</span></a></li>
                </ul>
            </li>
            {{--<li>
                <a class="J_menuItem" href="{{ route('admin.entities.index') }}"><i class="fa fa-truck"></i> <span class="nav-label">设备管理</span></a>
            </li>--}}
            <li>
                <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">巡查管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="{{ route('admin.patrolMatters.index') }}"><i class="fa fa-truck"></i> <span class="nav-label">巡查发现事件管理</span></a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="{{ route('admin.patrols.index') }}"><i class="fa fa-car"></i> <span class="nav-label">巡查记录</span></a>
                    </li>
                </ul>

            </li>
            <li>
                <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">12345任务管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="{{ route('admin.matters.index') }}"><i class="fa fa-tasks"></i> <span class="nav-label">12345任务清单</span></a>
                    </li>
                    <li><a class="J_menuItem" href="{{ route('admin.situations.index') }}"><i class="fa fa-calendar-minus-o"></i>  <span class="nav-label">12345任务情况</span></a></li>
                </ul>
            </li>

            <li>
                <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">微信上报任务管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="{{ route('admin.people.index') }}"><i class="fa fa-tasks"></i> <span class="nav-label">群众上报任务清单</span></a>
                    </li>
                    <li><a class="J_menuItem" href="{{ route('admin.people.peopleSituation') }}"><i class="fa fa-calendar-minus-o"></i>  <span class="nav-label">群众上报任务情况</span></a></li>
                </ul>
            </li>
            <li>
                <a class="J_menuItem" href="{{ route('admin.programUsers.index') }}"><i class="fa fa-users"></i> <span class="nav-label">群众管理</span></a>
            </li>
            <li>
                <a class="J_menuItem" href="{{ route('admin.version.index') }}"><i class="fa fa-vimeo"></i> <span class="nav-label">APP版本管理</span></a>
            </li>
            <li>
                <a class="J_menuItem" href="{{ route('admin.alarm.index') }}"><i class="fa fa-truck"></i> <span class="nav-label">智能告警事件管理</span></a>
            </li>
            <li>
                <a class="J_menuItem" href="{{ route('admin.hats.index') }}"><i class="fa fa-truck"></i> <span class="nav-label">安全帽智能检测管理</span></a>
            </li>
            <li>
                <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">基础数据管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a class="J_menuItem" href="{{ route('admin.categories.index') }}">责任类别</a></li>
                    <li><a class="J_menuItem" href="{{ route('admin.responsibility.index') }}">责任清单指导</a></li>
                    <li><a class="J_menuItem" href="{{ route('admin.governanceStandard.index') }}">治理标准</a></li>
                    <li><a class="J_menuItem" href="{{ route('admin.part.index') }}">部件信息</a></li>
                    <li><a class="J_menuItem" href="{{ route('admin.coordinates.index') }}">网格划分图</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">微信基础数据管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="{{ route('admin.programImages.index') }}"><i class="fa fa-file-image-o"></i> <span class="nav-label">轮播图管理</span></a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="{{ route('admin.news.index') }}"><i class="fa fa-file-image-o"></i> <span class="nav-label">要闻管理</span></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-table"></i> <span class="nav-label">系统管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a class="J_menuItem" href="{{ route('admin.administrators.index') }}">管理员</a>
                    </li>
                    <li><a class="J_menuItem" href="{{ route('admin.roles.index') }}">角色</a>
                    </li>
                    <li><a class="J_menuItem" href="{{ route('admin.permissions.index') }}">权限</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<!--左侧导航结束-->