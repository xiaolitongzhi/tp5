<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;

class Order extends Base
{ 
	public function index()
	{
		echo 'order-index';
	}

	public function list()
	{
		$data = input();
        $where = [];
        $page_param['query'] = [];

       
        $field = "a.*,b.user_name";
    	$list = Db::name('order')->alias('a')
    		->join("user b","a.user_id=b.user_id","LEFT")
            ->where($where)
    		->order('id','desc')
    		->field($field)
    		->paginate($this->page_size,false,$page_param);


        $count = Db::name('order')->where($where)->count();

        $this -> assign('count',$count);
 		$this -> assign('data',$list);

		return $this -> view ->fetch('order/order-list');
	}

    public function info()
    {
        $id = intval(input('id'));
        $where = array();
        $where['id'] = $id;
        $info = Db::name('order')->where($where)->find();
        
        $this -> assign('data',$info);
        return $this -> fetch('order/order-info');
    }



    public function delete()
    {
        $id=intval(input('id'));
        $res = Db::name('order')->delete($id);

        if($res > 0){
            ajaxmsg('操作成功',1);
        }else{
            ajaxmsg('操作失败',0);
        }
    }

}