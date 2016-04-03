<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title><?php echo ($title); ?></title>
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/public.css" />
    <script type="text/javascript" src="/APP/Admin/View/Public/Js/jquery-1.12.1.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#role-from').submit(function(){
                var name = $('input[name="name"]');
                name.next('span').remove()
                if (name.val().trim().length < 3){
                    name.after('<span class="error">角色名不得少于三位数</span>')
                    return false
                }
            })
        })
    </script>
</head>
<body>
<table class="table">
    <thead>
    <tr>
        <th style="text-align: left;width: 40%"><a href="<?php echo U(CONTROLLER_NAME.'/index');?>">返回列表</a></th>
        <th><?php echo ($title); ?></th>
    </tr>
    </thead>
    <tbody>
    <form action="<?php echo U(CONTROLLER_NAME.'/'.ACTION_NAME);?>" method="post" id="role-from">
        <?php if(ACTION_NAME == 'edit'): ?><input type="hidden" name="id" value="<?php echo ($id); ?>"><?php endif; ?>
    <tr>
        <th>角色名称</th>
        <td><input type="text" name="name" value="<?php echo ($name); ?>"></td>
    </tr>
    <tr>
        <th>父角色</th>
        <td>
            <select name="pid">
                <option value="0">==请选择==</option>
                <?php if(is_array($roles)): foreach($roles as $key=>$v): if($v['id'] == $pid): ?><option value="<?php echo ($v["id"]); ?>" selected><?php echo ($v["name"]); ?></option>
                    <?php else: ?>
                        <option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php endif; endforeach; endif; ?>
            </select>
        </td>
    </tr>
    <tr>
        <th>角色描述</th>
        <td><textarea name="remark" cols="40" rows="8"><?php echo ($remark); ?></textarea></td>
    </tr>
    <tr>
        <th>角色状态</th>
        <td>
            <select name="status">
                <?php if(is_array($status_list)): foreach($status_list as $key=>$val): if($key == $status): ?><option value="<?php echo ($key); ?>" selected><?php echo ($val); ?></option>
                        <?php else: ?>
                        <option value="<?php echo ($key); ?>"><?php echo ($val); ?></option><?php endif; endforeach; endif; ?>
            </select>
        </td>
    </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" name="send" value="<?php echo ($submit); ?>"></td>
        </tr>
    </form>
    </tbody>
</table>
</body>
</html>