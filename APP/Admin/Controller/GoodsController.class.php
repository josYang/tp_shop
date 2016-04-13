<?php
/**
 * 商品管理
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/4
 * Time: 13:17
 */
namespace Admin\Controller;
use Admin\Controller;
class GoodsController extends CommonController{
    public function index(){
        $this->display();
    }

    public function add (){
        if(IS_POST){
            dump($_POST);
            return;
        }
        $this->title = '添加商品';
        $this->display('goods_form');
    }
}
?>