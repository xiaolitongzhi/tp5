<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;

class Redmember extends Base
{
	public function index()
	{
		echo 'Redmember-index';
	}

	//红包记录列表
	public function list()
	{
        $data = input();

        $where = [];
        $page_param['query'] = [];

       

		$field = "a.*,b.user_name,c.name";
		$list = Db::name('user_red')->alias('a')
		 	->join("user b","a.user_id=b.user_id","LEFT")
		 	->join("red c","a.red_id=c.id","LEFT")
            ->order('id','asc')
            ->field($field)
            ->paginate($this->page_size,false,$page_param);
        $count = Db::name('red')->where($where)->count();

        $this->view->assign('count',$count);
        $this->view->assign('data',$list);
        return $this -> fetch('redmember/list');
	}


	//删除
    public function delete()
    {
        $id=intval(input('id'));
        $res = Db::name('user_red')->delete($id);

        if($res > 0){
            ajaxmsg('操作成功',1);
        }else{
            ajaxmsg('操作失败',0);
        }
    }

}

