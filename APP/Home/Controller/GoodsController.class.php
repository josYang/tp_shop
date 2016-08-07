<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/22
 * Time: 22:54
 */

namespace Home\Controller;
use Think\Controller;

class GoodsController extends Controller
{
    public function index(){
        $goods_id = I('get.id',0,'intval');
        $g_db = M('goods');
        $goods = $g_db->find($goods_id);
        if(empty($goods)) $this->error('页面不存在');
        $this->gallerys = M('goods_gallery')->where('goods_id='.$goods_id)->select();

        $grp = M()
            ->table('__GOODS_TYPE__ AS gt, __GOODS__ AS g')
            ->where('g.`goods_id`=' . $goods_id .' AND gt.`cat_id` = g.`goods_type`')
            ->getField('attr_group');

        if(!empty($grp)) $groups = explode(",", strtr($grp, "\r", ''));

        $res = D('AttrView')
            ->where('g.`goods_id`='.$goods_id)
            ->order('a.`sort_order`,g.`attr_price`,g.`goods_attr_id`')
            ->select();

        $position = array(
            'pro' => array(),       // 属性
            'spe' => array(),       // 规格
            'lnk' => array()        // 关联的属性
        );

        foreach ($res AS $row){
            $row['attr_value'] = str_replace("\n", '<br />', $row['attr_value']);

            if ($row['attr_type'] == 0){
                $group = (isset($groups[$row['attr_group']])) ? $groups[$row['attr_group']] : '商品属性';

                $position['pro'][$group][$row['attr_id']]['name']  = $row['attr_name'];
                $position['pro'][$group][$row['attr_id']]['value'] = $row['attr_value'];
            }else{
                $position['spe'][$row['attr_id']]['attr_type'] = $row['attr_type'];
                $position['spe'][$row['attr_id']]['name']      = $row['attr_name'];
                $position['spe'][$row['attr_id']]['values'][]  = array(
                    'label'        => $row['attr_value'],
                    'price'        => $row['attr_price'],
                    'format_price' => number_format(abs($row['attr_price']), 2, '.', ''),
                    'id'           => $row['goods_attr_id']
                );
            }
            if ($row['is_linked'] == 1){
                /* 如果该属性需要关联，先保存下来 */
                $position['lnk'][$row['attr_id']]['name']  = $row['attr_name'];
                $position['lnk'][$row['attr_id']]['value'] = $row['attr_value'];
            }
        }

        $this->properties    = $position['pro'];
        $this->specification = $position['spe'];
        $this->goods = $goods;

        $g_db->where('`goods_id`='.$goods_id)->setInc('click_count',1);

        $this->display();
    }

    public function getPrice()
    {
        $json = array();
        $json['price'] = 0;
        $attr_db = M('goods_attr');
        $goods_db = M('goods');

        foreach ($_POST as $key=>$item) {
            if($key == 'spec_goods_id'){
                $json['price'] += $goods_db->where("`goods_id`={$item}")->getField('market_price');
            }else{
                if(is_array($item) && !empty($item)){
                    foreach ($item as $goods_attr_id) {
                        $json['price'] += $attr_db->where("`goods_attr_id` = {$goods_attr_id}")->getField('attr_price');
                    }
                }else{
                    $json['price'] += $attr_db->where("`goods_attr_id` = {$item}")->getField('attr_price');
                }
            }
        }
        $this->ajaxReturn($json);
    }
}