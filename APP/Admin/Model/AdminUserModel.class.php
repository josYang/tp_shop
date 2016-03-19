<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/3/19
 * Time: 17:50
 */
namespace Admin\Model;
use Think\Model;
class AdminUserModel extends Model{
    public function checkUser ($username){
        $user = M('AdminUser');
        $query = $user->where('`username`="'.$username.'"')->count();
        return $query;
    }
}
?>