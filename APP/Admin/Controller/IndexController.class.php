<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
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