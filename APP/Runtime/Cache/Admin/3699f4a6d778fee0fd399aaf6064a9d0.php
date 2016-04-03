<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>节点列表</title>
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/public.css" />
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/node.css" />
</head>
<body>
<div id="wrap">
    <a class="add-app" href="<?php echo U(CONTROLLER_NAME.'/add',array('pid'=>0,'level'=>1));?>">添加应用</a>
    <?php if(is_array($node)): foreach($node as $key=>$app): ?><div class="app">
            <p>
                <strong><?php echo ($app["title"]); ?>（<?php echo ($app["name"]); ?>）</strong>
                [<a href="<?php echo U(CONTROLLER_NAME.'/add',array('pid'=>$app['id'],'level'=>2));?>">添加控制器</a>]
                [<a href="<?php echo U(CONTROLLER_NAME.'/edit',array('id'=>$app['id']));?>">修改</a>]
                [<a href="<?php echo U(CONTROLLER_NAME.'/delete',array('id'=>$app['id']));?>">删除</a>]
            </p>
            <?php if(is_array($app["child"])): foreach($app["child"] as $key=>$action): ?><dl>
                    <dt>
                        <strong><?php echo ($action["title"]); ?>（<?php echo ($action["name"]); ?>）</strong>
                        [<a href="<?php echo U(CONTROLLER_NAME.'/add',array('pid'=>$action['id'],'level'=>3));?>">添加方法</a>]
                        [<a href="<?php echo U(CONTROLLER_NAME.'/edit',array('id'=>$action['id']));?>">修改</a>]
                        [<a href="<?php echo U(CONTROLLER_NAME.'/delete',array('id'=>$action['id']));?>">删除</a>]
                    </dt>
                    <?php if(is_array($action["child"])): foreach($action["child"] as $key=>$method): ?><dd>
                            <span><?php echo ($method["title"]); ?>（<?php echo ($method["name"]); ?>）</span>
                            [<a href="<?php echo U(CONTROLLER_NAME.'/edit',array('id'=>$method['id']));?>">修改</a>]
                            [<a href="<?php echo U(CONTROLLER_NAME.'/delete',array('id'=>$method['id']));?>">删除</a>]
                        </dd><?php endforeach; endif; ?>
                </dl><?php endforeach; endif; ?>
        </div><?php endforeach; endif; ?>
</div>
</body>
</html>