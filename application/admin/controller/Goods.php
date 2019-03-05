<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;

class Goods extends Base
{ 
	public function index()
	{
		echo 'goods-index';
	}

	public function list()
	{
		$data = input();
        $where = [];
        $page_param['query'] = [];


        $field = "a.*,b.cate_name";
        $list = Db::name('goods')->alias('a')
        	->join("cate b","a.cate_id=b.cate_id","LEFT")
            ->where($where)
    		->order('goods_id','asc')
    		->field($field)
    		->paginate($this->page_size,false,$page_param);
    	$count = Db::name('goods')->where($where)->count();

        $this -> assign('count',$count);
 		$this -> assign('data',$list);
 		return $this -> fetch('goods/list');
	}

	public function create()
	{
		$where = [];
		$data_cate = Db::name('cate')
			->where($where)
			->select();
		$this -> view ->assign('data_cate',$data_cate);

		return $this -> view -> fetch('goods/add');
	}


	public function save()
	{
		$data = input();
    	$rule = [
  			'cate_id|分类'=>'require',
  			'goods_name|昵称'=>'require|unique:goods',
  			'goods_price_1|价格1'=>'require',
  			'goods_price_2|价格2'=>'require',
  			'goods_price_3|价格3'=>'require',
  			'goods_en|goods_en'=>'require',
  			'goods_attr_desc|goods_attr_desc'=>'require',
  			'goods_price_coin|goods_price_coin'=>'require',
  			'main_img|图片'=>'require',
    	];
    	$back_rule = [
  			'cate_id.require'=>'请选择分类！',
  			'goods_name.require'=>'昵称不能为空！','goods_name.unique'=>'该商品已经存在！',
  			'goods_price_1.require'=>'请输入价格1！',
  			'goods_price_2.require'=>'请输入价格2！',
  			'goods_price_3.require'=>'请输入价格3！',
  			'goods_en.require'=>'goods_en未上传',
  			'goods_attr_desc.require'=>'goods_attr_desc未上传',
  			'goods_price_coin.require'=>'goods_price_coin未上传',
  			'main_img.require'=>'请上传商品图片！',
    	];
    	$result=$this->validate($data,$rule,$back_rule);
    	

    	if($result===true){
    		$data_save = [
    			'cate_id' => $data['cate_id'],
    			'goods_name' => $data['goods_name'],
    			'goods_price_1' => $data['goods_price_1'],
    			'goods_price_2' => $data['goods_price_2'],
    			'goods_price_3' => $data['goods_price_3'], 
    			'goods_en' => $data['goods_en'], 
    			'goods_attr_desc' => $data['goods_attr_desc'], 
    			'goods_price_coin' => $data['goods_price_coin'], 
    			'main_img' => $data['main_img'], 
    		];

	        $res = Db::name('goods')->insert($data_save);
	        if($res > 0){
        		ajaxmsg('添加成功',1);
	        }else{
	        	ajaxmsg('添加失败',0);
	        }
    	}else{
    		ajaxmsg($result,0);
    	} 

	}


	public function delete()
	{
        $goods_id=intval(input('goods_id'));
        $res = Db::name('goods')->delete($goods_id);

		if($res > 0){
			ajaxmsg('操作成功',1);
		}else{
			ajaxmsg('操作失败',0);
		}
	}



	//资源上传
	public function upload()
	{
    	$files = request()->file();
	    if(!empty($files))
	    {
            //将接收到的图片移动到新路径,新的路径可以定义
            $fInfo = $files['file']-> move(config('set_resource_path').'/admin');

            //将移动后的上传文件保存
            $fname = $fInfo -> getSaveName();

            //检测保存的文件是否存在
            $file_path = config('set_resource_path').'/admin/'.$fname;
            $file_path = str_replace("\\","/",$file_path);//替换路径中的反斜线

            //返回完整路径
            $fname = config('get_resource_path').'/admin/'.$fname;//图片路径拼接完整路径
            $fname = str_replace("\\","/",$fname);//替换路径中的反斜线
            
      
            //返回值
            if(file_exists($file_path)){
	            $arr = [
	                'code'=>0,
	                'msg'=>'图片上传成功',
	                'data'=>[
	                    'src'=>$fname
		                ]
		            ];
	        }else{
	            $arr = [
	                'code'=>1,
	                'msg'=>'图片上传失败',
	                'data'=>[
	                    'src'=>''
	                ]
	            ];
	        }
	        echo json_encode($arr);
		}
	}


}