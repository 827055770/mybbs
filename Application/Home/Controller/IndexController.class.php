<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        //获取分区信息
        $parts = M('bbs_part')->select();
        //把qid当做下标qname当做名
        $parts = array_column($parts,null,'pid');

        //获取版块信息
        $cates = M('bbs_cate')->select();
        
        //获取用户信息
        $users = M('bbs_user')->select();
        $this->assign('users',$users);
        
        //把板块信息追加到分区信息中
        foreach($cates as $cate)
        {
            $parts[ $cate['pid']]['sub'][]=$cate;
        }

        /*echo '<pre>';
        print_r($parts);*/


        $this->assign('parts',$parts);
        $this->display();//View/index/index.hmtl
    }
}