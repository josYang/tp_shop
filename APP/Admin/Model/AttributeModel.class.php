<?php
/**
 * 产品属性实体类
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/4
 * Time: 19:49
 */
namespace Admin\Model;
use Think\Model\RelationModel;
class AttributeModel extends RelationModel{
    protected $_link = array(
        'goods_type' => array(
            'mapping_type'  => self::BELONGS_TO,
            'foreign_key'   => 'cat_id',
            'as_fields'     => 'cat_name'
        ),
    );

    public function getAttrs($cat_id,$limit){
        $field = array('attr_id','cat_id','attr_name','attr_input_type','attr_values','sort_order');
        return $this->field($field)->where(array('cat_id'=>$cat_id))->relation('goods_type')->order('sort_order ASC')->limit($limit)->select();
    }

    /**
     * 查询当前类型下属性总数
     * @param $cat_id
     * @return mixed
     */
    public function total($cat_id){
        return $this->where(array('cat_id'=>$cat_id))->count();
    }
}
?>