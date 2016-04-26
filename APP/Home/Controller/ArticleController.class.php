<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/26
 * Time: 19:57
 */

namespace Home\Controller;
use Think\Controller;

class ArticleController extends Controller
{
    public function index(){
        $article_id = I('get.id',0,'intval');
        $article = M('Article')->find($article_id);
        $this->article = $article;
        $this->display();
    }
}