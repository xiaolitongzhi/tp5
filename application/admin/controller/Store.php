<?php 

namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;

class Store extends Base
{
	public function index()
	{
		echo 'store-index';
	}


	public function list()
	{

		$data = input();
        $where = [];
        $page_param['query'] = [];

       
    	$list = Db::name('store')
            ->where($where)
    		->order('store_id','asc')
    		->paginate($this->page_size,false,$page_param);
        $count = Db::name('store')->where($where)->count();

        $this -> assign('count',$count);
 		$this -> assign('data',$list);
    	return $this -> fetch('store/store-list');
	}


	public function create()
	{
		
		return $this -> fetch('store/store-add');
	}


	public function save()
	{
		$data = input();

		$rule = [
	  		'store_name|店铺名称'=>'require|unique:store',
	    	];
    	$back_rule = [
  			'store_name.require'=>'店铺名称不能为空！','store_name.unique'=>'该店铺名称已经存在！',
    		];
		$result=$this->validate($data,$rule,$back_rule);
		if($result===true){
			$data_save = array();
			$data_save['store_name'] = $data['store_name'];
			$data_save['x_n'] = $data['x_n'];
			$data_save['y_e'] = $data['y_e'];
			$data_save['is_send'] = $data['is_send'] ?? 0;

			$res = Db::name('store')->insertGetId($data_save);
	        if($res > 0){
        		ajaxmsg('添加成功',1);
	        }else{
	        	ajaxmsg('添加失败',0);
	        }
		}else{
			ajaxmsg($result,0);
		}
	}


	public function edit()
	{
		$store_id = intval(input('store_id'));
       	$data_info = Db::name('store')
       		->where('store_id','=',$store_id)
       		->find();

        $this -> assign('data',$data_info);
        return $this -> fetch('store/store-edit');
	}


	public function update()
	{

		$data = input();
    	$rule = [
    		'store_id|参数'=>'require',
  			'store_name|店铺名称'=>'require|unique:store',
  			'is_send|是否发送'=>'accepted',
    		];
		$back_rule = [
			'store_id.require'=>'参数错误！',
				'store_name.require'=>'店铺名称不能为空！','store_name.unique'=>'该店铺名称已经存在！',
				'is_send.accepted'=>'请求非法！',
			];
  			
    	$result=$this->validate($data,$rule,$back_rule);


    	if($result===true){

    		$info = Db::name('store')
	       		->where('store_id','=',$data['store_id'])
	       		->find();

	       	if ($info['store_name']==$data['store_name']){
                ajaxmsg('未作出更改',0);
            }elseif($info == null){
				ajaxmsg('参数错误',0);
            }else{
            	 $res =Db::name('store')   
            	 	->where('store_id',$data['store_id'])
            	 	->data([
            	 		'store_name' => $data['store_name'],
            	 		'is_send' => $data['is_send'] ?? 0,
            	 	]) 
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


	public function delete()
	{
        $store_id=intval(input('store_id'));
        $res = Db::name('store')->delete($store_id);

		if($res > 0){
			ajaxmsg('操作成功',1);
		}else{
			ajaxmsg('操作失败',0);
		}
	}


}