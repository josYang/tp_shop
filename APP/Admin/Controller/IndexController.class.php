<?php
namespace Admin\Controller;
use Admin\Controller;
class IndexController extends CommonController {
    /**
     * 后台首页
     */
    public function index(){
        $this->display();
    }

    /**
     * 显示系统信息
     */
    public function show(){
        $this->display();
    }
}