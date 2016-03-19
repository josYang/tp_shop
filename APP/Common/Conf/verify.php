<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/3/19
 * Time: 16:37
 */
return array(
    'expire' => 60*3,//验证码的有效期（秒）
    'useImgBg' => true,//是否使用背景图片 默认为false
    'fontSize' => 10,//验证码字体大小（像素） 默认为25
    'useCurve' => true,//是否使用混淆曲线 默认为true
    'useNoise' => true,//是否添加杂点 默认为true
    'imageW' => 0,//验证码宽度 设置为0为自动计算
    'imageH' => 0,//验证码高度 设置为0为自动计算
    'length' => 4,//验证码位数
    'fontttf' => '5.ttf',//指定验证码字体 默认为随机获取
    'useZh' => false,//是否使用中文验证码
    'bg' => array(243,251,254),//验证码背景颜色 rgb数组设置，例如 array(243, 251, 254)
    'seKey' => 'jos',//验证码的加密密钥
    'codeSet' => 'utf8',//验证码字符集合
    'zhSet' => 'utf8',//验证码字符集合
);
?>