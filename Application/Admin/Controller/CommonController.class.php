<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Page;

//继承这个父类 这个就是爷爷类
//
class CommonController extends Controller
{

public function __construct()
{
    parent::__construct();
    //验证是否成功登录，如果没有登录，就转到登录窗口
    if( empty($_SESSION['flag']))
    {
        $this->error('请您先登录','/index.php?m=admin&c=login&a=login');
    }
}
}