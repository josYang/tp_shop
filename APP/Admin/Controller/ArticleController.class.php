<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/16
 * Time: 21:14
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;

class ArticleController extends CommonController
{
    public function index(){
        $this->articles = D('ArticleView')->select();
        $this->display();
    }

    public function add(){
        if(IS_POST){
            $data = array(
                'title' => I('post.title','','htmlentities'),
                'cat_id' => I('post.cat_id',0,'intval'),
                'author' => I('post.author','','htmlentities'),
                'keywords' => I('post.keywords','','htmlentities'),
                'article_type' => I('post.article_type',0,'intval'),
                'content' => I('post.content','','htmlentities'),
                'add_time' => date('Y-m-d h:i:s'),
            );
            M('article')->add($data);
            $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->title = '添加文章';
        $this->submit = '保存添加';
        $this->getFrom();
        $this->display('article_form');
    }

    public function edit(){
        $article_id = I('get.art_id',0,'intval');
        $art_db = M('article');
        if(IS_POST){
            $data = array(
                'article_id' => I('post.article_id',0,'intval'),
                'title' => I('post.title','','htmlentities'),
                'cat_id' => I('post.cat_id',0,'intval'),
                'author' => I('post.author','','htmlentities'),
                'keywords' => I('post.keywords','','htmlentities'),
                'article_type' => I('post.article_type',0,'intval'),
                'content' => I('post.content','','htmlentities'),
                'add_time' => date('Y-m-d h:i:s'),
            );
            $art_db->save($data);
            $this->success('修改成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->article = $art_db->find($article_id);
        $this->title = '修改文章';
        $this->submit = '保存修改';
        $this->getFrom();
        $this->display('article_form');
    }

    public function delete(){
        $article_id = I('get.art_id',0,'intval');
        if($article_id > 0){
            M('article')->delete($article_id);
            $this->success('删除成功',U(CONTROLLER_NAME.'/index'));
        }else{
            $this->errot('操作有误');
        }
    }

    private function getFrom(){
        import('Class.Category',APP_PATH);
        $cats = M('article_cat')->field('sort_order',true)->order('sort_order ASC')->select();
        $this->cats = \Category::unlimitedForLevel($cats);
    }
}