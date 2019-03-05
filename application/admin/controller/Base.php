<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;


class Base extends Controller
{
    public $page_size = 10;

    protected function _initialize()
    {
   		$this -> is_login();
   		
    }

    public function is_login()
    {

        if(Session('adminflag')==null){
 			return $this -> redirect(url('Login/index'));
        }
    }

   

   
}