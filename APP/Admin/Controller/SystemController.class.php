<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/17
 * Time: 21:51
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Upload;

class SystemController extends CommonController
{
    public function index(){
        if(IS_POST){
            $data = array(
                'web_title'         => I('post.web_title','','htmlentities'),
                'keyword'           => I('post.keyword','','htmlentities'),
                'description'       => I('post.description','','htmlentities'),
                'instructions'      => I('post.instructions','','htmlentities'),
                'hot_search'        => I('post.hot_search','','htmlentities'),
                'copyright'         => I('post.copyright','','htmlentities'),
                'icp'               => I('post.icp','','htmlentities'),
                'qq'                => I('post.qq','','htmlentities'),
                'phone'             => I('post.phone','','htmlentities'),
                'page_size'         => I('post.page_size',20,'intval'),
                'goods_thumb_width' => I('post.goods_thumb_width',400,'intval'),
                'goods_thumb_height'=> I('post.goods_thumb_height',400,'intval'),
//                'water'             => $info ? $info['savepath'].$info['savename'] : C('SYSTEM.water'),
            );

            $config = array(
                'maxSize' => C('UPLOAD.imageSize'),
                'exts' => C('UPLOAD.exts'),
                'rootPath' => './',
                'savePath' => './Public/',
                'autoSub' => false,
                'replace' => true,
            );
            $upload = new Upload($config);
            foreach ($_FILES as $name => $image) {
                $upload->saveName = $name;
                $info = $upload->uploadOne($_FILES[$name]);
                $data[$name] = $info['savepath'].$info['savename'];
            }
            if(empty($data['water'])){
                $data['water'] = C('SYSTEM.water');
            }
            if(empty($data['logo'])){
                $data['logo'] = C('SYSTEM.logo');
            }
            if(create_config_file('system',$data,CONF_PATH)){
                $this->success('修改成功！',U(CONTROLLER_NAME.'/index'));
            }else{
                $this->error('修改失败，请检查目录权限');
            }
            return;
        }
        $this->display();
    }
}