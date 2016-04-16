<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>角色列表</title>
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/public.css" />
</head>
<body>
<table class="table">
    <thead>
    <tr>
        <th colspan="4">角色列表</th>
        <th><a href="<?php echo U(CONTROLLER_NAME.'/add');?>">添加角色</a></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th>编号</th>
        <th>名称</th>
        <th>状态</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php if(is_array($roles)): foreach($roles as $key=>$role): ?><tr>
        <td><?php echo ($role["id"]); ?></td>
        <td><?php echo ($role["name"]); ?></td>
        <td>
            <?php if($role["status"] == 1): ?>开启
                <?php else: ?>
                禁用<?php endif; ?>
        </td>
        <td><?php echo ($role["remark"]); ?></td>
        <td>
            [<a href="<?php echo U(MODULE_NAME.'/AdminAccess'.'/index',array('id'=>$role['id']));?>">配置权限</a>]
            [<a href="<?php echo U(CONTROLLER_NAME.'/edit',array('id'=>$role['id']));?>">修改</a>]
            [<a href="<?php echo U(CONTROLLER_NAME.'/delete',array('id'=>$role['id']));?>" onclick="return confirm('确定删除？？')">删除</a>]
        </td>
    </tr><?php endforeach; endif; ?>
    </tbody>
</table>
</body>
</html>