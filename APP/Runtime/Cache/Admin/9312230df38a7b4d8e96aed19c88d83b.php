<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title><?php echo ($title); ?></title>
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/public.css" />
    <script type="text/javascript" src="/APP/Admin/View/Public/Js/jquery-1.12.1.min.js"></script>
    <script type="text/javascript" src="/APP/Admin/View/Public/Js/user_from.js"></script>
    <script type="text/javascript">
        var action  = '<?php echo (ACTION_NAME); ?>';
        var url     = '/Admin/AdminUser';
        var module  = '<?php echo (CONTROLLER_NAME); ?>';
    </script>
</head>
<body>
<table class="table">
    <thead>
        <tr>
            <th style="text-align: left;width: 40%;"><a href="<?php echo U(CONTROLLER_NAME.'/index');?>">返回列表</a></th>
            <th><?php echo ($title); ?></th>
        </tr>
    </thead>
    <form action="<?php echo U(CONTROLLER_NAME.'/'.ACTION_NAME);?> " method="post" id="user-from">
    <tbody>
        <tr>
            <th>管理员名称</th>
            <?php if(ACTION_NAME == 'add'): ?><td><input type="text" name="username"></td>
                <?php else: ?>
                <input type="hidden" name="id" value="<?php echo ($id); ?>">
                <td><?php echo ($username); ?></td><?php endif; ?>
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
            <th>所属角色</th>
            <td>
                <?php if(is_array($roles)): foreach($roles as $key=>$v): ?><label><input type="checkbox" name="role_id[]" value="<?php echo ($v["id"]); ?>" <?php if(in_array($v['id'],$user_role)): ?>checked<?php endif; ?> /><?php echo ($v["name"]); ?></label>&nbsp;<?php endforeach; endif; ?>
            </td>
        </tr>
        <tr>
            <th>邮箱地址</th>
            <td><input type="text" name="email" value="<?php echo ((isset($email) && ($email !== ""))?($email):''); ?>"></td>
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
                <input type="submit"  class="bt2" name="send" value="<?php echo ($send_submit); ?>">
            </td>
        </tr>
        <?php if(ACTION_NAME == 'edit'): ?><tr>
            <td colspan="2">其他数据：</td>
        </tr>
        <tr>
            <th>添加时间</th>
            <td><?php echo ($add_time); ?></td>
        </tr>
        <tr>
            <th>上次登录时间</th>
            <td><?php echo ($last_time); ?></td>
        </tr>
        <tr>
            <th>上次登录IP</th>
            <td><?php echo ($last_ip); ?></td>
        </tr><?php endif; ?>
    </tbody>
    </form>
</table>
</body>
</html>