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

/**
 * 上传文件命名规则
 * @param $file_name
 * @param $fn
 * @return string
 */
function save_name($file_name,$fn){
    $file_name = explode('.',$file_name);
    return $fn().rand().$file_name[0];
}

/**
 * 生成配置文件
 * @param string $name 文件名称
 * @param mixed $value 配置数组
 * @param string $path 文件路径
 * @return mixed
 */
function create_config_file($name, $value='', $path=CONF_PATH) {
    $filename       = $path . $name . '.php';
    if ('' !== $value) {
        if (is_null($value)) {
            // 删除缓存
            return false !== strpos($name,'*')?array_map("unlink", glob($filename)):unlink($filename);
        } else {
            // 缓存数据
            $dir            =   dirname($filename);
            // 目录不存在则创建
            if (!is_dir($dir)) mkdir($dir,0755,true);
            return file_put_contents($filename, strip_whitespace("<?php\treturn " . var_export($value, true) . ";?>"));
        }
    }
    return false;
}
?>