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

    public $now_uri;
    public $now_url;
    public $ret;
    public function __construct()
    {
        $this->request = Request::instance();
        parent::__construct();
        session('body_id', 0);
        $this->now_uri = $this->request->module() . '/' . $this->request->controller() . '/' . $this->request->action();
        $this->now_uri = strtolower($this->now_uri);
        $this->now_url = $this->request->path();
        $this->assign('base_uri', url($this->now_uri));
        $this->assign('now_uri', $this->now_uri);
        $this->assign('now_url', $this->now_url);
        $this->bodymap['body_id'] = session('body_id');
        $this->user=new User();
    }
    
    public function md5Pwd($param) {
    	return $pwd=md5($param);
    }
    
    public function getUserById($id){
        return 	$date=$this->user->where('user_id',$id)->where('user_status','1')->find();
    }
    
    public function checkPwd($name,$pwd){
    	$pwd = $this->md5Pwd($pwd);
    	$date=$this->user->where('user_name',$name)->find();
    	if ($pwd==$date['user_password']&&$date['user_status']==1){
    		return $date;
    	}else{
    		return false;
    	}
    }
    
    public function saveUser($date){
    	$date['user_update']=time();
    	$user=$this->user->where('user_name',$date['user_name'])->find();
    	if ($user==null){
    		$res=$this->user->save($date);
    		if($res){
    			return $date= $this->user->find($date);
    		}else {
    			return false;
    		}
    	}else {
    		return false;
    	}

    }
    
}
