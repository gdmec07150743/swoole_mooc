<?php
namespace app\common\controller;

use app\Home\model\User;
use \think\Controller;
use \think\Loader;
use \think\Request;
use \think\Session;
use \think\Config;

class Base extends Controller
{
	protected $current_user = null;//当前登录人信息
    public $now_uri;//当前操作
    public $now_url;//请求路径
    public $privilege_urls = [];//保存去的权限链接
    protected $allowAllAction = [//默认允许路径
    		'home/admin/login'
    ];
    public $ignore_url = [
    		'home/admin/index',
    		'home/error/forbidden' ,
    		'home/admin/login',
    		'home/admin/logout',
    		'home/admin/regist',
    		
    ];
    
//最先执行的操作
    protected $beforeActionList = [
    		'first',
    		'second',
    ];
    
    
    public function first(){
    	$this->request = Request::instance();
    	$this->now_uri = $this->request->module() . '/' . $this->request->controller() . '/' . $this->request->action();
    	$this->now_uri = strtolower($this->now_uri);
    	$this->now_url = $this->request->path();
    }

    public function second() {
    	$login_status = $this->checkLoginStatus();
    	if ( !$login_status && !in_array( $this->now_uri,$this->allowAllAction )  ) {
    		return $this->error('没有登录',url('home/admin/login'));
    	}
    	
    	if( !$this->checkPrivilege($this->now_uri ) ){
    		$this->redirect(url('home/error/forbidden'));
    		return false;
    	}
    	return true;
    }
    
    //最先执行的操作
    public function __construct()
    {
        parent::__construct();
        //下一步跑到了$beforeActionList执行完了里面的内容才往下走；
        session('body_id', 0);
        $this->assign('base_uri', url($this->now_uri));
        $this->assign('now_uri', $this->now_uri);
        $this->assign('now_url', $this->now_url);
        $this->bodymap['body_id'] = session('body_id');
        $this->user=new User();
    }

    //验证登录是否有效，返回 true or  false
    protected function checkLoginStatus(){
    	$sessions = $this->request->session('userinfo');
    	if (!empty($sessions)){
    		//获取用户的所有权限
    		$this->current_user=$sessions;//保存用户session
    		return true;
    	}else {
    		return false;
    	}
    }
    
    //取得用户访问权限路径
    protected function getRolePrivilege($uid = 0){
    	if ( !$uid ){
    		$uid=$this->current_user['user_id'];
    	}
    	//取出指定用户的角色
    	$this->userole=Loader::model('Userole');
    	$roles_id_list = $this->userole->where('userid',$uid)->column('rolesid');
    	$privilege_urls= [];
    	if($roles_id_list){//通过角色取出所属权限
    		$this->roleacc=Loader::model('Roleacc');
    		$this->access=Loader::model('Access');
    		$access_id_list_true=array();
    		foreach ($roles_id_list as $jian =>$roles_id){
    			$access_id_list[$jian]=$this->roleacc->where('roleac_roleid',$roles_id)->column('roleac_ac_id');
    			foreach ($access_id_list[$jian] as $vue){
    				array_push($access_id_list_true,$vue);
    			}
    		}
    		$access_id_list_true=array_unique($access_id_list_true);
    		foreach ($access_id_list_true as $access_id){
    			$act[]=$this->access->where('ac_id', $access_id  )->column('ac_url');
    		}
    		if ($act){
    			foreach ($act as $_item){
    				 $access_act[]=json_decode($_item['0'],true);
    				 foreach ($access_act as $urlarr){
    				 	foreach ($urlarr as $url){array_push($this->privilege_urls,$url);}
    				 } 
    			}
    		}
    	}
    	$this->privilege_urls=array_unique($this->privilege_urls);
    	return    $this->privilege_urls;
    }
    
    //检查是否有访问指定链接的权限
    public function checkPrivilege( $url ){
    	//如果是超级管理员 也不需要权限判断
    	if( $this->current_user && $this->current_user['user_type']=='0' ){
    		return true;
    	}
    
    	//有一些页面是不需要进行权限判断的
    	if( in_array( $url,$this->ignore_url ) ){
    		return true;
    	}
    
    	return in_array( $url, $this->getRolePrivilege( ) );
    }
    
    protected function md5Pwd($param) {
    	return $pwd=md5($param);
    }
    
    protected function dataNow(){
    	return date("y-m-d h:i:s" ,time());
    }
    
    protected function getUserById($id){
        return 	$date=$this->user->where('user_id',$id)->where('status','1')->find();
    }
    
    protected function checkPwd($name,$pwd){
    	$pwd = $this->md5Pwd($pwd);
    	$date=$this->user->where('user_name',$name)->find();
    	if ($pwd==$date['user_password']&&$date['status']==1){
    		return $date;
    	}else{
    		return false;
    	}
    }
    
    protected function saveUser($date){
    	if(!empty($date['user_id'])){
    		$user=$this->user->where('user_id',$date['user_id']) ->  where('status','1')->find();
    	}
    	if ($user){
    		$date['user_update']=$this->dataNow();
    		$res=$this->user->save($date,['user_id' => $date['user_id']]);
    	}else {
    		if (!empty($date['user_name'])){
    			$regist=$this->user->where('user_name',$date['user_name']) -> where('status','1')->find();
    			if($regist){
    				$res=false;
    			}else{
    			$date['user_creatdate']=$this->dataNow();
    			$res=$this->user->save($date);
    			}
    		}
    	}
    	return $res;
	}
}
