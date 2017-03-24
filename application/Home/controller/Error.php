<?php
namespace app\Home\controller;

use app\common\controller\Base;
use think\Session;

class Error extends Base{
	public function forbidden(){
		return $this->fetch();
	}
}