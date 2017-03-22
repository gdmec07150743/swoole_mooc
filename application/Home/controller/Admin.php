<?php
namespace app\Home\controller;

use app\common\controller\Base;
use think\Session;

class Admin extends Base
{
    public function index()
    {
        return view('index');
    }
    
    public function upuser(){
        	if ($this->request->isPost()){
    		$userid=input('id');
    		$data['user_password']=$this->md5Pwd($data['user_password']);
    		$res=$this->saveUser($data);
		    		if($res==false){
		    			return $this->error('修改失败',url('home/users/upuser',['id' => $userid]));
		    		}else{
		    			Session::set('userinfo',$data);
		    			return redirect(url('home/admin/index'));
		    		}
   			 }else {
   			 	$userid=input('id');
   			 	$data=$this->getUserById($userid);
   			 	$this->assign('userinfo',$data);
   			 	return $this->fetch();
   			 }
    }
    public function login(){
    	if ($this->request->isPost()){
    		$data=input();
    		$data=$this->checkPwd($data['user_name'], $data['user_password']);
    		if ($data){
    			Session::set('userinfo',$data);
    			return redirect(url('home/admin/index'));
    		}
    		return $this->error('用户不存在或者密码错误',url('home/admin/login'));
    	}else {
    		return view('login');
    	}
    }
    
    public function regist(){
    	if ($this->request->isPost()){
    		$data=input();
    		$data['user_password']=$this->md5Pwd($data['user_password']);
    		$data['user_creatdate']=time();
    		$res=$this->saveUser($data);
    		if(is_string($res)){
    			return $this->error($res,url('home/admin/login'));
    		}else{
    			Session::set('userinfo',$res);
    			return redirect(url('home/admin/index'));
    		}
    	}
    	return view('regist');
    }
    
    public function logout()
    {
        /**退出登录删除全部session**/
        session(null);
       	return redirect(url('home/admin/index'));
    }
}
