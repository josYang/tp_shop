<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/3/21
 * Time: 20:27
 */
namespace Admin\Controller;
use Admin\Controller;
class AdminUserController extends CommonController{
    public function index(){
        $this->admin = D('AdminUser')->gitUserList();
        $this->display();
    }

    public function edit(){

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
}
?>