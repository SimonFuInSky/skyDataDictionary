<?php
namespace Home\Controller;
use Think\Controller;
class AdminController extends BaseController {
	
    public function login(){
        $this->display('admin/login');
    }
    
    public function jump(){
    	$user_id = cookie('login_user_id');
    	
    	if(empty($user_id)){
    		//掉线了
    		redirect(C('AdminConfig.login_page'));
    		return;
    	}
    	
    	$lst_user_rule = M('user_rule','','DB_system')->field("distinct(`menu_domain`),`menu_domain_desc`,`default_url`")->where("user_name='%s' and level = 1", $user_id)->select();
    	
    	foreach($lst_user_rule as $key => $user_rule_en){
    		$domain_url	= C('DomainMapping.'.$user_rule_en['menu_domain']);
    		$lst_user_rule[$key]['default_url']	= $domain_url.$user_rule_en['default_url'];
    	}
    	
    	$this->assign('username', $user_id);
    	$this->assign('lst_user_rule', $lst_user_rule);
    	
    	$this->display('admin/jump');
    }
    
    public function errorauth(){
    	
    	$this->display('admin/error');
    }
    
    
    public function errorPage(){
    	$type	= I('type');
    	$msg	= I('msg');
    	
        $is_show_button	= I('is_show_button');
        if(empty($is_show_button)||$is_show_button!='2'){
    	 	$is_show_button=1;
    	}
    	$this->assign('is_show_button',$is_show_button);
    	
    	/**
    	 * 1xx，www
    	 * 2xx，order
    	 * 3xx，fz
    	 */
//     	switch($type){
//     		case 301:
//     			//fz批量支付未有有效的提现数据
    			
//     		default:
//     			break;
//     	}
    	$this->assign('msg', $msg);
    	$this->display('admin/errorPage');
    	
    }
    
    public function infoPage(){
    	
    	$type	= I('type');
    	$is_show_button	= I('is_show_button');
    	 if(empty($is_show_button)||$is_show_button!='2'){
    	 	$is_show_button=1;
    	}
    	$msg	= I('msg');
    	$this->assign('msg', $msg);
    	$this->assign('is_show_button',$is_show_button);
    	$this->display('admin/infoPage');
    }
    
    public function test(){
    	
    	echo 123;
    }
    
    
    
  
}