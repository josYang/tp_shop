<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->sliders = M('slider')->order('sort ASC')->select();
        $this->display('index');
    }
}