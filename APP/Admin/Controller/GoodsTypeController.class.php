<?php
/**
 * 商品类型
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/4
 * Time: 14:19
 */
namespace Admin\Controller;
use Admin\Controller;
class GoodsTypeController extends CommonController{
    public function index(){
        $types = M('goods_type')->select();
        foreach ($types as &$type) {
            $type['attr_total'] = M('attribute')->where(array('cat_id'=>$type['cat_id']))->count();
        }
        $this->types = $types;
        $this->display();
    }

    /**
     * 添加产品类型
     */
    public function add(){
        if(IS_POST){
            $data = array(
                'cat_name'      => I('post.cat_name','','htmlentities'),
                'attr_group'    => I('post.attr_group','','htmlentities'),
            );
            M('goods_type')->add($data);
            $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->title    = '添加类型';
        $this->submit   = '保存添加';
        $this->display('goods_type_from');
    }

    /**
     * 修改类型
     */
    public function edit(){
        if(IS_POST){
            $data = array(
                'cat_id'        => I('post.cat_id',0,'intval'),
                'cat_name'      => I('post.cat_name','','htmlentities'),
                'attr_group'    => I('post.attr_group','','htmlentities'),
            );
            M('goods_type')->save($data);
            $this->success('修改成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $tid = I('get.id',0,'intval');
        if($tid > 0){
            $this->title    = '修改类型';
            $this->submit   = '保存修改';
            $this->type     = M('goods_type')->find($tid);
            $this->display('goods_type_from');
        }else{
            $this->error('操作有误');
        }
    }

    public function get_group(){
        if(IS_AJAX){
            $group = M('goods_type')->field('attr_group')->find(I('post.cat_id',0,'intval'));
            $json = explode(',',$group['attr_group']);
            $this->ajaxReturn($json);
        }
    }

    /**
     * 删除类型
     */
    public function delete(){
        $tid = I('get.id',0,'intval');
        if($tid > 0){
            M('goods_type')->delete($tid);
            $this->success('删除成功',U(CONTROLLER_NAME.'/index'));
        }else{
            $this->error('操作有误');
        }
    }
}
?>