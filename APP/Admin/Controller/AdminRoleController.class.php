<?php
/**
 * 角色控制器
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/3/29
 * Time: 22:45
 */
namespace Admin\Controller;
use Admin\Controller;
class AdminRoleController extends CommonController{
    /**
     * 角色列表
     */
    public function index(){
        $this->roles = M('role')->select();
        $this->display();
    }

    /**
     * 添加管理员
     */
    public function add(){
        if(IS_POST){
            $data = array(
                'name'      => I('post.name','','htmlentities'),
                'pid'       => I('post.pid',0,'intval'),
                'status'    => I('post.status',0,'intval'),
                'remark'    => I('post.remark','','htmlentities'),
            );
            M('role')->add($data);
            $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->getFrom();
    }

    /**
     * 修改角色
     */
    public function edit(){
        if(IS_POST){
            $data = array(
                'id'        => I('post.id',0,'intval'),
                'name'      => I('post.name','','htmlentities'),
                'pid'       => I('post.pid',0,'intval'),
                'status'    => I('post.status',0,'intval'),
                'remark'    => I('post.remark','','htmlentities'),
            );
            M('role')->save($data);
            $this->success('修改成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->getFrom();
    }

    /**
     * 删除角色
     */
    public function delete(){
        $id = I('get.id',0,'intval');
        if(!empty($id) && IS_GET){
            M('role')->delete($id);
            $this->success('删除成功',U(CONTROLLER_NAME.'/index'));
            return;
        }else{
            $this->error('操作有误');
        }
    }

    /**
     * 获取表单
     */
    private function getFrom(){
        if(ACTION_NAME == 'edit'){
            $this->title    = '修改角色';
            $role_info      = M('role')->where(array('id'=>I('get.id',0,'intval')))->find();
            $this->id       = $role_info['id'];
            $this->pid      = $role_info['pid'];
            $this->name     = $role_info['name'];
            $this->remark   = $role_info['remark'];
            $this->status   = $role_info['status'];
            $this->submit   = '保存修改';
        }else{
            $this->title    = '添加角色';
            $this->id       = 0;
            $this->pid      = 0;
            $this->name     = '';
            $this->remark   = '';
            $this->status   = 1;
            $this->submit   = '保存添加';
        }
        $this->status_list = array(
            0=>'禁用',
            1=>'启用'
        );
        $this->roles = M('role')->where(array('id'=>array('neq',I('get.id',0,'intval'))))->select();
        $this->display('role_from');
    }
}
?>