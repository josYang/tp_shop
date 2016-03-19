<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
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

    /**
     * 退出登录
     */
    public function logout(){
        session_unset();
        session_destroy();
        $this->redirect(MODULE_NAME.'/Login/index');
    }
}