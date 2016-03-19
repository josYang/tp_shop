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
        $verify->entry('admin_login');
    }

    /**
     * 验证用户名是否存在
     */
    public function checkusername(){
        if(IS_AJAX){
            $json = array();
            $admin = D('AdminUser');
            if($admin->checkUser(I('post.username','','htmlspecialchars')) > 0){
                $json['correct'] = '√';
            }else{
                $json['error'] = '用户不存在';
            }
            die(json_encode($json));
        }
    }

    /**
     * 判断验证码是否输入正确
     */
    public function checkcode(){
        if(IS_AJAX){
            $verify = new \Think\Verify();
            $verify->seKey = C('VERIFY.seKey');
            $json = array();
            if($verify->check(I('code',''),'admin_login')){
                $json['correct'] = '√';
            }else{
                $json['error'] = '验证码错误';
            }
            die(json_encode($json));
        }
    }
}
?>