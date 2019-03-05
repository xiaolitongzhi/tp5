<?php 
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;


class Memberaddress extends Base
{
	public function index()
	{
		echo '111111';
	}


	public function list()
	{
		$data = input();
        $where = [];
        $page_param['query'] = [];


        $where['user_id'] = $data['user_id'];


		$list = Db::name('user_address')
            ->where($where)
    		->order('address_id','desc')
    		->paginate($this->page_size,false,$page_param);

        $count = Db::name('user_address')->where($where)->count();


        $this->view->assign('count',$count);
        $this->view->assign('user_id',$data['user_id']);
 		$this->view->assign('data',$list);
    	return $this -> fetch();
	}


	public function create()
	{
		$user_id = intval(input('user_id'));
		$this -> assign('user_id',$user_id);
		return $this -> fetch();
	}

	public function save()
	{
		$data = input();

    	$rule = [
  			'user_id|用户id'=>'require|number',
  			'address_sheng|省'=>'require',
  			'address_shi|市'=>'require',
  			'address_qu|区'=>'require',
  			'address_xiangxi|详细'=>'require',
  			'user_mobile|收货人手机号'=>'require',
  			'nick_name|收货人姓名'=>'require',
    	];
    	$back_rule = [
    		
  			'user_id.require'=>'参数错误！','user_id.number'=>'参数错误！',
  			'address_sheng.require'=>'省级参数为空！',
  			'address_shi.require'=>'市级参数为空！',
  			'address_qu.require'=>'区级参数为空！',
  			'address_xiangxi.require'=>'地址详细为空！',
  			'user_mobile.require'=>'收货人手机号为空！',
  			'nick_name.require'=>'收货人姓名为空！',
  		
    	];
    	$result=$this->validate($data,$rule,$back_rule);
    	if($result===true){
    		$data_save = [];
    		$data_save['user_id'] = $data['user_id'];
    		$data_save['address_sheng'] = $data['address_sheng'];
    		$data_save['address_shi'] = $data['address_shi'];
    		$data_save['address_qu'] = $data['address_qu'];
    		$data_save['address_xiangxi'] = $data['address_xiangxi'];
    		$data_save['user_mobile'] = $data['user_mobile'];
    		$data_save['nick_name'] = $data['nick_name'];
    		$data_save['is_default'] = $data['is_default'] ?? 0 ;

	        $res = Db::name('user_address')->insertGetId($data_save);
	        if($res > 0){
        		ajaxmsg('地址添加成功',1);
	        }else{
	        	ajaxmsg('地址添加失败',0);
	        }
    	}else{
    		ajaxmsg($result,0);
    	} 
	}

	public function edit()
	{

	}

	public function update()
	{
		
	}


	public function delete()
	{
		$address_id=intval(input('address_id'));
        $res = Db::name('user_address')->delete($address_id);
    	if($res > 0){
    		ajaxmsg('操作成功',1);
    	}else{
    		ajaxmsg('操作失败',0);
    	}
	}

}




