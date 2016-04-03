<?php
/**
 * 角色权限分配
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/2
 * Time: 19:36
 */
namespace Admin\Controller;
use Admin\Controller;
class AdminAccessController extends CommonController{
    public function index(){
        $node_model = M('node');
        $access_model = M('access');
        if(IS_POST){
            $rid = I('post.rid',0,'intval');
            $access_model->where(array('role_id'=>$rid))->delete();
            $data = array();
            foreach($_POST['access'] as $v){
                $tmp = explode('_',$v);
                $data[] = array(
                    'role_id'=>$rid,
                    'node_id'=>$tmp[0],
                    'level'=>$tmp[1],
                );
            }
//            dump($_POST);dump($data);die;
            $access_model->addAll($data);
            $this->success('修改成功',U(MODULE_NAME.'/AdminRole/index'));
            return;
        }
        $rid = I('get.id',0,'intval');
        $node = $node_model->order('sort')->field(array('id','name','title','pid'))->select();
        //原有权限
        $access = $access_model->where(array('role_id'=>$rid))->getField('node_id',true);
        $this->node = node_merge($node,$access);
        $this->rid=$rid;
        $this->display();
    }
}
?>