<?php
namespace app\Home\controller;

use app\common\controller\Base;
use think\Session;

class Users extends Base{
	/***展示用户列表***/
	public function showuser(){
		if(session('userinfo')){
		$user=$this->user->where('user_status','1')->select();
		$this->assign('user',$user);
		return $this->fetch();
		}else{
			return $this->error('请登录',url('home/admin/login'));
		}
	}
	
	/***更新用户信息***/
	public function upuser() {
		if ($this->request->isPost()){
			$data=input();
			if ($data['user_password']==$data['rpassword']&&$data['user_password']!=''){
				$data['user_password']=$this->md5Pwd($data['user_password']);
				$res=$this->saveUser($data);
						if($res==false){
							return $this->error('修改失败',url('home/users/upuser',['id' => $data['user_id']]));
						}else{
							Session::set('userinfo',$data);
							return redirect(url('home/users/showuser'));
						}
				}else {
					return $this->error('密码不一致或者为空',url('home/users/upuser',['id' => $data['user_id']]));
				}
   			 }else {
   			 	$userid=input('id');
   			 	$data=$this->getUserById($userid);
   			 	$this->assign('userinfo',$data);
   			 	return $this->fetch();
   			 }
		}
		
		/***查看用户***/
		public function userinfo() {
			if(session('userinfo')){
				$userid=input('id');
				$data=$this->getUserById($userid);
				$this->assign('userinfo',$data);
				return $this->fetch();
			}else{
				return $this->error('请登录',url('home/admin/login'));
			}
		}
		
		/***删除用户***/
		public function deluser() {
			if(session('userinfo')){
			$userid=input('id');
			$res=$this->user->save(['user_status'=>'0'],['user_id'=>$userid]);
					if( $res=='1'){
						return $this->success('删除成功',url('home/users/showuser'));
					}else{
						return $this->error('删除失败',url('home/users/showuser'));
					}
			}else{
				return $this->error('请登录',url('home/admin/login'));
			}
		}
}