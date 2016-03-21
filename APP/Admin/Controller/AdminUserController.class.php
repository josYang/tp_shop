<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/3/21
 * Time: 20:27
 */
namespace Admin\Controller;
use Admin\Controller;
class AdminUserController extends CommonController{
    public function index(){
        $this->admin = D('AdminUser')->gitUserList();
        $this->display();
    }
    public function delete(){
        
    }
}
?>