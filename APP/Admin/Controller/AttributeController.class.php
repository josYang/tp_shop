<?php
/**
 * 属性管理
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/4
 * Time: 15:29
 */
namespace Admin\Controller;
use Admin\Controller;
use Think\Page;

class AttributeController extends CommonController{
    public function index(){
        $cat_id     =  I('get.cat_id',0,'intval');
        $attr_db    = D('Attribute');
        $total      = $attr_db->total($cat_id);
        $page       = new Page($total,C('SYSTEM.page_size'));

        $this->attrs = $attr_db->getAttrs($cat_id,$page->firstRow.','.$page->listRows);
        $this->show = $page->show();
        $this->cat_id = $cat_id;
        $this->input_type_list = array('手工录入','从列表中选择','多行文本框');

        $this->display();
    }

    /**
     * 添加属性
     */
    public function add(){
        if(IS_POST){
            $cat_id = I('post.cat_id', 0, 'intval');
            $data = array(
                'cat_id'            => $cat_id,
                'attr_name'         => I('post.attr_name', '', 'htmlentities'),
                'attr_input_type'   => I('post.attr_input_type', 0, 'intval'),
                'attr_type'         => I('post.attr_type', 0, 'intval'),
                'attr_values'       => I('post.attr_values', '', 'htmlentities'),
                'sort_order'        => I('post.sort_order', 0, 'intval'),
                'is_linked'         => I('post.is_linked', 0, 'intval'),
                'attr_group'        => I('post.attr_group', 0, 'intval'),
            );
            M('attribute')->add($data);
            $this->success('添加成功',U(CONTROLLER_NAME.'/index',array('cat_id'=>$cat_id)));
            return;
        }
        $this->cat_id   = I('get.cat_id',0,'intval');
        $this->title    = '添加属性';
        $this->submit   = '保存添加';
        $this->getFrom();
        $this->display('attr_from');
    }

    public function edit(){
        if(IS_POST){
            $cat_id = I('post.cat_id', 0, 'intval');
            $data = array(
                'attr_id'           => I('post.attr_id',0,'intval'),
                'cat_id'            => $cat_id,
                'attr_name'         => I('post.attr_name', '', 'htmlentities'),
                'attr_input_type'   => I('post.attr_input_type', 0, 'intval'),
                'attr_type'         => I('post.attr_type', 0, 'intval'),
                'attr_values'       => I('post.attr_values', '', 'htmlentities'),
                'sort_order'        => I('post.sort_order', 0, 'intval'),
                'is_linked'         => I('post.is_linked', 0, 'intval'),
                'attr_group'        => I('post.attr_group', 0, 'intval'),
            );
            M('attribute')->save($data);
            $this->success('修改成功',U(CONTROLLER_NAME.'/index',array('cat_id'=>$cat_id)));
            return;
        }
        $attr     = M('attribute')->find(I('get.id',0,'intval'));
        $this->cat_id   = $attr['cat_id'];
        $this->attr     = $attr;
        $this->title    = '修改属性';
        $this->submit   = '保存修改';
        $this->getFrom();
        $this->display('attr_from');
    }

    /**
     * 删除属性
     */
    public function delete(){
        $attr_id = I('get.id',0,'intval');
        if($attr_id > 0){
            M('attribute')->delete($attr_id);
            $this->success('删除成功',U(MODULE_NAME.'/GoodsType/index'));
        }else{
            $this->error('操作有误');
        }
    }


    private function getFrom(){
        $this->types    = M('goods_type')->field('attr_group',true)->select();
        $this->linked_list = array('否','是');
        $this->attr_type_list = array('唯一属性','单选属性','复选属性');
        $this->input_type_list = array('手工录入','从下面的列表中选择( * 英文,逗号分开)','多行文本框');
    }
}
?>