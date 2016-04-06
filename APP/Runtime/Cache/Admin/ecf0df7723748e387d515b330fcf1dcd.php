<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>权限分配</title>
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/public.css" />
    <link rel="stylesheet" type="text/css" href="/APP/Admin/View/Public/Css/node.css" />
    <script type="text/javascript" src="/APP/Admin/View/Public/Js/jquery-1.12.1.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $('input[level="1"]').click(function(){
                var checkbox = $(this);
                if (checkbox.attr('checked') == undefined){
                    checkbox.attr('checked',true)
                }else {
                    checkbox.removeAttr('checked')
                }
                var inputs = $(this).parents('.app').find('input[type="checkbox"]');
                checkbox.attr('checked') ? inputs.attr('checked',true):inputs.removeAttr('checked');
            });
            $('input[level="2"]').click(function(){
                var checkbox = $(this);
                if (checkbox.attr('checked') == undefined){
                    checkbox.attr('checked',true)
                }else {
                    checkbox.removeAttr('checked')
                }
                var inputs = $(this).parents('dl').find('input');
                checkbox.attr('checked') ? inputs.attr('checked',true):inputs.removeAttr('checked');
            });
        });
    </script>
</head>
<body>
<form action="<?php echo U(CONTROLLER_NAME.'/'.ACTION_NAME);?>" method="post">
    <div id="wrap">
        <a class="add-app" href="<?php echo U(MODULE_NAME.'/AdminRole/index');?>">返回</a>
        <?php if(is_array($node)): foreach($node as $key=>$app): ?><div class="app">
                <p>
                    <label><strong><?php echo ($app["title"]); echo ($app["name"]); ?></strong>&nbsp;<input type="checkbox" name="access[]" value="<?php echo ($app["id"]); ?>_1" level='1' <?php if($app['access']): ?>checked<?php endif; ?> /></label>
                </p>
                <?php if(is_array($app["child"])): foreach($app["child"] as $key=>$action): ?><dl>
                        <dt>
                            <label><strong><?php echo ($action["title"]); echo ($action["name"]); ?></strong>&nbsp;<input type="checkbox" name="access[]" value="<?php echo ($action["id"]); ?>_2" level='2' <?php if($action['access']): ?>checked<?php endif; ?> /></label>
                        </dt>
                        <?php if(is_array($action["child"])): foreach($action["child"] as $key=>$method): ?><dd>
                                <label><span><?php echo ($method["title"]); echo ($method["name"]); ?></span>&nbsp;<input type="checkbox" name="access[]" value="<?php echo ($method["id"]); ?>_3" level='3' <?php if($method['access']): ?>checked<?php endif; ?> /></label>
                            </dd><?php endforeach; endif; ?>
                    </dl><?php endforeach; endif; ?>
            </div><?php endforeach; endif; ?>
    </div>
    <input type="hidden" name="rid" value="<?php echo ($rid); ?>" />
    <input type="submit" value="保存修改"  class="bt2" style="display: block;margin: 20px auto;cursor: pointer;" />
</form>
</body>
</html>