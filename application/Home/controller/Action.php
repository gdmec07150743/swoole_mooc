<?php
namespace app\Home\controller;

use app\common\controller\Base;
use app\Home\model\Access;
use think\Session;

class Action extends Base
{
	public function __construct(){
		parent::__construct();
		$this->access=new Access();
	}
	
	public function showAct(){
		if(session('userinfo')){
			$access=$this->access->where('status','1')->select();
			foreach ($access as $k=>$v){
				$access[$k]['ac_url']=json_decode($access[$k]['ac_url']);
				$access[$k]['ac_url']=implode("\n", $access[$k]['ac_url']);
			}
			$this->assign('access',$access);
			return $this->fetch();
		}else{
			return $this->error('请登录',url('home/admin/login'));
		}
	}

	/***权限表添加修改***/
	public function doAct(){
		if(session('userinfo')){
			if($this->request->isPost()){			
					$data=input();
					if (!empty($data)){
						if (!empty($data['ac_url'])){
							$data['ac_url']=explode("\n", $data['ac_url']);
							foreach ($data['ac_url'] as $ki=>$vue){
							      $data['ac_url'][$ki] =  str_replace("\r","",$data['ac_url'][$ki]);
							}
							$data['ac_url']=json_encode($data['ac_url']);
						}
							if (empty($data['ac_id'])){
								$roles=$this->access->where('ac_name' ,$data['ac_name'])-> where('status','1')->find();
								if ($roles){
									return $this->error('权限已经存在',url('home/rolers/dorole'));
								}
								$data['ac_time']=$this->dataNow();
								$result=$this->access->save($data);
										if ($result){
											return $this->success('权限添加成功',url('home/action/showact'));
										}else {
											return $this->error('权限添加失败',url('home/action/doact'));
										}
								}else{
							$data['ac_update']=$this->dataNow();
							$result=$this->access->save($data,['ac_id' => $data['ac_id']]);
							if ($result){
								return $this->success('权限修改成功',url('home/action/showact'));
							}else {
								return $this->error('权限修改失败',url('home/action/doact'));
							}
						}
					}else {
								return $this->error('请输入内容',url('home/action/doact'));
					}
				}else {
					$act_id=input('id');
					if ($act_id){
						$access=$this->access->where('ac_id',$act_id)->find();
						$access['ac_url']=json_decode($access['ac_url']);
						$access['ac_url']=implode("\n", $access['ac_url']);
						$this->assign('access',$access);
					}
					return $this->fetch();
			}
		}else{
			return $this->error('请登录',url('home/admin/login'));
		}
	}

	
		/***权限删除***/
		public function delAct(){
			if(session('userinfo')){
				$ac_id=input('id');
				$access=$this->access->where('ac_id',$ac_id)->find();
				if(empty($access)){
					return $this->error('权限不存在',url('home/action/showact'));
				}else{
					$deltime=$this->dataNow();
					$result=$this->access->save(['status' =>'0','ac_update'=>$deltime],['ac_id' => $ac_id]);
					if ($result){
						return $this->success('权限删除成功',url('home/action/showact'));
					}else {
						return $this->error('权限删除失败',url('home/action/showact'));
					}
				}
			}else{
				return $this->error('请登录',url('home/admin/login'));
			}
		}

}