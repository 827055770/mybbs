<?php
namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller
{

    //注册页
    public function signUp()
    {
        $this->display();//view//Login/signUp.html    
    }

    //接收注册信息，保存到数据库
    public function register()
    {
        $data = $_POST;

       /* //密码是否为空
        if(empty($data['upwd']||empty($data['reupwd'])))
        {
            die ('密码不能为空');
        }
        die;*/
        



        //密码是否一致





        //密码加密
        $data['upwd'] = password_hash($data['upwd'],PASSWORD_DEFAULT);
        $data['created_at']=time();
        $data['auth'] = 3;

        $row = M('bbs_user')->add($data);
        if($row)
        {
            $this->success('注册成功','/');
        }
        else
        {
            $this->error('注册失败');
        }
    }

    //接收登录信息进行验证
    public function dologin()
    {
        $uname = $_POST['uname'];
        $upwd = $_POST['upwd'];
        $users = M('bbs_user')->where("uname='$uname'")->find();
        //如果你传过来的是个数组   与   你输入的密码和数据表信息里的密码一致就  返回true  
        if($users && password_verify($upwd,$users['upwd']))
        {   //用户信息放到这里面
            $_SESSION['userInfo'] = $users;
            //是否成功放入里面
            $_SESSION['flag'] = true;
            $this->success('登录成功','/');
        }
        else
        {
            $this->error('账号或密码错误');
        }
    }
    public function logout()
    {
        $_SESSION['flag'] = false;
        $this->success('正在退出......','/');
    }
}