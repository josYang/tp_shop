<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/29
 * Time: 21:08
 */

namespace Home\Controller;
use Think\Controller;

class UserController extends Controller
{
    private function checkLogin(){
        $user_info = M('users')->find($_COOKIE['user_id']);
        if(empty($user_info) && $user_info['username'] != $_COOKIE['username']){
            $this->error('账号有误，请重新登陆');
        }
    }
    public function index(){
        $this->checkLogin();
        $this->display();
    }

    public function profile(){
        $this->checkLogin();
        $db = M('users');
        $user_info = $db->find($_COOKIE['user_id']);
        if(IS_POST){
            if(isset($_POST['act']) && $_POST['act'] == 'edit_password'){
                $data = array(
                    'user_id' => $_COOKIE['user_id']
                );
                $old_password = I('post.old_password','','md5');
                if($old_password == $user_info['password']){
                    if($_POST['new_password'] == $_POST['comfirm_password']){
                        $data['password'] = I('post.new_password','','md5');
                        $db->save($data);
                        $this->success('密码修改成功',U(__ACTION__));
                    }else{
                        $this->error('密码和确认密码不一致');
                    }
                }else{
                    $this->error('原密码不正确');
                }
            }else{
                $data = array(
                    'user_id' => $_COOKIE['user_id'],
                    'phone' => I('post.phone','','htmlentities'),
                    'email' => I('post.email','','htmlentities'),
                    'qq' => I('post.qq','','htmlentities'),
                );
                $db->save($data);
                $this->success('修改成功',U(__ACTION__));
            }
            return;
        }
        $this->user = $user_info;
        $this->display();
    }

    public function address_list(){
        $this->checkLogin();
        if(IS_POST){
            dump($_POST);
            return;
        }
        $this->display();
    }

    public function register(){
        if(IS_POST){
            $data = array(
                'username'  => I('post.username','','htmlentities'),
                'phone'     => I('post.phone','','htmlentities'),
                'qq'        => I('post.qq','','htmlentities'),
                'email'     => I('post.email','','htmlentities'),
                'status'    => 0,
                'add_time'  => date('Y-m-d H:i:s'),
                'last_time' => date('Y-m-d H:i:s'),
                'last_ip'   => get_client_ip(),
            );
            if(!empty($_POST['password']) && !empty($_POST['confirm_password'])){
                if($_POST['password'] == $_POST['confirm_password']){
                    $data['password'] = I('post.password','','md5');
                    $user_id = M('users')->add($data);
                    setcookie('username',$data['username'],time() + 60*60);
                    setcookie('user_id',$user_id,time() + 60*60);
                    $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
                }else{
                    $this->error('密码不一致');
                }
            }else{
                $this->error('密码不得为空');
            }
            return;
        }
        $this->display();
    }

    public function login(){
        if(IS_POST){
            $user_info = M('users')->where('`username`="'. I('post.username','','htmlentities') .'"')->find();
            if($user_info){
                if(I('post.password','','md5') == $user_info['password']){
                    $time = isset($_POST['remember']) && $_POST['remember'] == 1 ? 60*60*24 : 60*60*24*7;
                    setcookie('username',$user_info['username'],time() + $time);
                    setcookie('user_id',$user_info['user_id'],time() + $time);
                    $this->success('登录成功',U(__CONTROLLER__.'/index'));
                }else{
                    $this->error('密码错误！');
                }
            }else{
                $this->error('用户不存在！');
            }
            return;
        }
        $this->display();
    }

    public function is_registered(){
        $username = I('get.username','','htmlentities');
        $json = array();
        if(M('users')->where('username="'.$username.'"')->getField('username')){
            $json['result'] = false;
        }else{
            $json['result'] = true;
        }
        $this->ajaxReturn($json);
    }

    public function check_email(){
        $email = I('get.email','','htmlentities');
        $json = array();
        if(M('users')->where('email="'.$email.'"')->getField('email')){
            $json['result'] = false;
        }else{
            $json['result'] = true;
        }
        $this->ajaxReturn($json);
    }

    public function check_phone(){
        $phone = I('get.phone','','htmlentities');
        $json = array();
        if(M('users')->where('phone="'.$phone.'"')->getField('phone')){
            $json['result'] = false;
        }else{
            $json['result'] = true;
        }
        $this->ajaxReturn($json);
    }
}