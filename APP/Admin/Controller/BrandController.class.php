<?php
/**
 * 商品品牌控制器
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/11
 * Time: 21:14
 */
namespace Admin\Controller;
use Admin\Controller;
use Think\Image;
use Think\Page;
use Think\Upload;

class BrandController extends CommonController{
    private $uploadconf = array(
        'maxSize'       =>  1024*500, //上传的文件大小限制 (0-不做限制)
        'exts'          =>  array('jpg','png','gif','jpeg'), //允许上传的文件后缀
        'rootPath'      =>  './Data/Uploads/', //保存根路径
        'savePath'      =>  './image/brand/', //保存路径
        'saveName'      =>  array('time', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
    );
    public function index(){
        $db = M('brand');
        $count = $db->count();
        $page = new Page($count,C('SYSTEM.page_size'));
        $this->show = $page->show();
        $this->brands = $db->field(array('brand_id','brand_name','brand_logo','site_url','sort_order'))->order('sort_order ASC')->limit($page->firstRow.','.$page->listRows)->select();
        $this->display();
    }
    public function add(){
        if(IS_POST){
            $thumb = $this->upload($_FILES['brand_logo']);
            $data = array(
                'brand_name'    => I('post.brand_name','','htmlentities'),
                'brand_logo'    => $thumb,
                'brand_desc'    => I('post.brand_desc','','htmlentities'),
                'site_url'      => I('post.site_url','','htmlentities'),
                'sort_order'    => I('post.sort_order',50,'intval'),
            );
            M('brand')->add($data);
            $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->title    = '添加品牌';
        $this->submit   = '保存添加';
        $this->display('brand_form');
    }

    public function edit(){
        $db = M('brand');
        if(IS_POST){
            $brand_id = I('post.brand_id',0,'intval');
            $thumb = $this->upload($_FILES['brand_logo']);
            $data = array(
                'brand_id'      => $brand_id,
                'brand_name'    => I('post.brand_name','','htmlentities'),
                'brand_desc'    => I('post.brand_desc','','htmlentities'),
                'site_url'      => I('post.site_url','','htmlentities'),
                'sort_order'    => I('post.sort_order',50,'intval'),
            );
            if(!empty($thumb)){
                $data['brand_logo'] = $thumb;
                $brand_logo = ROOT_PATH.$db->where('brand_id=' . $brand_id)->getField('brand_logo');
                if(is_file($brand_logo)) unlink($brand_logo);
            }
            $db->save($data);
            $this->success('修改成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->brand = $db->find(I('get.brand_id',0,'intval'));
        $this->title = '修改品牌';
        $this->submit = '保存修改';
        $this->display('brand_form');
    }

    public function delete(){
        $brand_id = I('get.brand_id',0,'intval');
        if($brand_id > 0){
            $db = M('brand');
            $brand_logo = ROOT_PATH.$db->where('brand_id=' . $brand_id)->getField('brand_logo');
            if(is_file($brand_logo)){
                unlink($brand_logo);
            }
            $db->delete($brand_id);
            $this->success('删除成功',U(CONTROLLER_NAME.'/index'));
        }else{
            $this->error('操作有误');
        }
    }

    /**
     * 上传图片，添加水印，返回文件路径
     * @param $file
     * @return string
     */
    private function upload($file){
        if($file['error'] === 0){
            $upload = new Upload($this->uploadconf);
            $info = $upload->uploadOne($file);
            $logo = $this->uploadconf['rootPath'].substr($info['savepath'],2).$info['savename'];
            $thumb = dirname($logo).'/thumb_'.$info['savename'];
            $image = new Image();
            $image->open($logo)->thumb(80,40,Image::IMAGE_THUMB_FIXED)->save($thumb);
            if(is_file($logo)){
                unlink($logo);
            }
            return substr($thumb,1);
        }else{
            return '';
        }
    }
}
?>