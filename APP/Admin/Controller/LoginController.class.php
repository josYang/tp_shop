<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/3/19
 * Time: 15:51
 */
namespace Admin\Controller;
use Org\Util\Rbac;
use Think\Controller;
class LoginController extends Controller{
    /**
     * 登陆
     */
    public function login(){
        if(!IS_POST){
            $this->display();
            return;
        };
        $verify = new \Think\Verify();
        $verify->seKey = C('VERIFY.seKey');
        if($verify->check(I('code',''),'admin_login')){
            $username = I('post.username','','htmlspecialchars');
            $password = I('post.password','','md5');
            $module = D('AdminUser');
            if(!$admin = $module->checkUserName($username)){
                $this->error('用户名不存在');
            }else{
                if ($admin['status'] == '1'){
                    $this->error('当前管理员已被锁定，无法登陆');
                }
                if($password != $admin['password']){
                    $this->error('密码错误！');
                }
                $data = array(
                    'id'=>$admin['id'],
                    'last_time' => date('Y-m-d H:i:s'),
                    'last_ip' => get_client_ip(),
                );
                $module->save($data);
                session(C('USER_AUTH_KEY'),$admin['id']);
                session('username',$username);
                if($username == C('RBAC_SUPERADMIN'))session(C('ADMIN_AUTH_KEY'),true);
                Rbac::saveAccessList();
                $this->redirect(MODULE_NAME.'/Index/index');
            }
        }else{
            $this->error('验证码输入错误！');
        }
    }

    /**
     * 退出登录
     */
    public function logout(){
        session_unset();
        session_destroy();
        $this->redirect(CONTROLLER_NAME.'/login');
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
            if(!empty(D('AdminUser')->checkUserName(I('post.username','','htmlspecialchars')))){
                $json['correct'] = '√';
            }else{
                $json['error'] = '用户不存在';
            }
            die(json_encode($json));
        }
    }
}
?>