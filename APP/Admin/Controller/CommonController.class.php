<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/3/21
 * Time: 19:58
 */
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;
class CommonController extends Controller{
    /**
     * 权限判断
     */
    public function _initialize(){
        if(empty($_SESSION[C('USER_AUTH_KEY')]) || empty($_SESSION['username'])) {
            $this->redirect(MODULE_NAME.'/Login/login');
        }
        $notAuth = in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE'))) || in_array(CONTROLLER_NAME, explode(',', C('NOT_AUTH_CONTROLLER')));
        if (C('USER_AUTH_ON') && !$notAuth){
            Rbac::AccessDecision(MODULE_NAME)||$this->error('没有操作权限');
        }
    }

}
?>