<?php
/**
 * 管理员控制器
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/3/21
 * Time: 20:27
 */
namespace Admin\Controller;
use Admin\Controller;
class AdminUserController extends CommonController{
    private $error = array();
    public function index(){
        $this->admin = D('AdminUser')->getUserList();
        $this->display();
    }

    public function add(){
        $this->title = '添加管理员';
        if(IS_POST){
            if(empty($_POST['new_password'])){
                $this->error('密码不得为空');
            }
            if($_POST['confirm_password'] != $_POST['new_password']){
                $this->error('密码和确认密码不一致');
            }
            $data = array(
                'username' => $_POST['username'],
                'password' => I('post.new_password','','md5'),
                'add_time' => date('Y-m-d H:i:s'),
                'last_time' => date('Y-m-d H:i:s'),
                'last_ip' => get_client_ip(),
                'status' => I('status','','intval')
            );
            D('AdminUser')->addUser($data);
            $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->getFrom();
    }

    public function edit(){
        $this->title = '修改管理员';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $model = D('AdminUser');
            $userinfo = $model->getUser($_POST['id']);
            if($userinfo['username'] == C('RBAC_SUPERADMIN') && !$_SESSION['superadmin']){
                $this->error('无权修改');
            }
            $data = array(
                'id'=>$userinfo['id'],
                'status'=>I('post.status','','intval'),
            );
            if(!empty($_POST['new_password']) || !empty($_POST['confirm_password'])){
                if($_POST['new_password'] == $_POST['confirm_password']){
                    $data['password'] = I('post.new_password','','md5');
                }else{
                    $this->error('密码与确认密码不一致');
                }
            }
            $model->editUser($data);
            $this->success('修改成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->getFrom();
    }

    public function delete(){
        $id = I('get.id','','intval');
        if(!empty($id) && IS_GET){
            $model = D('AdminUser');
            $username = $model->getUserName($id);
            if($username == C('RBAC_SUPERADMIN')){
                $this->error('不能删除系统超级管理员');
            }else{
                D('AdminUser')->deleteUser($id);
                $this->success('删除成功',U(CONTROLLE_NAME.'/index'));
                return;
            }
        }else{
            $this->error('操作有误');
        }

    }

    private function getFrom(){
        if(ACTION_NAME == 'edit'){
            $userInfo = D('AdminUser')->getUser(I('get.id','','intval'));
            $this->id = $userInfo['id'];
            $this->username = $userInfo['username'];
            $this->add_time = $userInfo['add_time'];
            $this->last_time = $userInfo['last_time'];
            $this->last_ip = $userInfo['last_ip'];
            $this->status = $userInfo['status'];
            $this->send_submit = '确认修改';
        }else{
            $this->id = '';
            $this->username = '';
            $this->add_time = '';
            $this->last_time = '';
            $this->last_ip = '';
            $this->status = '';
            $this->send_submit = '确认添加';
        }
        $this->status_list = array(
            2=>'启用',
            1=>'禁用',
        );
        $this->display('user_from');
    }
}
?>