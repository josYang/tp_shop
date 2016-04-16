<?php
/**
 * Created by PhpStorm.
 * User: 18890
 * Date: 2016/4/16
 * Time: 18:01
 */

namespace Admin\Controller;
use Admin\Controller\CommonController;

class ArticleCatController extends CommonController{
    public function index(){
        $this->display();
    }
}