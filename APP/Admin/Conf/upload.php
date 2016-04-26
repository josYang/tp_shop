<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/16
 * Time: 13:41
 */
return array(
    'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
    'imageSize'     =>  1024*1024*2, //上传的文件大小限制 (0-不做限制)
    'exts'          =>  array('png','gif','jpeg','jpg'), //允许上传的文件后缀
    'autoSub'       =>  true, //自动子目录保存文件
    'rootPath'      =>  './Data/Uploads/', //保存根路径
    'goodsImgSavePath'      =>  './image/goods/', //商品图保存路径
    'brandImgSavePath'      =>  './image/brand/', //品牌logo保存路径
    'goodsGallerySavePath'  =>  './image/goods_gallery/', //商品相册保存路径
    'SliderSavePath'        =>  './image/slider/', //首页轮播图保存路径
    'saveName'      =>  array('save_name',array( '__FILE__','time')), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
    'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    'replace'       =>  false, //存在同名是否覆盖
    'hash'          =>  true, //是否生成hash编码
    'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
)
?>