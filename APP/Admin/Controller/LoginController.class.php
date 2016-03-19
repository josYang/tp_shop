<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/3/19
 * Time: 15:51
 */
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
    /**
     * 登录页面显示
     */
    public function index(){
        $this->display();
    }

    /**
     * 登陆
     */
    public function login(){
    }

    /**
     * 输出验证码信息
     */
    public function verify(){
        $verify = new \Think\Verify(C('VERIFY'));
        $verify->entry('jos');
    }

    public function checkusername(){
        if(IS_POST){
            $admin = D('AdminUser');
        }
    }
}
?>