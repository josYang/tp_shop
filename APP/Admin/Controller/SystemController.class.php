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
            $config = array(
                'maxSize' => C('UPLOAD.imageSize'),
                'exts' => C('UPLOAD.exts'),
                'rootPath' => './',
                'savePath' => './Public/',
                'autoSub' => false,
                'saveName' => 'water',
                'replace' => true,
            );
            $upload = new Upload($config);
            $info = $upload->uploadOne($_FILES['water']);
            $data = array(
                'web_title'         => I('post.web_title','','htmlentities'),
                'keyword'           => I('post.keyword','','htmlentities'),
                'description'       => I('post.description','','htmlentities'),
                'page_size'         => I('post.page_size',20,'intval'),
                'goods_thumb_width' => I('post.goods_thumb_width',400,'intval'),
                'goods_thumb_height'=> I('post.goods_thumb_height',400,'intval'),
                'water'             => $info['savepath'].$info['savename'],
            );
            if(create_config_file('system',$data,APP_PATH.MODULE_NAME.'/Conf/')){
                $this->success('生成成功！',U(CONTROLLER_NAME.'/index'));
            }else{
                $this->error('生成文件失败，请检查目录权限');
            }
            return;
        }
        $this->display();
    }
}