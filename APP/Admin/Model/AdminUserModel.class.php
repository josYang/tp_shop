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
    /**
     * 判断管理员是否存在
     * @param $username     管理员名称
     * @return array        管理员信息
     */
    public function getUser ($username){
        $user = M('AdminUser');
        $query = $user->where(array('username'=>$username,'status'=>'2'))->find();
        return $query;
    }
}
?>