<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>网站后台</title>
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/public.css" />
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/index.css" />
    <script type="text/javascript" src="/APP/Admin/View/Public/Js/jquery-1.12.1.min.js"></script>
    <script type="text/javascript" src="/APP/Admin/View/Public/Js/index.js"></script>
    <base target="iframe" />
</head>
<body>
    <div id="top">
        <div class="menu">
            <a href="<?php echo U(CONTROLLER_NAME.'/show');?>">系统信息</a>
            <a href="#">选择按钮</a>
            <a href="#">选择按钮</a>
            <a href="#">选择按钮</a>
            <a href="#">选择按钮</a>
        </div>
        <div class="exit">
            <a href="<?php echo U(MODULE_NAME.'/Login/logout');?>" target="_self">退出登录</a>
        </div>
    </div>
    <div id="left">
        <dl>
        <dt>RBAC</dt>
            <dd><a href="<?php echo U(MODULE_NAME.'/AdminUser/index');?>">管理员管理</a></dd>
            <dd><a href="#">选项</a></dd>
            <dd><a href="#">选项</a></dd>
            <dd><a href="#">选项</a></dd>
        </dl>
    </div>
    <div id="right">
        <iframe name="iframe" src="<?php echo U(CONTROLLER_NAME.'/show');?>"></iframe>
    </div>
</body>
</html>