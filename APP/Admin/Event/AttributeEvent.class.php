<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/6
 * Time: 20:06
 */
namespace Admin\Event;
class AttributeEvent {
    /**
     * 重组属性数组
     * @param $arr
     * @return array
     */
    public function get_attr_list($arr){
        $list = array();
        foreach ($arr as $val){
            $list[$val['cat_id']][] = array($val['attr_id']=>$val['attr_name']);
        }
        return $list;
    }

    /**
     * 获得商品类型的列表
     * @param $selected
     * @param $res
     * @return string
     */
    public function goods_type_list($selected,$res){
        $lst = '';
        foreach($res as $row){
            $lst .= "<option value='$row[cat_id]'";
            $lst .= ($selected == $row['cat_id']) ? ' selected="true"' : '';
            $lst .= '>' . htmlspecialchars($row['cat_name']). '</option>';
        }
        return $lst;
    }
}
?>