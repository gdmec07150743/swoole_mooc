<?php
namespace app\Home\controller;

use app\common\controller\Base;
use app\Home\model\Roles;
use app\Home\model\Userole;
use think\Session;

class Admin extends Base
{
	public function __construct(){
		parent::__construct();
		$this->user_roles=new Userole();
		$this->roles=new Roles();
	}
	
    public function index(){
        return view('index');
    }
    
    public function upuser(){
        	if ($this->request->isPost()){
    		$userid=input('id');
    		$data=$this->request->only(['user_id','user_name','user_email','user_type',],'post');
    		//$data['user_password']=$this->md5Pwd($data['user_password']);
	    		if($this->saveUser($data)){
	    			$userinfo=$this->user->where('user_id',$userid)->find();
	    			Session::set('userinfo',$userinfo);
	    			return $this->success('修改成功',url('home/admin/index'));
	    		}else{
	    			return $this->error('修改失败',url('home/admin/upuser',['id' => $userid]));
	    		}
   			 }else {
   			 	$userid=input('id');
   			 	$userinfo=$this->getUserById($userid);
   			 	
   			 	//取出当前用户分配的角色信息，只需要当前用户角色ID字段
   			 	$rolesid=$this->user_roles->where('userid',$userid)->column('rolesid');
   			 	
   			 	$roles=$this->roles->where('status','1')->select();//取出角色信息
   			 	$this->assign('rolesid',$rolesid);
   			 	$this->assign('roles',$roles);
   			 	
   			 	$this->assign('userinfo',$userinfo);
   			 	return $this->fetch();
   			 }
    }
    public function login(){
    	if ($this->request->isPost()){
    		$data=input();
    		$data=$this->checkPwd($data['user_name'], $data['user_password']);
    		if ($data){
    			Session::set('userinfo',$data);
    			return  $this->success('登陆成功',url('home/admin/index'));
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
    		if($this->saveUser($data)){
    			$userinfo=$this->user->where('user_name',$data['user_name']) -> where('status','1')->find();
    			Session::set('userinfo',$userinfo);
    			return $this->success('注册成功',url('home/admin/index'));
    		}else{
    			return $this->error('注册失败，用户存在',url('home/admin/login'));
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
