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
                'username'  => I('post.username','','htmlentities'),
                'password'  => I('post.new_password','','md5'),
                'email'     =>I('post.email','','htmlentities'),
                'add_time'  => date('Y-m-d H:i:s'),
                'last_time' => date('Y-m-d H:i:s'),
                'last_ip'   => get_client_ip(),
                'status'    => I('status',0,'intval')
            );
            $user_id = D('AdminUser')->addUser($data);
            //管理员分配角色
            $role_data = array();
            foreach ($_POST['role_id'] as $v) {
                $role_data[] = array(
                    'user_id' => $user_id,
                    'role_id' => $v,
                );
            }
            M('role_user')->addAll($role_data);
            $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->getFrom();
    }

    public function edit(){
        $this->title = '修改管理员';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $model = D('AdminUser');
            $userinfo = $model->getUser($_POST['user_id']);
            if($userinfo['username'] == C('RBAC_SUPERADMIN') && !$_SESSION['superadmin']){
                $this->error('无权修改');
            }
            $data = array(
                'id'=>$userinfo['id'],
                'status'=>I('post.status','','intval'),
                'email'=>I('post.email','','htmlentities'),
            );
            if(!empty($_POST['new_password']) || !empty($_POST['confirm_password'])){
                if($_POST['new_password'] == $_POST['confirm_password']){
                    $data['password'] = I('post.new_password','','md5');
                }else{
                    $this->error('密码与确认密码不一致');
                }
            }
            $model->editUser($data);
            //管理员分配角色
            $role_data = array();
            foreach ($_POST['role_id'] as $v) {
                $role_data[] = array(
                    'user_id' => $userinfo['id'],
                    'role_id' => $v,
                );
            }
            $role_user = M('role_user');
            $role_user->where(array('user_id'=>$userinfo['id']))->delete();
            $role_user->addAll($role_data);
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
                $this->success('删除成功',U(CONTROLLER_NAME.'/index'));
                return;
            }
        }else{
            $this->error('操作有误');
        }

    }

    /**
     * 验证用户名是否存在
     */
    public function checkUsername(){
        if(IS_AJAX){
            $json = array();
            if(!empty(D('AdminUser')->checkUserName(I('post.username','','htmlspecialchars')))){
                $json['isset'] = true;
            }else{
                $json['isset'] = false;
            }
            $this->ajaxReturn($json);
        }
    }

    public function checkEmail(){
        if(IS_AJAX){
            $json = array();
            $user_id = M('admin_user')->where(array('email'=>I('post.email','','htmlentities')))->getField('user_id');
            if($user_id != null){
                $json['isset'] = $user_id == I('post.user_id',0,'intval') ? false : true;
            }else{
                $json['isset'] = false;
            }
            $this->ajaxReturn($json);
        }
    }

    private function getFrom(){
        if(ACTION_NAME == 'edit'){
            $userInfo           = D('AdminUser')->getUser(I('get.id','','intval'));
            $this->id           = $userInfo['id'];
            $this->username     = $userInfo['username'];
            $this->add_time     = $userInfo['add_time'];
            $this->last_time    = $userInfo['last_time'];
            $this->email        = $userInfo['email'];
            $this->last_ip      = $userInfo['last_ip'];
            $this->status       = $userInfo['status'];
            $this->send_submit  = '确认修改';
            $this->user_role    = M('role_user')->where(array('user_id'=>$userInfo['id']))->getField('role_id',true);
        }else{
            $this->id           = '';
            $this->username     = '';
            $this->add_time     = '';
            $this->last_time    = '';
            $this->last_ip      = '';
            $this->status       = '';
            $this->send_submit  = '确认添加';
            $this->user_role    = array();
        }
        $this->status_list = array(
            2=>'启用',
            1=>'禁用',
        );
        $this->roles = M('role')->field('id,name')->select();
        $this->display('user_from');
    }
}
?>