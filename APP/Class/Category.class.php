<?php
/*
 * FZ 导航分类文件
 * ============================================================================
 * 版权所有 2015-2012 长沙江东科技有限公司，并保留所有权利。
 * 网站地址: http://www.jdx86.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: 小武
 * ThinkPad   2015年10月29日
 */
class Category {
    /**
     * 组合一维数组
     * @param array $cate       所有导航数组
     * @param string $html      隔离字符
     * @param number $pid       父级ID
     * @param number $level     当前导航等级
     * @return array            组合后的数组
     */
    static public function unlimitedForLevel($cate, $html='--', $pid = 0, $level = 0){
        $arr = array();
        foreach ($cate as $v){
            if ($v['parent_id'] == $pid){
                $v['level'] = $level+1;
                $v['html'] = str_repeat($html, $level);
                $arr[]=$v;
                $arr = array_merge($arr,self::unlimitedForLevel($cate,$html,$v['cat_id'],$level+1));
            }
        }
        return $arr;
    }
    /**
     * 组合多维数组
     * @param array $cate       所有导航数组
     * @param string $name      循环键名
     * @param number $pid       父级ID
     * @return array            组合后的数组
     */
    static public function unlimitedForLayer($cate, $name = 'child', $pid = 0){
        $arr = array();
        foreach ($cate as $v){
            if ($v['parent_id']==$pid){
                $v[$name] = self::unlimitedForLayer($cate, $name, $v['cat_id']);
                $arr[] = $v;
            }
        }
        return $arr;
    }
    /**
     * 传递子分类ID，返回所有父级分类
     * @param array $cate       所有导航数组
     * @param number $id        当前子级ID
     * @return array            所有父级数组
     */
    static public function getParents($cate,$id){
        $arr = array();
        foreach ($cate as $v){
            if ($v['cat_id'] == $id){
                $arr[]=$v;
                $arr = array_merge(self::getParents($cate, $v['parent_id']),$arr);
            }
        }
        return $arr;
    }
    /**
     * 传递一个父级ID，返回所有子级ID
     * @param array $cate       所有导航数组
     * @param number $pid       父级ID
     * @return array            ID数组
     */
    static public function getChildsId($cate, $pid){
        $arr = array();
        foreach ($cate as $v){
            if ($v['parent_id'] == $pid){
                $arr[]=$v['cat_id'];
                $arr = array_merge($arr,self::getChildsId($cate, $v['cat_id']));
            }
        }
        return $arr;
    }
    /**
     * 传递一个父级ID，返回所有子级
     * @param array $cate       所有导航数组
     * @param number $pid       父级ID
     * @return array            ID数组
     */
    static public function getChilds($cate, $pid){
        $arr = array();
        foreach ($cate as $v){
            if ($v['parent_id'] == $pid){
                $arr[]=$v;
                $arr = array_merge($arr,self::getChilds($cate, $v['cat_id']));
            }
        }
        return $arr;
    }
}
?>