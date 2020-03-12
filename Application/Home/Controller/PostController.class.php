<?php
namespace Home\Controller;

use Think\Controller;

class POstController extends Controller
{
    //发帖
    public function create()
    {
        $cid = empty($_GET['cid']) ? 0 : $_GET['cid'];
        //如果没有登录就跳到登录去
        if(empty($_SESSION['flag']))
        {
            $this->error('请先登录...','/');
        }
        //获取板块信息
        $cates = M('bbs_cate')->getField('cid,cname');
        $this->assign('cid',$cid);
        $this->assign('cates',$cates);

        $this->display();// View/Post/create.html
    }

    public function save()
    {
      $data = $_POST;
      //发帖人
      $data['uid'] = $_SESSION['userInfo']['uid'];
      //创建时间 更新时间
      $data['updated_at']  = $data['created_at'] = time();
      //把这个数据放入数据库
      $row = M('bbs_post')->add( $data );
        if($row)
        {
            $this->success('发帖成功');
        }
        else
        {
            $this->error('帖子发表失败');
        }
    }
    //帖子列表
    public function index()
    {

        $cid = $_GET['cid'];

        //获取数据
        $posts = M('bbs_post')->where(" cid=$cid")->order("created_at desc")->select();
        $users = M('bbs_user')->getfield('uid,uname');

        //遍历显示

        $this->assign('posts',$posts);
        $this->assign('users',$users);
        $this->display();  //view/post/index.html
    }


}