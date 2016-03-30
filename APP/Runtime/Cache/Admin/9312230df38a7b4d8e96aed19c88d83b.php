<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title><?php echo ($title); ?></title>
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/public.css" />
</head>
<body>
<table class="table">
    <thead>
        <tr>
            <th style="text-align: left;"><a href="<?php echo U(CONTROLLER_NAME.'/index');?>">返回列表</a></th>
            <th><?php echo ($title); ?></th>
        </tr>
    </thead>
    <form action="<?php echo ($action); ?>">
        <input type="hidden" name="id" value="<?php echo ($id); ?>">
    <tbody>
        <tr>
            <th>管理员名称</th>
            <td><?php echo ($username); ?></td>
        </tr>
        <tr>
            <th>管理员状态</th>
            <td>
                <select name="status">
                    <?php if(is_array($status_list)): foreach($status_list as $k=>$v): if($k == $status): ?><option value="<?php echo ($k); ?>" selected><?php echo ($v); ?></option>
                        <?php else: ?>
                            <option value="<?php echo ($k); ?>"><?php echo ($v); ?></option><?php endif; endforeach; endif; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>原密码</th>
            <td><input type="password" name="old_password"></td>
        </tr>
        <tr>
            <th>新密码</th>
            <td><input type="password" name="new_password"></td>
        </tr>
        <tr>
            <th>确认密码</th>
            <td><input type="password" name="confirm_password"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="send" value="<?php echo ($send_submit); ?>">
            </td>
        </tr>
        <tr>
            <td colspan="2">其他数据：</td>
        </tr>
        <tr>
            <th>添加时间</th>
            <td><?php echo ($add_time); ?></td>
        </tr>
    </tbody>
    </form>
</table>
</body>
</html>