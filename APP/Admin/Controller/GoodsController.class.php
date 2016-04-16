<?php
/**
 * 商品管理
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/4
 * Time: 13:17
 */
namespace Admin\Controller;
use Admin\Controller;
use Think\Image;
use Think\Page;
use Think\Upload;

class GoodsController extends CommonController{
    private $upload_conf = array(
        'maxSize'       =>  1024*1024*2, //上传的文件大小限制 (0-不做限制)
        'exts'          =>  array('png','gif','jpg','jpeg'), //允许上传的文件后缀
        'rootPath'      =>  './Data/Uploads/', //保存根路径
        'savePath'      =>  './image/goods/', //保存路径
        'saveName'      =>  array('time', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
    );
    public function index(){
        $cat_id     = I('get.cat_id',0,'intval');
        $brand_id   = I('get.brand_id',0,'intval');
        $intro_type = I('get.intro_type',0,'intval');
        $keyword    = I('get.keyword','','htmlentities');
        $where      = array();

        if($cat_id != 0) $where['cat_id'] = $cat_id;

        if($brand_id != 0) $where['brand_id'] = $brand_id;
        switch($intro_type){
            case 0 :
                break;
            case 1 :
                $where['is_best'] = 1;
                break;
            case 2 :
                $where['is_new'] = 1;
                break;
            case 3 :
                $where['is_hot'] = 1;
                break;
        }
        if(!empty($keyword)) $where['keywords'] = array('LIKE','%' . $keyword . '%');
        $db = M('goods');
        $total = $db->count();
        $page = new Page($total,C('SYSTEM.page_size'));
        $field = array('goods_id','goods_name','market_price','is_best','is_new','is_hot','sort_order');

        $this->cat_id = $cat_id;
        $this->brand_id = $brand_id;
        $this->intro_type = $intro_type;
        $this->keyword = $keyword;
        $this->goods_list = $db->field($field)->order('sort_order ASC')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
        $this->show = $page->show();
        $this->getForm();
        $this->display();
    }

    public function add (){
        if(IS_POST){
            $image = $this->upload($_FILES['goods_img']);
            $data = array(
                'goods_name'    => I('post.goods_name','','htmlentities'),
                'cat_id'        => I('post.cat_id',0,'intval'),
                'brand_id'      => I('post.brand_id',0,'intval'),
                'market_price'  => I('post.market_price',0,'intval'),
                'sort_order'    => I('post.sort_order',50,'intval'),
                'keywords'      => I('post.keywords','','htmlentities'),
                'goods_brief'   => I('post.goods_brief','','htmlentities'),
                'is_best'       => I('post.is_best',0,'intval'),
                'is_new'        => I('post.is_new',0,'intval'),
                'is_hot'        => I('post.is_hot',0,'intval'),
                'click_count'   => 1,
                'add_time'      => date('Y-m-d H:i:s'),
                'goods_thumb'   => $image['thumb'],
                'goods_img'     => $image['water'],
                'goods_type'    => I('post.goods_type',0,'intval'),
                'goods_desc'    => I('post.goods_desc','','htmlentities'),
                'goods_shipai'  => I('post.goods_shipai','','htmlentities'),
            );
            $goods_id = M('goods')->add($data);

            if(is_array($_POST['attr_id_list']) && is_array($_POST['attr_value_list']) && is_array($_POST['attr_price_list'])){
                $goods_attr = array();
                for($i=0;$i<count($_POST['attr_id_list']);$i++){
                    $goods_attr[] = array(
                        'goods_id'   => $goods_id,
                        'attr_id'    => $_POST['attr_id_list'][$i],
                        'attr_value' => $_POST['attr_value_list'][$i],
                        'attr_price' => $_POST['attr_price_list'][$i],
                    );
                }
                M('goods_attr')->addAll($goods_attr);
            }

            $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        $this->title = '添加商品';
        $this->submit = '保存添加';
        $this->getForm();
        $this->display('goods_form');
    }

    public function edit(){
        $goods_db = M('goods');

        if(IS_POST){
            $goods_id = I('post.goods_id',0,'intval');
            $image = $this->upload($_FILES['goods_img']);
            $where = 'goods_id=' . $goods_id;
            $data = array(
                'goods_id' => $goods_id,
                'goods_name' => I('post.goods_name','','htmlentities'),
                'cat_id' => I('post.cat_id',0,'intval'),
                'brand_id' => I('post.brand_id',0,'intval'),
                'market_price' => I('post.market_price',0,'intval'),
                'sort_order' => I('post.sort_order',50,'intval'),
                'keywords' => I('post.keywords','','htmlentities'),
                'goods_brief' => I('post.goods_brief','','htmlentities'),
                'is_best' => I('post.is_best',0,'intval'),
                'is_new' => I('post.is_new',0,'intval'),
                'is_hot' => I('post.is_hot',0,'intval'),
                'goods_type' => I('post.goods_type',0,'intval'),
                'goods_desc' => I('post.goods_desc','','htmlentities'),
                'goods_shipai' => I('post.goods_shipai','','htmlentities'),
            );
            if(!empty($image['water']) && !empty($image['thumb'])){
                $data['goods_img'] = $image['water'];
                $data['goods_thumb'] = $image['thumb'];
                $goods_img = ROOT_PATH.$goods_db->where($where)->getField('goods_img');
                $goods_thumb = ROOT_PATH.$goods_db->where($where)->getField('goods_thumb');
                if(is_file($goods_img)) unlink($goods_img);
                if(is_file($goods_thumb)) unlink($goods_thumb);
            }
            $goods_db->save($data);
            if(is_array($_POST['attr_id_list']) && is_array($_POST['attr_value_list']) && is_array($_POST['attr_price_list'])){
                $attr_db = M('goods_attr');
                $attr_db->where($where)->delete();
                $goods_attr = array();
                for($i=0;$i<count($_POST['attr_id_list']);$i++){
                    $goods_attr[] = array(
                        'goods_id'      => $goods_id,
                        'attr_id'       => $_POST['attr_id_list'][$i],
                        'attr_value'    => $_POST['attr_value_list'][$i],
                        'attr_price'    => $_POST['attr_price_list'][$i],
                    );
                }
                $attr_db->addAll($goods_attr);
            }
            $this->success('修改成功',U(CONTROLLER_NAME.'/index'));
            return;
        }

        $this->goods = $goods_db->field('add_time,click_count,goods_thumb',true)->find(I('get.gid',0,'intval'));

        $this->title    = '修改商品';
        $this->submit   = '保存修改';

        $this->getForm();
        $this->display('goods_form');
    }

    public function delete(){
        $goods_id = I('get.gid',0,'intval');
        if($goods_id > 0){
            $goods_db   = M('goods');
            $where      = 'goods_id=' . $goods_id;
            $goods_img  = ROOT_PATH.$goods_db->where($where)->getField('goods_img');
            $goods_thumb = ROOT_PATH.$goods_db->where($where)->getField('goods_thumb');

            if(is_file($goods_img)) unlink($goods_img);
            if(is_file($goods_thumb)) unlink($goods_thumb);

            $goods_db->delete($goods_id);
            M('goods_attr')->where($where)->delete();
            M('goods_gallery')->where($where)->delete();
            $this->success('删除成功',U(CONTROLLER_NAME.'/index'));
        }else{
            $this->error('操作有误');
        }
    }

    public function getGoodsAttr(){
        if(IS_AJAX){
            $json = array();
            $goods_id = I('post.goods_id',0,'intval');
            $cat_id   = I('post.cat_id',0,'intval');
            $attr = $this->get_attr_list($goods_id,$cat_id);
            $html = '<tr><th colspan="2" style="text-align: center">商品属性</th></tr>';
            $spec = 0;

            foreach ($attr AS $key => $val){
                $html .= "<tr><th style='width: 40%'>";

                if ($val['attr_type'] == 1 || $val['attr_type'] == 2) {
                    $html .= ($spec != $val['attr_id']) ?
                        "<a href='javascript:;' onclick='addSpec(this)'>[+]</a>" :
                        "<a href='javascript:;' onclick='removeSpec(this)'>[-]</a>";
                    $spec = $val['attr_id'];
                }

                $html .= "$val[attr_name]</th><td><input type='hidden' name='attr_id_list[]' value='$val[attr_id]' />";

                if ($val['attr_input_type'] == 0) {
                    $html .= '<input name="attr_value_list[]" type="text" value="' .htmlspecialchars($val['attr_value']). '" size="40" /> ';
                } elseif ($val['attr_input_type'] == 2) {
                    $html .= '<textarea name="attr_value_list[]" rows="3" cols="40">' .htmlspecialchars($val['attr_value']). '</textarea>';
                } else {
                    $html .= '<select name="attr_value_list[]">';
                    $html .= '<option value="">请选择...</option>';

                    $attr_values = explode(",", $val['attr_values']);

                    foreach ($attr_values AS $opt) {
                        $opt    = trim(htmlspecialchars($opt));

                        $html   .= ($val['attr_value'] != $opt) ?
                            '<option value="' . $opt . '">' . $opt . '</option>' :
                            '<option value="' . $opt . '" selected="selected">' . $opt . '</option>';
                    }

                    $html .= '</select> ';
                }

                $html .= ($val['attr_type'] == 1 || $val['attr_type'] == 2) ?
                    '属性价格： <input type="text" name="attr_price_list[]" value="' . $val['attr_price'] . '" size="5" maxlength="10" />' :
                    ' <input type="hidden" name="attr_price_list[]" value="0" />';
                $html .= '</td></tr>';
            }

            $json['error'] = $cat_id==0 ? true : false;
            $json['message'] = $cat_id==0 ? '警告：类型传值有误！' : '';
            $json['content'] = $html;
            $this->ajaxReturn($json);
        }
    }

    private function get_attr_list($goods_id,$cat_id){
        $attr_list = M()->table(C('DB_PREFIX').'attribute a')
        ->field('a.attr_id, a.attr_name, a.attr_input_type, a.attr_type, a.attr_values, v.attr_value, v.attr_price')
        ->join('LEFT JOIN '.C('DB_PREFIX').'goods_attr v ON(v.`attr_id`=a.`attr_id` AND v.`goods_id`=' . $goods_id . ')')
        ->where('a.cat_id='.$cat_id.' OR a.cat_id=0')
        ->order('a.sort_order, a.attr_type, a.attr_id, v.attr_price, v.goods_attr_id')
        ->select();
        return $attr_list;
    }

    private function upload($file){
        if($file['error']==0){
            $upload = new Upload($this->upload_conf);
            $info = $upload->uploadOne($file);
            $goods_img = $this->upload_conf['rootPath'].substr($info['savepath'],2).$info['savename'];
            $water = dirname($goods_img).'/water_'.$info['savename'];
            $thumb = dirname($goods_img).'/thumb_'.$info['savename'];
            $image = new Image();
            $image->open($goods_img)->water(C('SYSTEM.water'),Image::IMAGE_WATER_SOUTHEAST)->save($water);
            $image->open($water)->thumb(C('SYSTEM.goods_thumb_width'),C('SYSTEM.goods_thumb_height'),Image::IMAGE_THUMB_FIXED)->save($thumb);
            if(is_file($goods_img)){
                unlink($goods_img);
            }
            return array(
                'water' => substr($water,1),
                'thumb' => substr($thumb,1),
            );
        }else{
            return array(
                'water' => '',
                'thumb' => '',
            );
        }
    }

    private function getForm(){
        import('Class.Category',APP_PATH);
        $this->types = M('goods_type')->field(array('cat_id','cat_name'))->select();
        $this->brands = M('brand')->field(array('brand_id','brand_name'))->select();
        $categorys = M('category')->field(array('cat_id','cat_name','parent_id'))->order('sort_order ASC')->select();
        $this->categorys = \Category::unlimitedForLevel($categorys);
    }
}
?>