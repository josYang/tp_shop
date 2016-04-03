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

    public function addUser($data){
        return $this->add($data);
    }

    /**
     * 修改管理员
     * @param $data
     * @return bool
     */
    public function editUser($data){
        return $this->save($data);
    }

    /**
     * 获取管理员信息
     * @param $username     管理员名称
     * @return array        管理员信息
     */
    public function checkUserName ($username){
        $user = M('AdminUser');
        $query = $user->field('id,password,status')->where(array('username'=>$username))->find();
        return $query;
    }

    /**
     * 获取管理员列表
     * @return array
     */
    public function getUserList(){
        return $this->field('password',true)->relation('role')->select();
    }

    /**
     * 获取用户名
     * @param $user_id
     * @return string
     */
    public function getUserName($user_id){
        return $this->where(array('id'=>$user_id))->getField('username');
    }

    /**
     * 获取管理员信息
     * @param $user_id
     * @return mixed
     */
    public function getUser($user_id){
        return $this->where(array('id'=>$user_id))->find();
    }

    /**
     * 删除管理员操作
     * @param $user_id int
     * @return int  删除条数
     */
    public function deleteUser($user_id){
        M('role_user')->where(array('user_id'=>$user_id))->delete();
        return $this->delete($user_id);
    }
}
?>