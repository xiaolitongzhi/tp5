<?php
namespace app\admin\controller;
use app\admin\controller\Base;


class Peizhi extends Base
{ 

	//配置
    public function index()
    {
     	return $this -> fetch();
    }

    public function update()
    {
    	$data = input();
    	$rule = [
    		'config_name|config_name'=>'require',
  			'config_value|config_value'=>'require',
  			'config_note|config_note'=>'require',
    		];
		$back_rule = [
			'config_name.require'=>'config_name为空！',
			'config_value.require'=>'cconfig_value为空！',
			'config_note.accepted'=>'config_note为空！',
			];
  			
    	$result=$this->validate($data,$rule,$back_rule);

    	if($result===true){
    		$where = array();
    		$where['id'] = intval($data['id']);

    		$info = Db::name('config')
	       		->where($where)
	       		->find();

	       	if ($info['config_name']==$data['config_name']&&$info['config_value']==$data['config_value']&&
	       		$info['config_note']==$data['config_note']){
                ajaxmsg('未作出更改',0);
            }elseif($info == null){
				ajaxmsg('参数错误',0);
            }else{
            	 $res =Db::name('config')   
            	 	->where($where)
            	 	->data([
            	 		'config_name' => $data['config_name'],'config_value' => $data['config_value'],
            	 		'config_note' => $data['config_note'],
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

    
}
