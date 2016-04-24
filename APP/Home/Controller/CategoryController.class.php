<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/22
 * Time: 22:52
 */

namespace Home\Controller;
use Think\Controller;
use Think\Page;

class CategoryController extends Controller
{
    public function index(){
        $cat_id         = I('get.id',0,'intval');
        $cart_db        = M('category');
        $goods_db       = M('goods');
        $cat           = $cart_db->find($cat_id);
        if(!$cat) $this->error('页面不存在！');
        $brand_id       = I('get.brand_id',0,'intval');
        $price_min      = I('get.price_min',0,'intval');
        $price_max      = I('get.price_max',0,'intval');
        $filter_attr_str= I('get.filter_attr','','htmlentities');
        $filter_attr_str= preg_match('/^[\d\.]+$/',$filter_attr_str) ? $filter_attr_str : '' ;
        $filter_attr    = empty($filter_attr_str) ? array() : explode('.', $filter_attr_str);
        $sort           = isset($_GET['sort']) && in_array(trim(strtolower($_GET['sort'])),array('goods_id', 'add_time', 'market_price', 'click_count')) ? trim($_GET['sort']) : 'goods_id';
        $order          = isset($_GET['order']) && in_array(trim($_GET['order']),array('DESC', 'ASC')) ? trim($_GET['order']) : 'DESC';

        $carts = $cart_db->field('cat_id,parent_id')->select();

        import('Class.Category',APP_PATH);

        $children = \Category::getChildsId($carts,$cat_id);
        $children[] = $cat_id;

        if($cat['grade']>1){
            $row = $goods_db
                ->field('MIN(`market_price`) AS `min`, MAX(`market_price`) AS `max`')
                ->where(array('cat_id'=>array('IN',$children)))
                ->find();
            $price_grade = 0.0001;

            for($i=-2; $i<= log10($row['max']); $i++) {
                $price_grade *= 10;
            }

            //跨度
            $dx = ceil(($row['max'] - $row['min']) / ($cat['grade']) / $price_grade) * $price_grade;

            if($dx == 0) $dx = $price_grade;

            for($i = 1; $row['min'] > $dx * $i; $i ++);

            for($j = 1; $row['min'] > $dx * ($i-1) + $price_grade * $j; $j++);
            $row['min'] = $dx * ($i-1) + $price_grade * ($j - 1);

            for(; $row['max'] >= $dx * $i; $i ++);
            $row['max'] = $dx * ($i) + $price_grade * ($j - 1);

            $price_grade = $goods_db
                ->field('(FLOOR((`market_price` - ' . $row['min'] . ') / ' . $dx . ')) AS `sn`, COUNT(*) AS `goods_num`')
                ->where(array('cat_id'=>array('IN',$children)))
                ->group('sn')
                ->select();

            foreach ($price_grade as $key=>$val){
                $temp_key = $key + 1;
                $price_grade[$temp_key]['goods_num'] = $val['goods_num'];
                $price_grade[$temp_key]['start'] = $row['min'] + round($dx * $val['sn']);
                $price_grade[$temp_key]['end'] = $row['min'] + round($dx * ($val['sn'] + 1));
                $price_grade[$temp_key]['price_range'] = $price_grade[$temp_key]['start'] . '&nbsp;-&nbsp;' . $price_grade[$temp_key]['end'];
                $price_grade[$temp_key]['url'] = U(__ACTION__,array('id'=>$cat_id,'brand_id'=>$brand_id,'price_min'=>$price_grade[$temp_key]['start'],'price_max'=>$price_grade[$temp_key]['end'],'filter_attr'=>$filter_attr_str,'sort'=>$sort,'order'=>$order));

                if(isset($_GET['price_min']) && $price_grade[$temp_key]['start'] == $price_min && $price_grade[$temp_key]['end'] == $price_max){
                    $price_grade[$temp_key]['selected'] = 1;
                }else{
                    $price_grade[$temp_key]['selected'] = 0;
                }
            }

            $price_grade[0]['start'] = 0;
            $price_grade[0]['end'] = 0;
            $price_grade[0]['price_range'] = '全部';
            $price_grade[0]['url'] = U(__ACTION__,array('id'=>$cat_id,'brand_id'=>$brand_id,'filter_attr'=>$filter_attr_str,'sort'=>$sort,'order'=>$order));
            $price_grade[0]['selected'] = empty($price_max) ? 1 : 0;

            $this->price_grade = $price_grade;
        }

        $brands = M()
            ->table('__GOODS__ AS g, __BRAND__ AS b')
            ->field('b.brand_id,b.brand_name,COUNT(*) AS goods_num')
            ->where(array('g.cat_id'=>array('IN',$children)))
            ->where('b.brand_id=g.brand_id')
            ->group('b.brand_id')
            ->having('`goods_num` > 0 ')
            ->order('b.sort_order ASC')
            ->select();

        foreach ($brands AS $key => $val){
            $temp_key = $key + 1;
            $brands[$temp_key]['brand_name'] = $val['brand_name'];
            $brands[$temp_key]['url'] = U(__ACTION__,array('id'=>$cat_id,'brand_id'=>$val['brand_id'],'price_min'=>$price_min,'price_max'=>$price_max,'filter_attr'=>$filter_attr_str,'sort'=>$sort,'order'=>$order));

            if ($brand_id == $brands[$key]['brand_id']){
                $brands[$temp_key]['selected'] = 1;
            }else{
                $brands[$temp_key]['selected'] = 0;
            }
        }

        $brands[0]['brand_name'] = '全部';
        $brands[0]['url'] = U(__ACTION__,array('id'=>$cat_id,'price_min'=>$price_min,'price_max'=>$price_max,'filter_attr'=>$filter_attr_str,'sort'=>$sort,'order'=>$order));
        $brands[0]['selected'] = empty($brand_id) ? 1 : 0;

        $this->brands = $brands;

        /* 属性筛选 */
        $ext = ''; //商品查询条件扩展
        if ($cat['filter_attr'] > 0){
            $cat_filter_attr = explode(',', $cat['filter_attr']);       //提取出此分类的筛选属性
            $all_attr_list = array();

            foreach ($cat_filter_attr AS $key => $value){
                //获取属性列表
                $temp_name = M()
                    ->table('__GOODS__ AS g, __ATTRIBUTE__ AS a')
                    ->where(array('g.cat_id'=>array('IN',$children),'a.attr_id'=>$value))
                    ->getField('a.attr_name');
                if($temp_name){
                    $all_attr_list[$key]['filter_attr_name'] = $temp_name;

                    $attr_list = M()
                        ->table('__GOODS_ATTR__ AS a, __GOODS__ AS g')
                        ->field('a.`attr_id`,MIN(a.`goods_attr_id`) AS `goods_id`, a.`attr_value` AS `attr_value`')
                        ->where(array('g.`cat_id`'=>array('IN',$children),'a.`attr_id`'=>$value))
                        ->where('g.`goods_id` = a.`goods_id`')
                        ->group('a.`attr_value`')
                        ->select();

                    $temp_arrt_url_arr = array();

                    for ($i = 0; $i < count($cat_filter_attr); $i++){   //获取当前url中已选择属性的值，并保留在数组中
                        $temp_arrt_url_arr[$i] = !empty($filter_attr[$i]) ? $filter_attr[$i] : 0;
                    }

                    $temp_arrt_url_arr[$key] = 0;                           //“全部”的信息生成
                    $temp_arrt_url = implode('.', $temp_arrt_url_arr);
                    $all_attr_list[$key]['attr_list'][0]['attr_value'] = '全部';
                    $all_attr_list[$key]['attr_list'][0]['url'] = U(__ACTION__,array('id'=>$cat_id,'brand_id'=>$brand_id,'price_min'=>$price_min,'price_max'=>$price_max,'filter_attr'=>$temp_arrt_url,'sort'=>$sort,'order'=>$order));
                    $all_attr_list[$key]['attr_list'][0]['selected'] = empty($filter_attr[$key]) ? 1 : 0;

                    foreach ($attr_list as $k => $v){
                        $temp_key = $k + 1;
                        $temp_arrt_url_arr[$key] = $v['goods_id'];       //为url中代表当前筛选属性的位置变量赋值,并生成以‘.’分隔的筛选属性字符串
                        $temp_arrt_url = implode('.', $temp_arrt_url_arr);
                        $all_attr_list[$key]['attr_list'][$temp_key]['attr_value'] = $v['attr_value'];
                        $all_attr_list[$key]['attr_list'][$temp_key]['url'] = U(__ACTION__,array('id'=>$cat_id,'brand_id'=>$brand_id,'price_min'=>$price_min,'price_max'=>$price_max,'filter_attr'=>$temp_arrt_url,'sort'=>$sort,'order'=>$order));

                        if (!empty($filter_attr[$key]) AND $filter_attr[$key] == $v['goods_id']){
                            $all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 1;
                        }else{
                            $all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 0;
                        }
                    }
                }
            }
            $this->filter_attr_list = $all_attr_list;

            if (!empty($filter_attr)){
                $ext = array();
                foreach ($filter_attr as $k => $v) {
                    if (is_numeric($v) && $v != 0 &&isset($cat_filter_attr[$k])){
                        $ext_group_goods = M()
                            ->table('__GOODS_ATTR__ AS a, __GOODS_ATTR__ AS b')
                            ->field('b.`goods_id`')
                            ->where(array('b.`attr_id`'=>$cat_filter_attr[$k],'a.`goods_attr_id`'=>$v))
                            ->where('b.`attr_value` = a.`attr_value`')
                            ->distinct(true)
                            ->select();

                        if(!empty($ext_group_goods)){
                            $gid_arr = array();
                            foreach ($ext_group_goods as $val){
                                $gid_arr[] = $val['goods_id'];
                            }
                            $ext = empty($ext) ? $gid_arr : array_intersect($ext,$gid_arr);
                        }
                    }
                }
            }
        }

        $where = array(
            'cat_id' => array('IN',$children)
        );

        if($brand_id != 0) $where['brand_id'] = $brand_id;
        if($price_min != 0) $where['market_price'] = array('EGT',$price_min);
        if($price_max != 0) $where['market_price'] = array('ELT',$price_max);
        if(!empty($filter_attr_str) && !preg_match('/^[0\.]+$/',$filter_attr_str)) $where['goods_id'] = empty($ext) ? -1 : array('IN',$ext);

        $count = $goods_db->where($where)->count();

        $page = new Page($count,C('SYSTEM.page_size'));

        $this->goods_list = $goods_db
            ->field('`goods_id`, `goods_name`, `market_price`, `is_new`, `is_best`, `is_hot`, `goods_thumb`')
            ->where($where)
            ->limit($page->firstRow.','.$page->listRows)
            ->order($sort.' '.$order)
            ->select();

        $this->page = $page->show();
        $this->count = $count;

        $url_info = array(
            'id'        => $cat_id,
            'brand_id'  => $brand_id,
            'price_min' => $price_min,
            'price_max' => $price_max,
            'order'     => $order,
            'p'         => I('get.p',1,'intval')
        );

        $n_order = $order == 'DESC' ? 'ASC' : 'DESC';

        $this->add_time_sort = U(__ACTION__,$sort == 'add_time' ? array_merge($url_info,array('sort'=>'add_time','order'=>$n_order)) : array_merge($url_info,array('sort'=>'add_time')));
        $this->market_price_sort = U(__ACTION__,$sort == 'market_price' ? array_merge($url_info,array('sort'=>'market_price','order'=>$n_order)) : array_merge($url_info,array('sort'=>'market_price')));
        $this->click_count_sort = U(__ACTION__,$sort == 'click_count' ? array_merge($url_info,array('sort'=>'click_count','order'=>$n_order)) : array_merge($url_info,array('sort'=>'click_count')));

        $this->sort = $sort;
        $this->order = $order;

        $this->display();
    }
}