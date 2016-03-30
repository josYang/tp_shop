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

    public function edit(){
        $model = D('AdminUser');
        $this->title = '修改管理员';
        if($_SERVER['REQUEST_METHOD'] == 'post'){
            var_dump(I('post'));
            //$model->editUser();
        }
        $this->getFrom($model);
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
            }
        }else{
            $this->error('操作有误');
        }

    }

    private function getFrom($model){
        $userInfo = $model->getUser(I('get.id','','intval'));
        if(!empty($userInfo)){
            $this->id = $userInfo['id'];
            $this->username = $userInfo['username'];
            $this->add_time = $userInfo['add_time'];
            $this->last_time = $userInfo['last_time'];
            $this->last_ip = $userInfo['last_ip'];
            $this->status = $userInfo['status'];
            $this->send_submit = '确认修改';
            $this->action = U(CONTROLLER_NAME.'/edit');
        }else{
            $this->id = '';
            $this->username = '';
            $this->add_time = '';
            $this->last_time = '';
            $this->last_ip = '';
            $this->status = '';
            $this->send_submit = '确认添加';
            $this->action = U(CONTROLLER_NAME.'/add');
        }
        $this->status_list = array(
            2=>'启用',
            1=>'禁用',
        );
        $this->display('user_from');
    }
}
?>