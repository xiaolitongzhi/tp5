<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class Login extends Controller
{

   public function index()
   {
   		return $this->fetch('login/login');
   }

   //登录验证
   public function check()
   {
      $request = Request::instance();
   		$data = input();
   
   		$rule = [
	  			'user_name|账号'=>'require|length:6,16',
	  			'password|密码'=>'require|alphaDash|length:6,16',
          // 'captcha|验证码'=>'require|captcha'
        ];
	    $back_rule = [
	  			'user_name.require'=>'账号不能为空！','user_name.length'=>'账号长度为6~16位字符！',
	  			'password.require'=>'密码不能为空！','password.alphaDash'=>'密码格式有误！','password.length'=>'密码格式错误！',
	    ];
	    $result=$this->validate($data,$rule,$back_rule);
	    if($result===true){

            //匹配账号
            $where = [];
            $username = trim($data['user_name']);
            if (preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $username)) {
                $where['user_email'] = array('eq', $username);     // 邮箱登陆
            } elseif (preg_match("/^1\d{10}$/", $username)) {
                $where['user_mobile'] = array('eq', $username);    // 手机号登陆
            } else {
                $where['user_name'] = array('eq', $username);  // 用户名登陆
            }

	    	 $user_info = Db::name('user')
	    	 			->where($where)
	    	 			->find();

              
	    	 if(empty($user_info)){
	    	 	    ajaxmsg('账号不存在',0);
	    	 }elseif($user_info['password']!=sha1($data['password'])){
	    	 	    ajaxmsg('密码错误',0);
	    	 }else{

            //更新用户上线信息
            Db::name('user')   
            ->where('user_id',$user_info['user_id'])
            ->data([
              'last_login' => time(),
              'last_ip' => $request->ip(),
              'token' => md5( $user_info['password'].date('Y-m-d', time())),
              'token_out_time' => strtotime("+1 day"),
              ]) 
            ->update();

	    	 		Session::set("adminflag",true);
	    	 		Session::set("UserName",$user_info['user_name']);
	    	 		Session::set("UserId",$user_info['user_id']);
	    	 		ajaxmsg('登录成功',1);
	    	 }

	    }else{
	    	  ajaxmsg($result,0);
	    }
   }


   //退出登录
   public function leave()
   {
   		  Session::set('adminflag',false);
        Session::delete('adminflag');
        Session::delete('UserName');
        Session::delete('UserId');
        $this->redirect(url('Login/index'));
   }


}