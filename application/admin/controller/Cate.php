<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;

class Cate extends Base
{ 
	public function index()
	{
		echo 'cate-index';
	}


	public function list()
	{
		$data = input();
        $where = [];
        $page_param['query'] = [];

        $list = Db::name('cate')
            ->where($where)
    		->order('cate_id','asc')
    		->paginate($this->page_size,false,$page_param);
        $count = Db::name('cate')->where($where)->count();

        $this -> assign('count',$count);
 		$this -> assign('data',$list);
 		return $this -> fetch('cate/list');
	}


	public function create()
	{
		return $this -> fetch('cate/add');
	}


	public function save()
	{
	

    		$data = input();

	    	$rule = [
	  			'cate_name|分类名'=>'require|unique:cate',
	  			'order|昵称'=>'require',
	    	];
	    	$back_rule = [
	    		
	  			'cate_name.require'=>'分类名称不能为空！','cate_name.unique'=>'该分类已经存在！',
	  			'order.require'=>'昵称不能为空！',
	    	];
	    	$result=$this->validate($data,$rule,$back_rule);

	    	if($result===true){
		        $res = Db::name('cate')->insertGetId($data);
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
		$cate_id = intval( input('cate_id') );
       	$data_info = Db::name('cate')
       		->where('cate_id','=',$cate_id)
       		->find();

        $this -> assign('data',$data_info);
        return $this -> fetch('cate/edit');
	}


	public function update()
	{
	

		$data = input();
    	$rule = [
    		'cate_id|参数'=>'require',
  			'cate_name|分类名'=>'require|unique:cate',
  			'order|昵称'=>'require',
    	];
    	$back_rule = [
    		'cate_id.require'=>'参数错误！',
  			'cate_name.require'=>'分类名称不能为空！','cate_name.unique'=>'该分类已经存在！',
  			'order.require'=>'昵称不能为空！',
    	];
    	$result=$this->validate($data,$rule,$back_rule);


    	if($result===true){

    		$info = Db::name('cate')
	       		->where('cate_id','=',$data['cate_id'])
	       		->find();

	       	if ($info['cate_name']==$data['cate_name']&&$info['order']==$data['order']){
                ajaxmsg('未作出更改',0);
            }elseif($info == null){
				ajaxmsg('参数错误',0);
            }else{
            	 $res =Db::name('cate')   
            	 	->where('cate_id',$data['cate_id'])
            	 	->data([
            	 		'cate_name' => $data['cate_name'],
            	 		'order' => $data['order'],
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

        $cate_id= intval( input('cate_id') );
        $res = Db::name('cate')->delete($cate_id);

		if($res > 0){
			ajaxmsg('操作成功',1);
		}else{
			ajaxmsg('操作失败',0);
		}
	}


}