<?php
namespace app\Home\controller;

use app\common\controller\Base;
use app\Home\model\Roles;
use app\Home\model\Userole;
use think\Session;

class Users extends Base{
	
	public function __construct(){
		parent::__construct();
		$this->user_roles=new Userole();
		$this->roles=new Roles();
	}
	
	/***展示用户列表***/
	public function showuser(){
		if(session('userinfo')){
		$user=$this->user->where('status','1')->select();
		$this->assign('user',$user);
		return $this->fetch();
		}else{
			return $this->error('请登录',url('home/admin/login'));
		}
	}
	
	/***更新用户信息***/
	public function upuser() {
		if ($this->request->isPost()){
			$data=$this->request->only(['user_id','user_name','user_email','user_type',],'post');
			$arr=$this->request->only('roles_id','post');
			if (empty($arr)){$arr['roles_id']=[];}
// 		if ($data['user_password']==$data['rpassword']&&$data['user_password']!=''){
// 		$data['user_password']=$this->md5Pwd($data['user_password']);
			if($this->saveUser($data)){
				/********
				 * 找出删除角色
				 * 假如已有角色集合为A，界面传过来的为B
				 * 角色集合A当中的某个角色不在B角色集合中，就删除他
				 ********/
				//已有角色集合为$user_role_list——A
				//界面传过来的集合$arr['roles_id']——B
				$user_role_list = $this->user_roles->where('userid',$data['user_id'])->select();
				$rel_role_id=array();
				if($user_role_list){
					foreach ($user_role_list as $key => $item){
						$rel_role_id[]=$item['rolesid'];//存储当前角色ID，供下一步使用
						if(!in_array($item['rolesid'],  $arr['roles_id'])){//角色集合A当中的某个角色不在B角色集合中
							$item->delete();//删除数据表中的记录
						}
					}
				}
				/********
				 * 找出添加角色
				 * 假如已有角色集合为A，界面传过来的为B
				 * 角色集合B当中的某个角色不在A角色集合中，就应该添加当前元素
				 ********/	
				//界面传过来的集合$arr['roles_id']——A
				//已有角色集合为$rel_role_id——B
				if($arr['roles_id']){
					foreach ($arr['roles_id'] as  $rolesid ){
						if(!in_array($rolesid, $rel_role_id)){
							$list['rolesid']=$rolesid;
							$list['userid']=$data['user_id'];
							$list['updatetime']=date('Y-m-d H:i:s');
							$this->user_roles->data($list,true)->isUpdate(false)->save();
						}
					}
				}
				return $this->success('修改成功',url('home/users/upuser',['id' => $data['user_id']]));
			}else{
				return $this->error('修改失败',url('home/users/upuser',['id' => $data['user_id']]));
				}
// 			}}else {return $this->error('密码不一致或者为空',url('home/users/upuser',['id' => $data['user_id']]));}
   			 }else {	
   			 	$userid=input('id');
   			 	$userinfo=$this->getUserById($userid);//取出用户信息
   			 	
   			 	//取出当前用户分配的角色信息，只需要当前用户角色ID字段
   			 	$rolesid=$this->user_roles->where('userid',$userid)->column('rolesid');
   			 	
   			 	$roles=$this->roles->where('status','1')->select();//取出角色信息
   			 	$this->assign('rolesid',$rolesid);
   			 	$this->assign('roles',$roles);
   			 	$this->assign('userinfo',$userinfo);
   			 	return $this->fetch();
   			 }
		}
		
		/***查看用户***/
		public function userinfo() {
			if(session('userinfo')){
				$userid=input('id');
				$userinfo=$this->getUserById($userid);//取出用户信息

				//取出当前用户分配的角色信息，只需要当前用户角色ID字段
				$rolesid=$this->user_roles->where('userid',$userid)->column('rolesid');
				
				$roles=$this->roles->where('status','1')->select();//取出角色信息
				$this->assign('rolesid',$rolesid);
				$this->assign('roles',$roles);
				$this->assign('userinfo',$userinfo);
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