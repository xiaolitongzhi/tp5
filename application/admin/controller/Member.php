<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use think\Db;


class Member extends Base
{   

    public function index()
    {
        echo 'admin-member';
    }
    

    // 会员列表
    public function list()
    {
        $data = input();
        $where = [];
        $page_param['query'] = [];


    	$list = Db::name('user')
            ->where($where)
    		->order('user_id','desc')
    		->paginate($this->page_size,false,$page_param);

        $count = Db::name('user')->where($where)->count();

        $this->view->assign('count',$count);
 		$this->view->assign('data',$list);
    	return $this -> fetch('member/member-list');
    }


    //会员添加
    public function create()
    {
    	return $this -> fetch('member/member-add');
    }


    //会员添加
    public function save()
    {
    	
		$data = input();
       
    	$rule = [
  			'user_name|登录名'=>'require|alphaNum|unique:user',
  			'nick_name|昵称'=>'require',
  			'user_mobile|手机号'=>'require|unique:user',
            'password|密码'=>'require',
            'res_password|重复密码'=>'require|confirm:password',
            'sex|性别'=>'require',
            'birthday|生日'=>'require',
    	];
    	$back_rule = [
  			'user_name.require'=>'登录名称不能为空！','user_name.alphaNum'=>'登录名必须是数字和字母组成！','user_name.unique'=>'该登陆账号已被注册！',
  			'nick_name.require'=>'昵称不能为空！',
  			'user_mobile.require'=>'手机号不能为空！','user_mobile.unique'=>'该手机号已被注册！',
            'password.require'=>'密码不能为空！',
            'res_password.require'=>'重复密码不能为空！','res_password.confirm'=>'两次密码不一致！',
            'sex.require'=>'请选择性别！',
            'birthday.require'=>'请选择出生日期！',
    	];
    	$result=$this->validate($data,$rule,$back_rule);

    	if($result===true){
            $data_save = [];
            $data_save['user_name'] = $data['user_name'];
            $data_save['nick_name'] = $data['nick_name'];
            $data_save['user_mobile'] = $data['user_mobile'];
            $data_save['password'] = sha1(trim($data['password']));
            $data_save['sex'] = $data['sex'];
            $data_save['birthday'] = $data['birthday'];
            $data_save['reg_time'] = time();

	        $res = Db::name('user')->insertGetId($data_save);

	        if($res > 0){
        		ajaxmsg('添加成功',1);
	        }else{
	        	ajaxmsg('添加失败',0);
	        }
    	}else{
    		ajaxmsg($result,0);
    	} 
    }


    //会员编辑
    public function edit()
    {
    	$user_id = intval(input('user_id')) ;
       	$data_info = Db::name('user')
       		->where('user_id','=',$user_id)
       		->find();

        $this -> assign('data',$data_info);
        return $this -> fetch('member/member-edit');
    }


    //会员信息更新
    public function update()
    {
		$data = input();

    	$rule = [
  			'user_id|ID'=>'require|number',
  			'nick_name|昵称'=>'require',
  			'user_mobile|手机号'=>'require|unique:user',
            'sex|性别'=>'require',
            'birthday|生日'=>'require',
    	];
    	$back_rule = [
    		'user_id.require'=>'参数错误！',
  			'nick_name.require'=>'昵称不能为空！',
  			'user_mobile.require'=>'手机号不能为空！','user_mobile.unique'=>'该手机号已被注册！',
            'sex.require'=>'请选择性别！',
            'birthday.require'=>'请选择出生日期！',
    	];
    	$result=$this->validate($data,$rule,$back_rule);

    	if($result===true){

    		$info = Db::name('user')
	       		->where('user_id','=',$data['user_id'])
	       		->find();

	       	if ($info['nick_name']==$data['nick_name']&&$info['user_mobile']==$data['user_mobile']
                &&$info['sex']==$data['sex']&&$info['birthday']==$data['birthday']
            ){
                ajaxmsg('未作出更改',0);
            }elseif($info == null){
				ajaxmsg('参数错误',0);
            }else{
            	 $res =Db::name('user')   
            	 	->where('user_id',intval($data['user_id']))
            	 	->data([
            	 		'nick_name' => $data['nick_name'],
            	 		'user_mobile' => $data['user_mobile'],
                        'sex' => $data['sex'],
                        'birthday' => $data['birthday'],]) 
            	 	->update();

                if($res > 0){
                    ajaxmsg('修改成功',1);
                }else{
                    ajaxmsg('修改失败',0);
                }
            }
    	}else{
    		ajaxmsg($result,0);
    	} 
    }

    //修改密码
    public function update_pass()
    {       
        $data = input();
       
        $rule=[
            'user_id|参数'=>'require|number',
            'password|密码'=>'require|min:6',
        ];
        $back_rule = [
            'user_id'=>'参数错误！',
            'password.require'=>'请输入密码！','password.min'=>'密码长度过短！',
        ];
         $result=$this->validate($data,$rule,$back_rule);
        if ($result===true){
            $where = array();
            $where['user_id'] = intval($data['user_id']);
        

            $res =Db::name('user')   
                    ->where($where)
                    ->data([
                        'password' => sha1(trim($data['password'])) ]) 
                    ->update();
                if($res > 0){
                    ajaxmsg('修改成功',1);
                }else{
                    ajaxmsg('修改失败',0);
                }
        }else{
            ajaxmsg($result,0);
        }
    }


    //删除用户
    public function delete()
    {
        $user_id=intval(input('user_id'));
        $res = Db::name('user')->delete($user_id);

    	if($res > 0){
    		ajaxmsg('操作成功',1);
    	}else{
    		ajaxmsg('操作失败',0);
    	}
    }







    //会员签到
    public function member_qiandao()
    {
      
        $data = input();
        
        $rule = [
            'user_id|用户id'=>'require',
        ];
        $back_rule = [     
            'user_id'=>'参数错误！',
        ];
        $result = $this -> validate($data,$rule,$back_rule);
        if($result===true){
            //检查重复签到
            $where = [];
            $where['user_id'] = $data['user_id'];

            $max_data = Db::name('qiandao')->where($where)->max('add_time');
            $a = substr(date('Y-m-d',$max_data),0,10); 
            $b = date('Y-m-d');
            
            if($a==$b){
                ajaxmsg('你今天已经签过到了！',0);
            }else{ 
                $data_save = [];
                $data_save['user_id'] = $data['user_id'];
                $data_save['add_time'] = time();

                $res  = Db::name('qiandao')->insertGetId($data_save);
                if($res > 0){
                    ajaxmsg('签到成功',1);
                }else{
                    ajaxmsg('签到失败',0);
                }
            } 
        }else{
            ajaxmsg($result,0);
        }
    }


    //会员签到记录
    public function qiandao_list()
    {
        $data = input();
        $where = [];
        $page_param['query'] = [];


        $field = "a.*,b.nick_name";
        $list = Db::name('qiandao')->alias('a')
            ->join("user b","a.user_id=b.user_id","LEFT")
            ->where($where)
            ->field($field)
            ->order('add_time','dasc')
            ->paginate($this->page_size,false,$page_param);
        $count = Db::name('qiandao')->where($where)->count();

    

        $this->view->assign('count',$count);
        $this->view->assign('data',$list);
        return $this -> view -> fetch('member/qiandao-list');
    }


    //签到记录删除
    public function qiandao_delete()
    {
        $id=intval(input('id'));
        $res = Db::name('qiandao')->delete($id);
        if($res > 0){
            ajaxmsg('操作成功',1);
        }else{
            ajaxmsg('操作失败',0);
        }
    }



}
