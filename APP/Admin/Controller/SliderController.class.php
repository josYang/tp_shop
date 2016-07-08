<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/26
 * Time: 20:36
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Image;
use Think\Upload;

class SliderController extends CommonController
{
    public function index(){
        $db = M('slider');
        if(IS_POST){
            $config = array(
                'maxSize'  => C('UPLOAD.imageSize'),
                'exts'     => C('UPLOAD.exts'),
                'rootPath' => C('UPLOAD.rootPath'),
                'savePath' => C('UPLOAD.SliderSavePath'),
                'saveName' => C('UPLOAD.saveName'),
            );

            $upload = new Upload($config);
            $info   = $upload->upload($_FILES);

            foreach($_POST['set_title'] as $key=>$val){
                $db->where('`id`='.$key)->setField('title',$val);
            }

            foreach($_POST['set_link'] as $key=>$val){
                $db->where('`id`='.$key)->setField('link',$val);
            }

            foreach($_POST['set_sort'] as $key=>$val){
                $db->where('`id`='.$key)->setField('sort',$val);
            }

            foreach($_POST['set_status'] as $key=>$val){
                $db->where('`id`='.$key)->setField('status',$val);
            }

            if($info !== false){
                $image  = new Image();
                $data   = array();

                for($i = 0; $i < count($info); $i++){
                    $img_original   = $config['rootPath'].substr($info[$i]['savepath'],2).$info[$i]['savename'];
                    $img_crop      = dirname($img_original).'/crop_'.$info[$i]['savename'];
                    $image->open($img_original)->crop(962,450)->save($img_crop);
                    $data[] = array(
                        'img_url'   => substr($img_crop,1),
                        'title' => $_POST['title'][$i],
                        'sort' => $_POST['sort'][$i],
                        'link' => $_POST['link'][$i],
                        'status' => $_POST['status'][$i],
                    );
                    if(is_file($img_original)) unlink($img_original);
                }
                $db->addAll($data);
            }

            $this->success('修改成功',U(__ACTION__));
            return;
        }
        $this->gallerys = $db->order('sort ASC')->select();
        $this->display();
    }
    public function delete(){
        $img_id = I('post.img_id',0,'intval');
        $json = array();
        if($img_id > 0){
            $db = M('slider');
            $img = $db->where('`id`='.$img_id)->getField('img_url');
            $img = ROOT_PATH.$img;
            if(is_file($img)) unlink($img);
            $db->delete($img_id);
            $json['error'] = false;
            $json['message'] = '删除成功';
        }else{
            $json['error'] = true;
            $json['message'] = '图片ID不存在';
        }
        $this->ajaxReturn($json);
    }
}