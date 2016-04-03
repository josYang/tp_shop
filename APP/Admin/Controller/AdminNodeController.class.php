<?php
/**
 * 节点控制器
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/3/29
 * Time: 22:45
 */
namespace Admin\Controller;
use Admin\Controller;
class AdminNodeController extends CommonController{
    /**
     * 节点列表
     */
    public function index(){
        $node = M('node')->field('id,name,title,pid')->order('sort')->select();
        $this->node = node_merge($node);
//        dump($node);
//        dump($this->node);
        $this->display();
    }

    public function add(){
        if(IS_POST){
            $data = array(
                'name'      => I('post.name','','htmlentities'),
                'title'     => I('post.title','','htmlentities'),
                'status'    => I('post.status','','intval'),
                'remark'    => I('post.remark','','htmlentities'),
                'sort'      => I('post.sort','','intval'),
                'pid'       => I('post.pid','','intval'),
                'level'     => I('post.level','','intval'),
            );
            M('node')->add($data);
            $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->getFrom();
    }

    public function edit(){
        if(IS_POST){
            $data = array(
                'id'        => I('post.id',0,'intval'),
                'name'      => I('post.name','','htmlentities'),
                'title'     => I('post.title','','htmlentities'),
                'status'    => I('post.status',0,'intval'),
                'remark'    => I('post.remark','','htmlentities'),
                'sort'      => I('post.sort',50,'intval'),
                'pid'       => I('post.pid',0,'intval'),
                'level'     => I('post.level',0,'intval'),
            );
            M('node')->save($data);
            $this->success('修改成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->getFrom();
    }

    /**
     * 获取表单
     */
    private function getFrom(){
        if(ACTION_NAME == 'edit'){
            $id = I('get.id',0,'intval');
            $node_info      = M('node')->find($id);
            $this->title = '修改节点';
            $this->id       = $node_info['id'];
            $this->name     = $node_info['name'];
            $this->nodetitle= $node_info['title'];
            $this->status   = $node_info['status'];
            $this->remark   = $node_info['remark'];
            $this->sort     = $node_info['sort'];
            $this->level    = $node_info['level'];
            $this->pid      = $node_info['pid'];
            $this->submit   = '保存修改';
            switch ($node_info['level']){
                case 1:
                    $this->type = '应用';
                    break;
                case 2:
                    $this->type = '控制器';
                    break;
                case 3:
                    $this->type = '动作方法';
                    break;
            }
        }else{
            $this->title = '增加节点';
            $this->name     = '';
            $this->nodetitle= '';
            $this->status   = 0;
            $this->remark   = '';
            $this->sort     = 50;
            $this->level = I('level',1,'intval');
            $this->pid = I('pid',0,'intval');
            $this->submit   = '保存添加';
            switch ($this->level){
                case 1:
                    $this->type = '应用';
                    break;
                case 2:
                    $this->type = '控制器';
                    break;
                case 3:
                    $this->type = '动作方法';
                    break;
            }
        }
        $this->status_list = array(
            0 => '禁用',
            1 => '启用',
        );
        $this->display('node_from');
    }
}
?>