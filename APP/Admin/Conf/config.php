<?php
return array(
    //权限配置
    'RBAC_SUPERADMIN'        => 'admin',                     //设置超级管理员
    'ADMIN_AUTH_KEY'         => 'superadmin',                //超级管理员识别
    'USER_AUTH_ON'           => true,                        //是否需要认证
    'GUEST_AUTH_ON'          => false,                       //关闭游客登录
    'USER_AUTH_GATEWAY'      => MODULE_NAME.'/Login/login',  //无权限自动跳转页面
    'USER_AUTH_TYPE'         => 1,                           //认证类型
    'USER_AUTH_KEY'          => 'uid',                       //认证识别号
    'REQUIRE_AUTH_MODULE'    => 'Admin',                     //需要认证模块
    'NOT_AUTH_MODULE'        => 'Home',                      //无需认证模块
    'REQUIRE_AUTH_ACTION'    => '',                          //需要认证控制器
    'NOT_AUTH_ACTION'        => 'Login',                     //无需认证控制器
    'RBAC_ROLE_TABLE'        => C('DB_PREFIX').'role',       //角色表名称
    'RBAC_USER_TABLE'        => C('DB_PREFIX').'admin_user', //用户表名称
    'RBAC_ACCESS_TABLE'      => C('DB_PREFIX').'access',     //权限表名称
    'RBAC_NODE_TABLE'        => C('DB_PREFIX').'node',       //节点表名称

    //模板解析配置
    'TMPL_PARSE_STRING'     => array(
        '__PUBLIC__' => substr(APP_PATH,1).MODULE_NAME.'/View/Public',
    ),

    //拓展配置
    'LOAD_EXT_CONFIG' => array(
        'UPLOAD'=>'upload',
    ),

    //注册命名空间
//    'AUTOLOAD_NAMESPACE'    => array(
//        'Class'     => substr(APP_PATH,1).'Class',
//    ),
);