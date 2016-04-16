<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/2
 * Time: 21:18
 */
/**
 * 递归
 * @param array $node   要处理的节点数组
 * @param number $pid   父级ID
 * @return array        处理过后的数组
 */
function node_merge($node,$access = null,$pid = 0){
    $arr = array();
    foreach ($node as $v){
        if (is_array($access)){
            $v['access'] = in_array($v['id'], $access) ? 1 : 0;
        }
        if ($v['pid'] == $pid){
            $v['child'] = node_merge($node,$access,$v['id']);
            $arr[]=$v;
        }
    }
    return $arr;
}

function save_name($file_name,$fn){
    $file_name = explode('.',$file_name);
    return $fn().rand().$file_name[0];
}
?>