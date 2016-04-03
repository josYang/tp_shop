<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title><?php echo ($title); ?></title>
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/public.css" />
</head>
<body>
<form action="<?php echo U(CONTROLLER_NAME.'/'.ACTION_NAME);?>" method="post">
    <table class="table">
        <thead>
        <tr>
            <th style="text-align: left;width: 40%;"><a href="<?php echo U(CONTROLLER_NAME.'/index');?>">返回列表</a></th>
            <th><?php echo ($title); ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th><?php echo ($type); ?>名称</th>
            <td><input type="text" name="name" value="<?php echo ($name); ?>"></td>
        </tr>
        <tr>
            <th><?php echo ($type); ?>中文名称</th>
            <td><input type="text" name="title" value="<?php echo ($nodetitle); ?>"></td>
        </tr>
        <tr>
            <th><?php echo ($type); ?>状态</th>
            <td>
                <select name="status">
                    <?php if(is_array($status_list)): foreach($status_list as $key=>$val): if($status == $key): ?><option value="<?php echo ($key); ?>" selected><?php echo ($val); ?></option>
                            <?php else: ?>
                            <option value="<?php echo ($key); ?>"><?php echo ($val); ?></option><?php endif; endforeach; endif; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><?php echo ($type); ?>描述</th>
            <td>
                <textarea name="remark" cols="40" rows="9"><?php echo ($remark); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><?php echo ($type); ?>排序</th>
            <td><input type="text" name="sort" value="<?php echo ($sort); ?>"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <?php if(ACTION_NAME == 'edit'): ?><input type="hidden" name="id" value="<?php echo ($id); ?>"><?php endif; ?>
                <input type="hidden" name="pid" value="<?php echo ($pid); ?>">
                <input type="hidden" name="level" value="<?php echo ($level); ?>">
                <input type="submit" name="send" value="<?php echo ($submit); ?>">
            </td>
        </tr>
        </tbody>
    </table>
</form>
</body>
</html>