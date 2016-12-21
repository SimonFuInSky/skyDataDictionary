<?php
namespace Home\Controller;
use Think\Controller;
class InfoController extends Controller {
    public function index(){
    	//验证当前用户是否登陆
    	$user_id = cookie('login_user_id');
    	
    	if(empty($user_id)){
    		//未登录
    		redirect(C('AdminConfig.login_page'));
    		return;
    	}
    	else{
    		redirect(C('AdminConfig.jump_page'));
    		return;
    	}
    }
    
    
    public function invoice(){
    	$this->display('index/invoice');
    }
    
    public function test(){
    	
    }
    
    
}