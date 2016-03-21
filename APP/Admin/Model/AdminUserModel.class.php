<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/3/19
 * Time: 17:50
 */
namespace Admin\Model;
use Think\Model\RelationModel;
class AdminUserModel extends RelationModel{
    protected $_link = array(
        'role' => array(
            'mapping_type'=>self::MANY_TO_MANY,
            'foreign_key'=>'user_id',
            'relation_key'=>'role_id',
            'relation_table'=>'shop_role_user',
            'mapping_fields'=>'id,name,remark',
        ),
    );

    /**
     * 获取管理员信息
     * @param $username     管理员名称
     * @return array        管理员信息
     */
    public function checkUserName ($username){
        $user = M('AdminUser');
        $query = $user->field('id,password,status')->where(array('username'=>$username,'status'=>'2'))->find();
        return $query;
    }

    /**
     * 获取管理员列表
     * @return array
     */
    public function gitUserList(){
        return $this->field('password',true)->relation('role')->select();
    }

    /**
     * 删除管理员操作
     * @param $user_id int
     * @return int  删除条数
     */
    public function deleteUser($user_id){
        return $this->delete($user_id);
    }
}
?>