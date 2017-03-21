<?php
namespace app\Home\controller;

use app\common\controller\Base;
use think\Session;
use phpDocumentor\Reflection\Types\This;

class Admin extends Base
{
    public function index()
    {
        return view('index');
    }
    
    public function upuser($id=''){
        	if ($this->request->isPost()){
    		$data=input();
    		$data['user_password']=$this->md5Pwd($data['user_password']);
    		$res=$this->saveUser($data);
		    		if($res==false){
		    			return $this->error('修改失败','public/home/admin/upuser');
		    		}else{
		    			Session::set('userinfo',$data);
		    			return redirect('public/home/admin/index');
		    		}
   			 }else {
   			 	$userid=input('id');
   			 	$data=$this->getUserById($userid);
   			 	$this->assign('userinfo',$data);
   			 	return $this->view('upuser');
   			 }
    }
    public function login(){
    	if ($this->request->isPost()){
    		$data=input();
    		$data=$this->checkPwd($data['user_name'], $data['user_password']);
    		if ($data){
    			Session::set('userinfo',$data);
    			$this->assign('user_name', $data['user_name']);
    			return redirect('public/home/admin/index');
    		}
    		return redirect('public/home/admin/login');
    	}
    	return redirect('public/home/admin/login');
    }
    
    public function regist(){
    	if ($this->request->isPost()){
    		$data=input();
    		$data['user_password']=$this->md5Pwd($data['user_password']);
    		$data['user_creatdate']=time();
    		$res=$this->saveUser($data);
    		if(is_string($res)){
    			return $this->error($res,'public/home/admin/login');
    		}else{
    			Session::set('userinfo',$data);
    			return redirect('public/home/admin/index');
    		}
    	}
    	return view('regist');
    }
    
    public function logout()
    {
        /**退出登录删除全部session**/
        session(null);
       	return view('login');
    }
}
