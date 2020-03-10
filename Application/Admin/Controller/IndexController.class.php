<?php
namespace Admin\Controller;

use Think\Controller;

class IndexController extends CommonController
{
    public function index()
    {
        // 显示 html页面.默认index.html
       $this->display();
    }
}