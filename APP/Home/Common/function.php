<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/24
 * Time: 20:19
 */
/**
 * 截取字符串
 * @param $str
 * @param int $len
 * @param string $encoding
 * @return string
 */
function sub_str($str,$len = 50,$encoding='UTF-8'){
    $str_len = mb_strlen($str,$encoding);
    if($str_len < $len){
        return $str;
    }else{
        return mb_substr($str,0,$len,$encoding).'...';
    }
}
?>