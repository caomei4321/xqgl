<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .td{
            border: 1px solid #000000;
            width: 10;
            height: 30;
        }
        .td-h{
            width: 10;
            height: 30;
            border: 1px solid #000000;
        }
        .td-t{
            height: 80;
            border: 1px solid #000000;
        }
    </style>
</head>
<body>
<table>
    <tr>
        <td valign="middle" colspan="16" align="center" style="height: 60;">
            <span><h1>{{  $data['title']  }}</h1></span>
        </td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>受理员编号</b></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><text>{{ $data['accept_num'] }}</text></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>办结时限</b></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><text>{{ $data['time_limit'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>工单编号</b></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><text>{{ $data['work_num'] }}</text></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>紧急程度</b></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><text>{{ $data['level'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>来电类别</b></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><text>{{ $data['type'] }}</text></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>信息来源</b></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><text>{{ $data['source'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>是否回复</b></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><text>{{ $data['is_reply'] }}</text></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>是否保密</b></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><text>{{ $data['is_secret'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>联系人</b></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><text>{{ $data['contact_name'] }}</text></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>联系电话</b></td>
        <td class="td-h" valign="middle" colspan="4" align="center"><text>{{ $data['contact_phone'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>联系地址</b></td>
        <td class="td-h" valign="middle" colspan="12" align="center"><text>{{ $data['address'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>回复备注</b></td>
        <td class="td-h" valign="middle" colspan="12" align="center"><text>{{ $data['reply_remark'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>问题分类</b></td>
        <td class="td-h" valign="middle" colspan="12" align="center"><text>{{ $data['category_id'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>问题描述</b></td>
        <td class="td-h" valign="middle" colspan="12" align="center"><text>{{ $data['content'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>转办意见</b></td>
        <td class="td-t" valign="middle" colspan="12" align="center"><text>{{ $data['suggestion'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>领导批示</b></td>
        <td class="td-t" valign="middle" colspan="12" align="center"><text>{{ $data['approval'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>办理结果</b></td>
        <td class="td-t" valign="middle" colspan="12" align="center"><text>{{ $data['result'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>任务执行人</b></td>
        <td class="td-h" valign="middle" colspan="12" align="center"><text>{{ $data['user'] }}</text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>现场处理图片</b></td>
        <td class="td-t" valign="middle" colspan="12" align="center"><text><image height="80" src="" /></text></td>
    </tr>
    <tr>
        <td class="td-h" valign="middle" colspan="4" align="center"><b>现场处理信息</b></td>
        <td class="td-t" valign="middle" colspan="12" align="center"><text>{{ $data['information'] }}</text></td>
    </tr>
</table>

</body>

</html>