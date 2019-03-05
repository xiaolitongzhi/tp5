<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;

class Youhui extends Base
{ 

	
    public function index()
    {
       
    }

    public function list()
    {
    	$data = input();
        $where = [];
        $page_param['query'] = [];


        $list = Db::name('youhui')
            ->order('id','asc')
            ->paginate($this->page_size,false,$page_param);
        $count = Db::name('youhui')->where($where)->count();

        $this->view->assign('count',$count);
        $this->view->assign('data',$list);
        return $this -> fetch('youhui/youhui-list');
    }


    //添加页面
    public function create()
    {
        return $this -> fetch('youhui/youhui-add');
    }


    //添加数据
    public function save()
    {

        $data = input();
        $rule = [
            'name|标题'=>'require|unique:youhui',
            'note1|备注1'=>'require',
            'note2|备注2'=>'require',
            'note3|备注3'=>'require',
            'note4|备注4'=>'require',
            'price|红包面额'=>'require',
            'order_price|使用门槛'=>'require',
            'send_times|开始时间'=>'require',
            'end_time|结束时间'=>'require',
        ];
        $back_rule = [
            'name.require'=>'标题不能为空！','name.unique'=>'标题已经存储！',
            'note1.require'=>'备注1参数为空！',
            'note2.require'=>'备注2参数为空！',
            'note3.require'=>'备注3参数为空！',
            'note4.require'=>'备注4参数为空！',
            'price.require'=>'请设置红包金额！',
            'order_price.require'=>'请设置红包使用门槛！',
            'send_times.require'=>'请输入红包开始时间！',
            'end_time.require'=>'请输入红包结束时间！',
        ];
        $result=$this->validate($data,$rule,$back_rule);

        if($result===true){
            $data_save = array();
            $data_save['name'] = $data['name'];$data_save['note1'] = $data['note1'];
            $data_save['note2'] = $data['note2'];$data_save['note3'] = $data['note3'];
            $data_save['note4'] = $data['note4'];$data_save['price'] = $data['price'];
            $data_save['order_price'] = $data['order_price'];
            $data_save['send_times'] = strtotime($data['send_times']);
            $data_save['has_time'] = strtotime($data['end_time']);
            $data_save['has_time'] = time();
           
            $res = $userId = Db::name('youhui')->insertGetId($data_save);

            if($res > 0){
                ajaxmsg('添加成功',1);
            }else{
                ajaxmsg('添加失败',0);
            }
        }else{
            ajaxmsg($result,0);
        } 

    }


    //修改
    public function edit()
    {
        $id = intval(input('id'));
        $data_info = Db::name('youhui')
            ->where('id',$id)
            ->find();

        $this -> assign('data',$data_info);
        return $this -> fetch('youhui/youhui-edit');
    }


    //更新
    public function update()
    {
        $data = input();
        $rule = [
            'id|ID'=>'require',
            'name|标题'=>'require|unique:youhui',
            'note1|备注1'=>'require',
            'note2|备注2'=>'require',
            'note3|备注3'=>'require',
            'note4|备注4'=>'require',
            'price|红包面额'=>'require',
            'order_price|使用门槛'=>'require',
            'send_times|开始时间'=>'require',
            'end_time|结束时间'=>'require',
        ];
        $back_rule = [
            'id.require'=>'id参数错误！',
            'name.require'=>'标题不能为空！','name.unique'=>'标题已经存储！',
            'note1.require'=>'备注1参数为空！',
            'note2.require'=>'备注2参数为空！',
            'note3.require'=>'备注3参数为空！',
            'note4.require'=>'备注4参数为空！',
            'price.require'=>'请设置红包金额！',
            'order_price.require'=>'请设置红包使用门槛！',
            'send_times.require'=>'请输入红包开始时间！',
            'end_time.require'=>'请输入红包结束时间！',
        ];
        $result=$this->validate($data,$rule,$back_rule);

        if($result===true){
            $data['send_times'] = strtotime($data['send_times']);
            $data['end_time'] = strtotime($data['end_time']);

            $info = Db::name('youhui')
                ->where('id','=',$data['id'])
                ->find();

            if ($info['name']==$data['name']&&$info['note1']==$data['note1']&&
                $info['note2']==$data['note2']&&$info['note3']==$data['note3']&&
                $info['note4']==$data['note4']&&$info['price']==$data['price']&&
                $info['order_price']==$data['order_price']&&$info['send_times']==$data['send_times']&&
                $info['end_time']==$data['end_time']
            ){
                ajaxmsg('未作出更改',0);
            }elseif($info == null){
                ajaxmsg('参数错误',0);
            }else{
                 $res =Db::name('youhui')   
                    ->where('id',$data['id'])
                    ->data([
                        'name' => $data['name'],
                        'note1' => $data['note1'],
                        'note2' => $data['note2'],
                        'note3' => $data['note3'],
                        'note4' => $data['note4'],
                        'price' => $data['price'],
                        'order_price' => $data['order_price'],
                        'has_time' => time(),
                        'send_times' => $data['send_times'],
                        'end_time' => $data['end_time'],
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


    //删除
    public function delete()
    {
        $id=intval(input('id'));
        $res = Db::name('youhui')->delete($id);
        if($res > 0){
            ajaxmsg('操作成功',1);
        }else{
            ajaxmsg('操作失败',0);
        }
    }


    
}
