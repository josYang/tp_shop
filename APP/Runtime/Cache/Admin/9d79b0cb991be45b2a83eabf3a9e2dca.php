<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title>管理员列表</title>
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/public.css" />
    <script type="text/javascript" src="/APP/Admin/View/Public/Js/jquery-1.12.1.min.js"></script>
</head>
<body>
<table class="table">
    <thead>
        <tr>
            <th>编号</th>
            <th>名称</th>
            <th>注册时间</th>
            <th>上次时间</th>
            <th>上次登录IP</th>
            <th>状态</th>
            <th>所属组别</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php if(is_array($admin)): foreach($admin as $key=>$val): ?><tr>
            <td><?php echo ($val["id"]); ?></td>
            <td><?php echo ($val["username"]); ?></td>
            <td><?php echo ($val["add_time"]); ?></td>
            <td><?php echo ($val["last_time"]); ?></td>
            <td><?php echo ($val["last_ip"]); ?></td>
            <td>
                <?php if($val["status"] == 2): ?>启用
                <?php else: ?>
                    禁用<?php endif; ?>
            </td>
            <td>
                <?php if($val["username"] == C('RBAC_SUPERADMIN')): ?>超级管理员
                <?php else: ?>
                    <ul>
                        <?php if(is_array($val["role"])): foreach($val["role"] as $key=>$v): ?><li><?php echo ($v["name"]); ?>(<?php echo ($v["remark"]); ?>)</li><?php endforeach; endif; ?>
                    </ul><?php endif; ?>
            </td>
            <td>
                [<a href="<?php echo U(CONTROLLER_NAME.'/status');?>">锁定</a>]
                [<a href="<?php echo U(CONTROLLER_NAME.'/edit',array('id'=>$val['id']));?>">修改</a>]
                [<a href="<?php echo U(CONTROLLER_NAME.'/delete',array('id'=>$val['id']));?>" onclick="return confirm('确定删除？')">删除</a>]
            </td>
        </tr><?php endforeach; endif; ?>
    </tbody>
</table>
</body>
</html>