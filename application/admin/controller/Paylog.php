<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;

class Paylog extends Base
{ 

	
    public function index()
    {
       echo '111';
    }

    //会员支付记录列表
    public function list()
    {
    	$data = input();
        $where = [];
        $page_param['query'] = [];


        $list = Db::name('pay_log')
            ->order('id','asc')
            ->paginate($this->page_size,false,$page_param);
        $count = Db::name('pay_log')->where($where)->count();

        $this->view->assign('count',$count);
        $this->view->assign('data',$list);

      return $this -> fetch('paylog/list');
    }



    //删除
    public function delete()
    {
        $id=intval(input('id'));
        $res = Db::name('pay_log')->delete($id);
        if($res > 0){
            ajaxmsg('删除成功',1);
        }else{
            ajaxmsg('删除失败',0);
        }
    }
    
}
