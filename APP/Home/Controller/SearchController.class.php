<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/26
 * Time: 21:41
 */

namespace Home\Controller;
use Think\Controller;
use Think\Page;

class SearchController extends Controller
{
    public function index(){
        $keywords = I('get.keywords','','htmlentities');
        if($keywords == '') $this->error('搜索关键字不得为空');
        $g_db = M('goods');
        $where = array('goods_name'=>array('LIKE','%'.$keywords.'%'),'keywords'=>array('LIKE','%'.$keywords.'%'),'_logic'=>'OR');

        $count = $g_db->where($where)->count();
        $page = new Page($count,C('SYSTEM.page_size'));
        $this->goods_list = $g_db
            ->field('`goods_id`, `goods_name`, `market_price`, `is_new`, `is_best`, `is_hot`, `goods_thumb`')
            ->where($where)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();

        $this->page = $page->show();
        $this->count = $count;
        $this->keywords = $keywords;
        $this->display();
    }
}