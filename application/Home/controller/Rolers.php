<?php
namespace app\Home\controller;

use think\Loader;
use app\common\controller\Base;
use app\Home\model\Roles;
use think\Session;

class Rolers extends Base
{
	public function __construct(){
		parent::__construct();
		$this->roleaccess=Loader::model('roleacc');
		$this->userole=Loader::model('userole');
		$this->access=Loader::model('access');
		$this->roles=new Roles();
	}
	
	/***角色表展示***/
	public function showRole(){
			if(session('userinfo')){
			$roles=$this->roles->where('status','1')->select();
			$this->assign('roles',$roles);
			return $this->fetch();
			}else{
				return $this->error('请登录',url('home/admin/login'));
			}
	}
	
	/***角色表添加修改***/
	public function doRole(){
		if(session('userinfo')){
			if($this->request->isPost()){
				$data=input();
				
				if (empty($data['roles_id'])){
					
					$roles=$this->roles->where('roles_name' ,$data['roles_name'])-> where('status','1')->find();
					if ($roles){
						return $this->error('角色已经存在',url('home/rolers/dorole'));
					}
					$data['roles_creatime']=$this->dataNow();
					$result=$this->roles->save($data);
					if ($result){
						return $this->success('角色添加成功',url('home/rolers/showrole'));
					}else {
						return $this->error('角色添加失败',url('home/rolers/dorole'));
					}
					
				}elseif(empty($data['roles_name'])){
					return $this->error('角色名不能为空,',url('home/rolers/dorole'));
					
				}else{
					$role_find_name=$this->roles->where('roles_name' ,$data['roles_name'])-> where('status','1')->find();
					if ($role_find_name){
						return $this->error('角色修改失败,角色名重复',url('home/rolers/dorole',['id' => $data['roles_id']]));
					}
					$data['roles_update']=$this->dataNow();
					$result=$this->roles->save($data,['roles_id' => $data['roles_id']]);
					if ($result){
						return $this->success('角色修改成功',url('home/rolers/showrole'));
					}else {
						return $this->error('角色修改失败',url('home/rolers/dorole'));
					}
				}
			}else {
					$roles_id=input('id');
					if($roles_id){
						$roles=$this->roles->where('roles_id',$roles_id)->find();
						$this->assign('roles',$roles);
					}
					return $this->fetch();
				}
		}else{
			return $this->error('请登录',url('home/admin/login'));
		}
	}
	
	
	//角色与权限绑定
	public function bindAct(){
		if(session('userinfo')){
			if($this->request->isGet()){
				$rolesid=input('id');
				if(empty($rolesid)){
					return $this->error('非法操作',url('home/rolers/showrole'));
				}
				$roles=$this -> roles -> where('roles_id',input('id')) -> find();
				if (!$roles){
					return $this->error('非法操作',url('home/rolers/showrole'));
				}
				//取出所有权限
				$access_list=$this->access->where('status','1')->select();
				//取出所有已分配的权限
				$access_id=$this->roleaccess->where('roleac_roleid',$rolesid)->column('roleac_ac_id');

				$this->assign('access_id',$access_id);
				$this->assign('access',$access_list);
				$this->assign('roles',$roles);

				return $this->fetch();
			}
			$roles_id = $this->request->only('roles_id','post');
			$access = $this->request->only('ac_id','post');
			if (empty($access)){$access=[];}
			foreach ($access as $kis=> $val){
				$access=$val;
			}
			if(empty($roles_id)){$roles_id=[];}
			foreach ($roles_id as $val){
				$roles_id=$val;
			}
			if (empty($roles_id)  ) {
				return $this->error('指定角色不存在',url('home/rolers/showrole'));
			}
			$roles = $this -> roles -> where('roles_id',$roles_id) -> find();
			if( empty($roles) ){
				return $this->error('指定角色不存在',url('home/rolers/showrole'));
			}
			
			/**
			* 找出删除的权限
			* 假如已有的权限集合为A，界面传过来的权限集合为B
			* A中的某个权限不在B中，array_diff()补集计算
			*/
			//取出所有已分配的权限
			$roles_access_id=$this->roleaccess->where('roleac_roleid',$roles_id)->column('roleac_ac_id');
			//array_diff计算要删除的权限
			$deleted_access_ids=array_diff($roles_access_id,$access);
			if($deleted_access_ids){
				foreach ($deleted_access_ids as $k ){
					$this->roleaccess
					->where('roleac_roleid',$roles_id)
					->where('roleac_ac_id',$k)
					->delete();
				}
			}
			/**
			 * 找出添加的权限
			 * 假如已有的权限集合为A，界面传过来的权限集合为B
			 * B中的某个权限不在A中，array_diff()补集计算
			 */
			$add_access_ids=array_diff($access,$roles_access_id);
			if($add_access_ids){
				foreach ($add_access_ids as $_item){
					$list['roleac_roleid']=$roles_id;
					$list['roleac_ac_id']=$_item;
					$list['update']=$this->dataNow();
					$this->roleaccess->data($list,true)->isUpdate(false)->save();
					}
				}
			return $this->success('权限绑定成功',url('home/rolers/showrole'));
		}else{
			return $this->error('请登录',url('home/admin/login'));

		}
	}
	
	
	/***角色删除***/
	public function delRole(){
		if(session('userinfo')){
			$roles_id=input('id');
			$roles=$this->roles->where('roles_id',$roles_id)->find();
			if(empty($roles)){
				return $this->error('角色不存在',url('home/rolers/showrole'));
			}else{
				$deltime=$this->dataNow();
				$result=$this->roles->save(['status' =>'0','roles_update'=>$deltime],['roles_id' => $roles_id]);
				if ($result){
					return $this->success('角色删除成功',url('home/rolers/showrole'));
				}else {
					return $this->error('角色删除失败',url('home/rolers/showrole'));
				}
			}
		}else{
			return $this->error('请登录',url('home/admin/login'));
		}
	}
	
	
	
}