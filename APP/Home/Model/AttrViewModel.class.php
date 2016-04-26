<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/25
 * Time: 23:48
 */

namespace Home\Model;
use Think\Model\ViewModel;

class AttrViewModel extends ViewModel
{
    public $viewFields = array(
        'goods_attr' => array(
            'goods_attr_id',
            'attr_value',
            'attr_price',
            '_as' => 'g'
        ),
        'attribute'  => array(
            'attr_id',
            'attr_name',
            'attr_group',
            'is_linked',
            'attr_type',
            '_as' => 'a',
            '_type' => 'LEFT',
            '_on' => 'a.attr_id = g.attr_id'
        )
    );
}