<?php
namespace app\admin\controller;
use app\admin\controller\Base;


class Index extends Base
{ 

	
    public function index()
    {
        return $this -> view ->  fetch('index/index');
    }

      public function welcome()
    {
        return $this->view->fetch('index/welcome');
    }
    
}
