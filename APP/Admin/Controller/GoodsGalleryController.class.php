<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/16
 * Time: 15:03
 */
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Image;
use Think\Upload;

class GoodsGalleryController extends CommonController{
    public function index(){
        $db = M('goods_gallery');
        if(IS_POST){
            $goods_id = I('post.goods_id',0,'intval');
            $config = array(
                'maxSize'  => C('UPLOAD.imageSize'),
                'exts'     => C('UPLOAD.exts'),
                'rootPath' => C('UPLOAD.rootPath'),
                'savePath' => C('UPLOAD.goodsGallerySavePath'),
                'saveName' => C('UPLOAD.saveName'),
            );
            $upload = new Upload($config);
            $info   = $upload->upload($_FILES);

            foreach($_POST['old_img_desc'] as $key=>$val){
                $db->where('img_id='.$key)->setField('img_title',$val);
            }

            if($info !== false){
                $image  = new Image();
                $data   = array();

                for($i = 0; $i < count($info); $i++){
                    $img_original   = $config['rootPath'].substr($info[$i]['savepath'],2).$info[$i]['savename'];
                    $img_water      = dirname($img_original).'/water_'.$info[$i]['savename'];
                    $img_thumb      = dirname($img_original).'/thumb_'.$info[$i]['savename'];
                    $image->open($img_original)->water(C('SYSTEM.water'),Image::IMAGE_WATER_SOUTHEAST)->save($img_water);
                    $image->open($img_water)->thumb(C('SYSTEM.goods_thumb_width'),C('SYSTEM.goods_thumb_height'),Image::IMAGE_THUMB_FIXED)->save($img_thumb);
                    $data[] = array(
                        'goods_id'  => $goods_id,
                        'img_url'   => substr($img_water,1),
                        'img_title' => $_POST['img_desc'][$i],
                        'thumb_url' => substr($img_thumb,1)
                    );
                    if(is_file($img_original)) unlink($img_original);
                }
                $db->addAll($data);
            }

            $this->success('添加成功',U(__ACTION__,array('gid'=>$goods_id)));
            return;
        }
        $goods_id = I('get.gid',0,'intval');
        $this->gallerys = $db->field('goods_id',true)->where('goods_id=' . $goods_id)->select();
        $this->goods_id = $goods_id;
        $this->display();
    }

    public function delete(){
        $img_id = I('post.img_id',0,'intval');
        $json = array();
        if($img_id > 0){
            $db = M('goods_gallery');
            $img = $db->field('img_url,thumb_url')->find($img_id);
            foreach($img as $value){
                $image = ROOT_PATH.$value;
                if(is_file($image)) unlink($image);
            }
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
?>