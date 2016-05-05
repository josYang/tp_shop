<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/17
 * Time: 11:18
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Page;

class UserController extends CommonController
{
    public function index(){
        $db = M('users');
        $total = $db->count();
        $page = new Page($total,C('SYSTEM.page_size'));
        $this->users = $db->field('user_id,username,phone,status,add_time')->limit($page->firstRow.','.$page->listRows)->select();
        $this->show = $page->show();
        $this->display();
    }

    public function add(){
        if(IS_POST){
            $data = array(
                'username'  => I('post.username','','htmlentities'),
                'phone'     => I('post.phone','','htmlentities'),
                'qq'        => I('post.qq','','htmlentities'),
                'email'     => I('post.email','','htmlentities'),
                'status'    => I('post.status',0,'intval'),
                'add_time'  => date('Y-m-d H:i:s'),
                'last_time' => date('Y-m-d H:i:s'),
                'last_ip'   => get_client_ip(),
            );
            if(!empty($_POST['new_password']) && !empty($_POST['confirm_password'])){
                if($_POST['new_password'] == $_POST['confirm_password']){
                    $data['password'] = I('post.new_password','','md5');
                    M('users')->add($data);
                    $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
                }else{
                    $this->error('密码不一致');
                }
            }else{
                $this->error('密码不得为空');
            }
            return;
        }
        $this->title  = '添加会员';
        $this->submit = '保存添加';
        $this->getForm();
        $this->display('user_form');
    }

    public function edit(){
        $db = M('users');
        $user_id = I('get.uid',0,'intval');
        if(IS_POST){
            $data = array(
                'user_id'  => I('post.user_id',0,'intval'),
                'phone'     => I('post.phone','','htmlentities'),
                'qq'        => I('post.qq','','htmlentities'),
                'email'     => I('post.email','','htmlentities'),
                'status'     => I('post.status',0,'intval'),
            );
            if(!empty($_POST['new_password']) && !empty($_POST['confirm_password'])){
                if($_POST['new_password'] == $_POST['confirm_password']){
                    $data['password'] = I('post.new_password','','md5');
                }else{
                    $this->error('密码不一致');
                }
            }
            $db->save($data);
            $this->success('修改成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->user = $db->find($user_id);
        $this->title  = '修改会员';
        $this->submit = '保存修改';
        $this->getForm();
        $this->display('user_form');
    }

    private function getForm(){
        $this->status_list = array('未验证','已验证');
    }

    public function checkUsername(){
        if(IS_AJAX){
            $json = array();
            $username = M('users')->where(array('username'=>I('post.username','','htmlentities')))->getField('username');
            $json['isset'] = $username ? true :false;
            $this->ajaxReturn($json);
        }
    }

    public function delete (){
        $user_id = I('get.uid',0,'intval');
        if($user_id > 0){
            M('users')->delete($user_id);
            $this->success('删除成功',U(CONTROLLER_NAME.'/index'));
        }else{
            $this->error('操作有误');
        }
    }

    public function checkEmail(){
        if(IS_AJAX){
            $json = array();
            $user_id = M('users')->where(array('email'=>I('post.email','','htmlentities')))->getField('user_id');
            if($user_id != null){
                    $json['isset'] = $user_id == I('post.user_id',0,'intval') ? false : true;
            }else{
                $json['isset'] = false;
            }
            $this->ajaxReturn($json);
        }
    }

    public function checkPhone(){
        if(IS_AJAX){
            $json = array();
            $user_id = M('users')->where(array('phone'=>I('post.phone','','htmlentities')))->getField('user_id');
            if($user_id != null){
                $json['isset'] = $user_id == I('post.user_id',0,'intval') ? false : true;
            }else{
                $json['isset'] = false;
            }
            $this->ajaxReturn($json);
        }
    }
}