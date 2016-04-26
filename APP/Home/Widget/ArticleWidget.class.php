<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/26
 * Time: 20:23
 */

namespace Home\Widget;
use Think\Controller;

class ArticleWidget extends Controller
{
    public function nav($cid,$num){
        $this->articles = M('article')->where('`cat_id`='.$cid)->field('article_id,title')->order('add_time DESC')->limit($num)->select();
        $this->art_cart_name = M('article_cat')->where('`cat_id`='.$cid)->getField('cat_name');
        $this->display('Widget:art_nav');
    }

    public function footer($cid,$num){
        $this->articles = M('article')->where('`cat_id`='.$cid)->field('article_id,title')->order('add_time DESC')->limit($num)->select();
        $this->art_cart_name = M('article_cat')->where('`cat_id`='.$cid)->getField('cat_name');
        $this->display('Widget:art_foot');
    }
}