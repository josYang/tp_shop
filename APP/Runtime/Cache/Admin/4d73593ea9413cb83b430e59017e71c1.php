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
            <a href="<?php echo U('/');?>" target="_blank">网站首页</a>
            <a href="<?php echo U(MODULE_NAME.'/Login/logout');?>" target="_self">退出登录</a>
        </div>
    </div>
    <div id="left">
        <dl>
        <dt>产品管理</dt>
            <dd><a href="<?php echo U(MODULE_NAME.'/Category/index');?>">产品分类</a></dd>
            <dd><a href="<?php echo U(MODULE_NAME.'/Goods/index');?>">产品列表</a></dd>
            <dd><a href="<?php echo U(MODULE_NAME.'/GoodsType/index');?>">产品类型列表</a></dd>
            <dd><a href="<?php echo U(MODULE_NAME.'/Brand/index');?>">产品品牌列表</a></dd>
        </dl>
        <dl>
        <dt>文章管理</dt>
            <dd><a href="<?php echo U(MODULE_NAME.'/ArticleCat/index');?>">文章分类列表</a></dd>
        </dl>
        <dl>
        <dt>RBAC</dt>
            <dd><a href="<?php echo U(MODULE_NAME.'/AdminUser/index');?>">管理员管理</a></dd>
            <dd><a href="<?php echo U(MODULE_NAME.'/AdminRole/index');?>">角色管理</a></dd>
            <dd><a href="<?php echo U(MODULE_NAME.'/AdminNode/index');?>">节点管理</a></dd>
        </dl>
    </div>
    <div id="right">
        <iframe name="iframe" src="<?php echo U(CONTROLLER_NAME.'/show');?>"></iframe>
    </div>
</body>
</html>