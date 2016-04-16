<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/16
 * Time: 18:01
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;

class ArticleCatController extends CommonController{
    public function index(){
        import('Class.Category',APP_PATH);
        $cats = M('article_cat')->order('sort_order ASC')->select();
        $this->cats = \Category::unlimitedForLevel($cats);
        $this->display();
    }

    public function add(){
        $db = M('article_cat');
        if(IS_POST){
            $data = array(
                'cat_name' => I('post.cat_name','','htmlentities'),
                'parent_id' => I('post.parent_id',0,'intval'),
                'sort_order' => I('post.sort_order',50,'intval'),
            );
            $db->add($data);
            $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        import('Class.Category',APP_PATH);
        $parent_cats = $db->field('sort_order',true)->order('sort_order ASC')->select();
        $this->parent_cats = \Category::unlimitedForLevel($parent_cats);
        $this->title = '添加分类';
        $this->submit = '保存添加';
        $this->display('article_cat_from');
    }

    public function edit(){
        $cat_id = I('get.cat_id',0,'intval');
        $db = M('article_cat');
        if(IS_POST){
            $data = array(
                'cat_id' => I('post.cat_id',0,'intval'),
                'cat_name' => I('post.cat_name','','htmlentities'),
                'sort_order' => I('post.sort_order',50,'intval'),
                'parent_id' => I('post.parent_id',0,'intval'),
            );
            $db->save($data);
            $this->success('修改成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        import('Class.Category',APP_PATH);
        $this->cat = $db->find($cat_id);
        $parent_cats = $db->field('sort_order',true)->where(array('cat_id'=>array('neq',$cat_id)))->order('sort_order ASC')->select();
        $this->parent_cats = \Category::unlimitedForLevel($parent_cats);
        $this->title = '修改分类';
        $this->submit = '保存修改';
        $this->display('article_cat_from');
    }

    public function delete(){
        $cat_id = I('get.cat_id',0,'intval');
        if($cat_id > 0){
            M('article_cat')->delete($cat_id);
            $this->success('删除成功',U(CONTROLLER_NAME.'/index'));
        }else{
            $this->error('操作有误');
        }
    }
}