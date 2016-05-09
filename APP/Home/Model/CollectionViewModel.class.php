<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/5/9
 * Time: 23:11
 */

namespace Home\Model;
use Think\Model\ViewModel;

class CollectionViewModel extends ViewModel
{
    public $viewFields = array(
        'collect_goods' => array(
            '_as' => 'cg'
        ),
        'goods' => array(
            'goods_id',
            'goods_name',
            'goods_thumb',
            'market_price',
            '_as' => 'g',
            '_type' => 'LEFT',
            '_on' => 'g.goods_id=cg.goods_id'
        )
    );
}