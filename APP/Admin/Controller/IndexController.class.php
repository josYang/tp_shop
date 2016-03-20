<?php
namespace Admin\Controller;
use Org\Util\Rbac;
use Think\Controller;
class IndexController extends Controller {
    /**
     * 权限判断
     */
    public function _initialize(){
        if(empty($_SESSION[C('USER_AUTH_KEY')]) || empty($_SESSION['username'])) {
            $this->redirect(MODULE_NAME.'/Login/login');
        }
        $notAuth = in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE'))) || in_array(CONTROLLER_NAME, explode(',', C('NOT_AUTH_CONTROLLER')));
        if (C('USER_AUTH_ON') && !$notAuth){
            Rbac::AccessDecision()||$this->error('没有操作权限');
        }
    }
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