<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/16
 * Time: 22:15
 */

namespace Admin\Model;
use Think\Model\ViewModel;

class ArticleViewModel extends ViewModel
{
    public $viewFields = array(
        'article' => array(
            'article_id','title','article_type','add_time',
        ),
        'article_cat' => array(
            'cat_name',
            '_on' => 'article.cat_id=article_cat.cat_id',
            '_type'=>'LEFT'
        )
    );
}