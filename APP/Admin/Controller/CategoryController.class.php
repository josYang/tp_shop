<?php
/**
 * 商品分类
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/3/21
 * Time: 20:03
 */
namespace Admin\Controller;
use Admin\Controller;
class CategoryController extends CommonController{
    /**
     * 分类列表
     */
    public function index(){
        import('Class.Category',APP_PATH);
        $g_db = M('goods');
        $cart = M('category')->order('sort_order ASC')->select();
        foreach ($cart as &$item) {
            $item['goods_count'] = $g_db->where('cat_id='.$item['cat_id'])->count();
        }
        $this->cates = \Category::unlimitedForLevel($cart,'-&nbsp;');
        $this->display();
    }

    /**
     * 添加分类
     */
    public function add(){
        if(IS_POST){
            $data = array(
                'cat_name'      => I('post.cat_name','','htmlentities'),
                'parent_id'     => I('post.parent_id',0,'intval'),
                'sort_order'    => I('post.sort_order',50,'intval'),
                'filter_attr'   => implode(',',$_POST['filter_attr']),
                'status'        => I('post.status',50,'intval'),
                'is_show'       => I('post.is_show',0,'intval'),
                'grade'         => I('post.grade',50,'intval'),
            );
            M('category')->add($data);
            $this->success('添加成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        import('Class.Category',APP_PATH);
        $this->title            = '添加分类';
        $this->submit           = '保存添加';
        $attr_event             = A('Attribute','Event');
        $arr                    = M()->field('a.attr_id, a.cat_id, a.attr_name')->table(array(C('DB_PREFIX').'attribute'=>'a',C('DB_PREFIX').'goods_type'=>'gt'))->where(array('a.cat_id=gt.cat_id'))->select();
        $types                  = M('goods_type')->field('cat_id, cat_name')->select();
        $this->attr_list        = $attr_event->get_attr_list($arr);
        $this->goods_type_list  = $attr_event->goods_type_list(0,$types);
        $parent_cate            = M('category')->order('sort_order ASC')->select();
        $this->parent_cate      = \Category::unlimitedForLevel($parent_cate);
        $this->getfrom();
        $this->display('category_from');
    }

    public function edit(){
        $cid = I('get.id',0,'intval');
        if(IS_POST){
            $data = array(
                'cat_id'        => I('post.cat_id',0,'intval'),
                'cat_name'      => I('post.cat_name','','htmlentities'),
                'parent_id'     => I('post.parent_id',0,'intval'),
                'filter_attr'   => implode(',',$_POST['filter_attr']),
                'status'        => I('post.status',0,'intval'),
                'is_show'       => I('post.is_show',0,'intval'),
                'grade'         => I('post.grade',0,'intval'),
                'sort_order'    => I('post.sort_order',50,'intval'),
            );
            M('category')->save($data);
            $this->success('修改成功',U(CONTROLLER_NAME.'/index'));
            return;
        }
        if($cid > 0){
            import('Class.Category',APP_PATH);
            $attr_event         = A('Attribute','Event');
            $cat_info           = M('category')->find($cid);
            $arr                = M()->field('a.attr_id, a.cat_id, a.attr_name')->table(array(C('DB_PREFIX').'attribute'=>'a',C('DB_PREFIX').'goods_type'=>'gt'))->where(array('a.cat_id=gt.cat_id'))->select();
            $types              = M('goods_type')->field('cat_id, cat_name')->select();
            $attr_list          = $attr_event->get_attr_list($arr);
            $filter_attr_list   = array();

            if ($cat_info['filter_attr']){
                $iteration = 1;
                $filter_attr = explode(",", $cat_info['filter_attr']);  //把多个筛选属性放到数组中

                foreach ($filter_attr AS $k => $v) {
                    $attr_cat_id = M('attribute')->where(array('attr_id'=>intval($v)))->field('cat_id')->getField('cat_id','');
                    $filter_attr_list[$k]['goods_type_list'] = $attr_event->goods_type_list($attr_cat_id,$types);  //取得每个属性的商品类型
                    $filter_attr_list[$k]['filter_attr'] = $v;
                    $filter_attr_list[$k]['iteration'] = $iteration;
                    $attr_option = array();

                    foreach ($attr_list[$attr_cat_id] as $val)
                    {
                        $attr_option[key($val)] = current ($val);
                    }

                    $filter_attr_list[$k]['option'] = $attr_option;
                    $iteration++;
                }

                $this->filter_attr_list = $filter_attr_list;
            } else {
                $attr_cat_id = 0;
                $this->goods_type_list  = $attr_event->goods_type_list(0,$types);
            }

            $this->title        = '修改分类';
            $this->submit       = '保存修改';
            $this->attr_cat_id  = $attr_cat_id;
            $this->attr_list    = $attr_list;
            $this->cate         = $cat_info;
            $parent_cate        = M('category')->where(array('cat_id'=>array('neq',$cid)))->order('sort_order ASC')->select();
            $this->parent_cate  = \Category::unlimitedForLevel($parent_cate);
            $this->getfrom();
            $this->display('category_from');
        }else{
            $this->error('操作有误');
        }
    }

    public function delete(){
        $cid = I('get.id',0,'intval');
        if($cid > 0){
            M('category')->delete($cid);
            $this->success('删除成功',U(CONTROLLER_NAME.'/index'));
        }else{
            $this->error('操作有误');
        }
    }

    private function getfrom(){
        $this->status_list = array(
            1 => '禁用',
            2 => '启用',
        );
    }
}
?>