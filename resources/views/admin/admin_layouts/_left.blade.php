<!--左侧导航开始-->
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span><img alt="image" class="img-circle" src="{{ asset('assets/admin/img/profile_small.jpg') }}" /></span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold">Beaut-zihan</strong></span>
                                <span class="text-muted text-xs block">超级管理员<b class="caret"></b></span>
                                </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a class="J_menuItem" href="form_avatar.html">修改头像</a>
                        </li>
                        <li><a class="J_menuItem" href="profile.html">个人资料</a>
                        </li>
                        <li><a class="J_menuItem" href="contacts.html">联系我们</a>
                        </li>
                        <li><a class="J_menuItem" href="mailbox.html">信箱</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="javascript:void(0)" onclick="document.getElementById('logout').submit()">安全退出</a>
                        </li>
                    </ul>
                </div>
                <div class="logo-element">H+
                </div>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-home"></i>
                    <span class="nav-label">主页</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="index_v1.html" data-index="0">主页示例一</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="index_v2.html">主页示例二</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="index_v3.html">主页示例三</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="index_v4.html">主页示例四</a>
                    </li>
                    <li>
                        <a href="index_v5.html" target="_blank">主页示例五</a>
                    </li>
                </ul>

            </li>
            <li>
                <a class="J_menuItem" href="{{ route('admin.users.index') }}"><i class="fa fa-truck"></i> <span class="nav-label">人员管理</span></a>
            </li>
            <li>
                <a class="J_menuItem" href="{{ route('admin.entities.index') }}"><i class="fa fa-truck"></i> <span class="nav-label">设备管理</span></a>
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
            <li>
                <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">基础数据管理</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a class="J_menuItem" href="{{ route('admin.categories.index') }}">责任类别</a></li>
                    <li><a class="J_menuItem" href="{{ route('admin.responsibility.index') }}">责任清单指导</a></li>
                    <li><a class="J_menuItem" href="{{ route('admin.matters.index') }}">任务清单</a></li>
                    <li><a class="J_menuItem" href="{{ route('admin.situations.index') }}">任务情况</a></li>
                    <li><a class="J_menuItem" href="{{ route('admin.part.index') }}">部件信息</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">BUG部件</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a class="J_menuItem" href="{{ route('admin.parts.index') }}">BUG部件信息</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<!--左侧导航结束-->