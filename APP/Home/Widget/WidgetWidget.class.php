<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/21
 * Time: 23:33
 */

namespace Home\Widget;
use Think\Controller;

class WidgetWidget extends Controller
{
    public function nav(){
        import('Class.Category',APP_PATH);
        $categorys = M('category')->field('cat_id,cat_name,parent_id')->where(array('status'=>2))->order('sort_order ASC')->select();
        $this->categorys = \Category::unlimitedForLayer($categorys);
        $this->display('Widget:nav');
    }

    public function hotSearch(){
        $hot_searchs = explode(',', C('SYSTEM.hot_search'));
        $html = '';
        foreach($hot_searchs as $val){
            $html .= '<a href=" ' . U('/search',array('keywords'=>$val)) . ' ">' . $val . '</a>&nbsp;|&nbsp;';
        }
        return $html;
    }

    public function choose($type){
        switch($type){
            case 'hot':
                $this->chooses = M('goods')->field('goods_id,goods_name,market_price,goods_thumb')->where('`is_hot`=1')->order('sort_order ASC')->limit('0,20')->select();
                $this->label = '热卖产品';
                $this->choose_id = 'Hot';
                break;
            case 'best':
                $this->chooses = M('goods')->field('goods_id,goods_name,market_price,goods_thumb')->where('`is_best`=1')->order('sort_order ASC')->limit('0,20')->select();
                $this->label = '精品推荐';
                $this->choose_id = 'Best';
                break;
            case 'new':
                $this->chooses = M('goods')->field('goods_id,goods_name,market_price,goods_thumb')->where('`is_new`=1')->order('sort_order ASC')->limit('0,20')->select();
                $this->label = '新品推荐';
                $this->choose_id = 'New';
                break;
        }
        $this->display('Widget:choose');
    }

    public function cat_goods($cid){
        import('Class.Category',APP_PATH);
        $cat_db = M('category');
        $this->category_name = $cat_db->where('`cat_id`='.$cid)->getField('cat_name');
        $this->category_id = $cid;
        $cats = $cat_db->field('cat_id,parent_id')->select();
        $pids = \Category::getChildsId($cats,$cid);
        $pids[] = $cid;
        $this->goods_list = M('goods')->where(array('parent_id'=>array('IN',$pids)))->field('goods_id,goods_name,market_price,goods_thumb')->select();
        $this->display('Widget:cat_goods');
    }

    public function article($cid,$num){
        $this->articles = M('article')->where('`cat_id`='.$cid)->field('article_id,title')->order('add_time DESC')->limit($num)->select();
        $this->art_cart_name = M('article_cat')->where('`cat_id`='.$cid)->getField('cat_name');
        $this->display('Widget:article');
    }

    public function left_goods($type,$cid){
        import('Class.Category',APP_PATH);
        $cats = M('category')->field('cat_id,parent_id')->select();
        $pids = \Category::getChildsId($cats,$cid);
        $pids[] = $cid;
        $where = array(
            'cat_id' => array('IN',$pids)
        );
        switch($type){
            case 'hot':
                $where['is_hot'] = 1;
                $this->label = '热卖产品';
                break;
            case 'best':
                $where['is_best'] = 1;
                $this->label = '新品推荐';
                break;
            case 'new':
                $where['is_new'] = 1;
                $this->label = '新品推荐';
                break;
        }
        $this->goods_list = M('goods')
            ->field('goods_id,goods_name,market_price,goods_thumb')
            ->where($where)
            ->select();
        $this->display('Widget:left_goods');
    }

    public function position(){
        $id = I('get.id',0,'intval');
        import('Class.Category',APP_PATH);
        $cat_arr = M('category')->field('cat_name,parent_id,cat_id')->order('sort_order ASC')->select();
        $html = '<a href="/">首页</a>&nbsp;';
        switch (CONTROLLER_NAME){
            case 'Category':
                $position = \Category::getParents($cat_arr,$id);
                foreach ($position as $item) {
                    $html .= '<code>&gt;</code>&nbsp;<a href="' . U('/c_'.$item['cat_id']) . '">' . $item['cat_name'] . '</a>';
                }
                break;
            case 'Goods':
                $goods = M('goods')->field('cat_id,goods_name')->find($id);
                $position = \Category::getParents($cat_arr,$goods['cat_id']);
                foreach ($position as $item) {
                    $html .= '<code>&gt;</code>&nbsp;<a href="' . U('/c_'.$item['cat_id']) . '">' . $item['cat_name'] . '</a>';
                }
                $html .= '<code>&gt;</code>&nbsp;<a href="' . U('/c_'.$goods['goods_id']) . '">' . sub_str($goods['goods_name']) . '</a>';
                break;
        }
        return $html;
    }
}