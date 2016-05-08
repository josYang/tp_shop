<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/29
 * Time: 21:08
 */

namespace Home\Controller;
use Think\Controller;

class UserController extends Controller
{
    private function checkLogin(){
        $user_info = M('users')->find($_COOKIE['user_id']);
        if(empty($_COOKIE['username']) || empty($_COOKIE['user_id']) || empty($user_info) || $user_info['username'] != $_COOKIE['username']){
            $this->success('账号有误，请重新登陆',U(__CONTROLLER__.'/login'));
            exit;
        }
    }
    public function index(){
        $this->checkLogin();
        $this->display();
    }

    public function profile(){
        $this->checkLogin();
        $db = M('users');
        $user_info = $db->find($_COOKIE['user_id']);
        if(IS_POST){
            if(isset($_POST['act']) && $_POST['act'] == 'edit_password'){
                $data = array(
                    'user_id' => $_COOKIE['user_id']
                );
                $old_password = I('post.old_password','','md5');
                if($old_password == $user_info['password']){
                    if($_POST['new_password'] == $_POST['comfirm_password']){
                        $data['password'] = I('post.new_password','','md5');
                        $db->save($data);
                        $this->success('密码修改成功',U(__ACTION__));
                    }else{
                        $this->error('密码和确认密码不一致');
                    }
                }else{
                    $this->error('原密码不正确');
                }
            }else{
                $data = array(
                    'user_id' => $_COOKIE['user_id'],
                    'phone' => I('post.phone','','htmlentities'),
                    'email' => I('post.email','','htmlentities'),
                    'qq' => I('post.qq','','htmlentities'),
                );
                $db->save($data);
                $this->success('修改成功',U(__ACTION__));
            }
            return;
        }
        $this->user = $user_info;
        $this->display();
    }

    public function address_list(){
        $this->checkLogin();
        $ud_db = M('user_address');
        if(IS_POST){
            $act = I('post.act','','htmlentities');
            if($act == 'add'){
                $data = array(
                    'user_id' => $_COOKIE['user_id'],
                    'consignee' => I('post.consignee','','htmlentities'),
                    'email' => I('post.email','','htmlentities'),
                    'country' => I('post.country',0,'intval'),
                    'province' => I('post.province',0,'intval'),
                    'city' => I('post.city',0,'intval'),
                    'district' => I('post.district',0,'intval'),
                    'address' => I('post.address',0,'htmlentities'),
                    'zipcode' => I('post.zipcode',0,'intval'),
                    'tel' => I('post.tel',0,'htmlentities'),
                    'mobile' => I('post.mobile',0,'intval'),
                    'best_time' => I('post.best_time','','htmlentities'),
                );
                $ud_db->add($data);
                $this->success('添加成功',U(__ACTION__));
            }elseif($act == 'edit'){
                $data = array(
                    'address_id' => I('post.address_id',0,'intval'),
                    'user_id' => $_COOKIE['user_id'],
                    'consignee' => I('post.consignee','','htmlentities'),
                    'email' => I('post.email','','htmlentities'),
                    'country' => I('post.country',0,'intval'),
                    'province' => I('post.province',0,'intval'),
                    'city' => I('post.city',0,'intval'),
                    'district' => I('post.district',0,'intval'),
                    'address' => I('post.address',0,'htmlentities'),
                    'zipcode' => I('post.zipcode',0,'intval'),
                    'tel' => I('post.tel',0,'htmlentities'),
                    'mobile' => I('post.mobile',0,'intval'),
                    'best_time' => I('post.best_time','','htmlentities'),
                );
                $ud_db->save($data);
                $this->success('修改成功',U(__ACTION__));
            }
            return;
        }
        $consignee_list = $ud_db
            ->where('`user_id`='.$_COOKIE['user_id'])
            ->select();
        $r_db = M('region');
        foreach ($consignee_list as &$item) {
            $item['country_list'] = $r_db
                ->field('region_id,region_name')
                ->where(array('parent_id' => 0, 'region_type' => 0))
                ->select();
            $item['province_list'] = $r_db
                ->field('region_id,region_name')
                ->where(array('parent_id' => $item['country'], 'region_type' => 1))
                ->select();
            $item['city_list'] = $r_db
                ->field('region_id,region_name')
                ->where(array('parent_id' => $item['province'], 'region_type' => 2))
                ->select();
            $item['district_list'] = $r_db
                ->field('region_id,region_name')
                ->where(array('parent_id' => $item['city'], 'region_type' => 3))
                ->select();
        }
        $this->countrys = $r_db
            ->field('region_id,region_name')
            ->where(array('parent_id' => 0, 'region_type' => 0))
            ->select();
        $this->provinces = $r_db
            ->field('region_id,region_name')
            ->where(array('parent_id' => 1, 'region_type' => 1))
            ->select();
        $this->consignee_list = $consignee_list;
        $this->display();
    }

    public function delress(){
        $this->checkLogin();
        $rid = I('get.address_id',0,'intval');
        $db = M('user_address');
        $user_id = $db->where('`address_id`='.$rid)->getField('user_id');
        if(!empty($rid) && !empty($user_id) && $user_id == $_COOKIE['user_id']){
            $db->delete($rid);
            $this->success('删除成功',U(__CONTROLLER__.'/address_list'));
        }else{
            $this->error('操作有误');
        }
    }

    public function collection_list(){}

    public function logout(){
        setcookie('username','',time() - 1);
        setcookie('user_id','',time() - 1);
        $this->redirect(U(MODULE_NAME.'/Index/index'));
    }

    public function register(){
        if(IS_POST){
            $data = array(
                'username'  => I('post.username','','htmlentities'),
                'phone'     => I('post.phone','','htmlentities'),
                'qq'        => I('post.qq','','htmlentities'),
                'email'     => I('post.email','','htmlentities'),
                'status'    => 0,
                'add_time'  => date('Y-m-d H:i:s'),
                'last_time' => date('Y-m-d H:i:s'),
                'last_ip'   => get_client_ip(),
            );
            if(!empty($_POST['password']) && !empty($_POST['confirm_password'])){
                if($_POST['password'] == $_POST['confirm_password']){
                    $data['password'] = I('post.password','','md5');
                    $user_id = M('users')->add($data);
                    setcookie('username',$data['username'],time() + 60*60);
                    setcookie('user_id',$user_id,time() + 60*60);
                    $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
                }else{
                    $this->error('密码不一致');
                }
            }else{
                $this->error('密码不得为空');
            }
            return;
        }
        $this->display();
    }

    public function login(){
        if(IS_POST){
            $user_info = M('users')->where('`username`="'. I('post.username','','htmlentities') .'"')->find();
            if($user_info){
                if(I('post.password','','md5') == $user_info['password']){
                    $time = isset($_POST['remember']) && $_POST['remember'] == 1 ? 60*60*24 : 60*60*24*7;
                    setcookie('username',$user_info['username'],time() + $time);
                    setcookie('user_id',$user_info['user_id'],time() + $time);
                    $this->success('登录成功',U(__CONTROLLER__.'/index'));
                }else{
                    $this->error('密码错误！');
                }
            }else{
                $this->error('用户不存在！');
            }
            return;
        }
        $this->display();
    }

    public function region(){
        $json = array();
        $type = I('post.type',0,'intval');
        $json['regions'] = M('region')
            ->field('region_id, region_name')
            ->where(array('region_type'=>$type,'parent_id'=>I('post.parent',0,'intval')))
            ->select();
        $json['type'] = $type;
        $json['target'] = !empty($_POST['target']) ? stripslashes(trim($_POST['target'])) : '';
        $json['target']  = htmlspecialchars($json['target']);
        $this->ajaxReturn($json);
    }

    public function is_registered(){
        $username = I('get.username','','htmlentities');
        $json = array();
        if(M('users')->where('username="'.$username.'"')->getField('username')){
            $json['result'] = false;
        }else{
            $json['result'] = true;
        }
        $this->ajaxReturn($json);
    }

    public function check_email(){
        $email = I('get.email','','htmlentities');
        $json = array();
        if(M('users')->where('email="'.$email.'"')->getField('email')){
            $json['result'] = false;
        }else{
            $json['result'] = true;
        }
        $this->ajaxReturn($json);
    }

    public function check_phone(){
        $phone = I('get.phone','','htmlentities');
        $json = array();
        if(M('users')->where('phone="'.$phone.'"')->getField('phone')){
            $json['result'] = false;
        }else{
            $json['result'] = true;
        }
        $this->ajaxReturn($json);
    }
}