<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Page;

class UserController extends CommonController
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
    //显示表单
    public function create()
    {
        //显示View/User/create.html
        $this->display();
    }
    //接收表单数据，保存到数据库
    public function Save()
    {
        
        $data = $_POST;
        $data['created_at'] = time();  //添加时间

        //密码不能为空空
        if(empty($data['upwd'])||empty($data['reupwd']))
        {
            $this->error('密码不能为空');
        }
        //两次密码不能一致
        if($data['upwd']!==$data['reupwd'])
        {
            $this->error('两次密码不一致');
        }
        //加密密码
        $data['upwd'] = password_hash($data['upwd'],PASSWORD_DEFAULT);//

        //文件上传处理方法

            //把上传文件新的名字复制给data
        $data['uface'] = $this->doUp();


        //生成缩略图

        $this->doSo();


        //打开$filename文件，后续进行处理
        $image = new \Think\Image(\Think\Image::IMAGE_GD,$this->filename); 
        //进行缩放生成新的文件缩略图名字与大小
        $image->thumb(150, 150)->save(getSm($this->filename));
        

            //m方法 add方法  添加保存到数据库
        $row = M('bbs_user')->add($data);
        if($row)
        {
            $this->success('添加成功!');
        }
        else{
            $this->error('添加失败!');
        }
    }

    //查看用户
    public function index()
    {

        //定义一个空数组
        $condition = [];

        //判断有没有性别条件
        if(!empty($_GET['sex']))
        {
            $condition['sex'] = ['eq',"{$_GET['sex']}"];
        }
        


        //判断有没有姓名条件
        if(!empty($_GET['uname']))
        {
            $condition['uname'] = ['like',"%{$_GET['uname']}%"];
        }

        //实例化一个表对象
        $User = M('bbs_user');

        //得到满足条件的总记录数
        $cnt = $User->where($condition)->count();

        //实例化分页类传入总记录和每页显示的记录数
        $Page = new \Think\Page($cnt,3);

        //得到分页显示html代码
        $html_page = $Page->show();

        //获取数据
       $users = $User->where( $condition )->limit($Page->firstRow,$Page->listRows)->select();
       
       /*

        //拆分图片名字 加入sm_  显示缩略图
       $arr = explode('/',$users['uface']);
       $arr[3] = 'sm_'.$arr[3];
       $users['uface'] = implode('/',$arr);
        
       */

        //显示数据
        $this->assign('users',$users);  //声明一下可以在下面那里使用users变量
        //把分页生成的HTML代码 分配给模板
        $this->assign('html_page',$html_page);
        $this->display();   //在View/User/index.html

        
        


        
    }
    //删除指定用户
    public function del()
    {
        $uid = $_GET['uid'];//接收uid
        $row = M('bbs_user')->delete($uid);//删除bbs_user数据库表的$uid

        if($row)
        {
            $this->success('删除用户成功');
        }
        else
        {
            $this->reeor('删除用户失败');
        }
    }


    //在表单显示原有数据
    public function edit()
    {
         //接收uid
         $uid = $_GET['uid'];
         //打开数据查看uid信息
         $user = M('bbs_user')->find($uid);
         /*
            //显示缩略图
         $arr = explode('/',$user['uface']);
            $arr[3] = 'sm_'.$arr[3];
            $user['uface'] = implode('/',$arr);
         */
         
            $this->assign('user',$user);
        
         $this->display();//在view/User/edit.html 中显示$user的内容
    }
    //接收修改后的数据，进行更新
    public function update()
    {
        $uid = $_GET['uid'];

        $data = $_POST;

         //有可能上传新的头像
        if($_FILES['uface']['error'] !==4)
        {
            $data['uface'] = $this->doUp(); 
            $this->doSo();
            
        }


        $row = M('bbs_user')->where("uid=$uid")->save($data);

        if($row)
        {
            $this->success('用户信息修改成功','/index.php?m=admin&c=user&a=index');
        }
        else
        {
            $this->error('用户修改失败');
        }
    }


    //封装文件上传处理
    public function doUp(){
    $config = [
        'maxSize' => 3145728,
        'rootPath' => './',
        'savePath' => 'Public/Uploads/',
        'saveName' => array('uniqid',''),
        'exts' => array('jpg','gif','png','jpeg'),
        'autoSub' => true,
        'subName' => array('date','Ymd'),
];
//实例化上传类
$upload = new \Think\Upload($config);

$info = $upload->upload();

if(!$info)
{
   //上传错误提示错误信息
   $this->error($upload->getError() );
}
   //把上传的文件复制链接一个新的名字    小写
   return  $this->filename = $info['uface']['savepath'].$info['uface']['savename'];

    }



    //封装生成缩略图
    public function doSo(){
    //打开$filename文件，后续进行处理
    $image = new \Think\Image(\Think\Image::IMAGE_GD,$this->filename); 
    //进行缩放生成新的文件缩略图名字与大小
    $image->thumb(150, 150)->save(getSm($this->filename));

    }

}
    