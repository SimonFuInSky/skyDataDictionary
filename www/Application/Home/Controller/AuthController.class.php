<?php
namespace Home\Controller;
use Think\Controller;
use Home\Biz\StringBiz;
class AuthController extends  LoginBaseController {
	
	public function home(){
		$this->display('auth/home');
	}
	
    public function domain(){
        $this->display('auth/domain');
    }
    
 
    public function adddomain(){
    	$this->display('auth/adddomain');
    }
    
    public function updatePassword(){
    	$user_id = cookie('login_user_id');
    	$this->assign('user_id', $user_id);
    	$this->display('auth/updatePassword');
    }
    
 
    public function menuResource(){
    	$lst_menu_domain = M('menu_domain','','DB_system')->select();
    	$lst_menu_resource = M('menu_resource','','DB_system')->where("level = 1 ")->select();
    	$this->assign('lst_menu_resource', $lst_menu_resource);
    	$this->assign('lst_menu_domain', $lst_menu_domain);
    	$this->display('menuResource/list');
    }
 
    public function domaindetail(){
    	$domain_id  = I('domain_id');
    	$domain_name  = I('domain_name');
    	$domain_url  = I('domain_url');
    	$default_url  = I('default_url');
    	$this->assign('domain_id', $domain_id);
    	$this->assign('domain_name', $domain_name);
    	$this->assign('domain_url', $domain_url);
    	$this->assign('default_url', $default_url);
    	$this->display('auth/domaindetail');
    }
    public function menuresourcedetail(){
    	$menu_id = I('menu_id');
    	$menu_resource = M('menu_resource','','DB_system')->where("menu_id='$menu_id'")->find();
    	$menu_resource['level_text'] = StringBiz::getLevelText($menu_resource['level']);
    	$this->assign('menu_resource', $menu_resource);
    	$this->display('menuResource/menuresourcedetail');
    }
    public function userinfo(){
    	$this->display('auth/userinfo');
    }
    public function addmenuresource(){
    	$lst_menu_domain = M('menu_domain','','DB_system')->select();
    	$this->assign('lst_menu_domain', $lst_menu_domain);
    	$lst_menu_resource = M('menu_resource','','DB_system')->where("level = 1 ")->select();
    	$this->assign('lst_menu_resource', $lst_menu_resource);
    	$this->display('menuResource/addmenuresource');
    }
    
    public function group(){
    	$this->display('auth/group');
    }
    
    public function addgroup(){
    	$this->display('auth/addgroup');
    }
 
    public function groupdetail(){
    	$group_id  = I('group_id');
    	$lst_group_resouce_relation = M('group_resouce_relation','','DB_system')->where("group_id = '$group_id'")->select();
    	$lst_group_user_relation = M('group_user_relation','','DB_system')->where("group_id = '$group_id'")->select();

    	$this->assign('group_id', $group_id);
    	$this->assign('lst_group_resouce_relation', $lst_group_resouce_relation);
    	$this->assign('lst_group_user_relation',$lst_group_user_relation);
    	
    	$this->display('auth/groupdetail');
    }
    
    public function menugrouprelation(){
    	
    	$lst_menu_domain = M('menu_domain','','DB_system')->select();
    	$this->assign('lst_menu_domain', $lst_menu_domain);
    	$lst_menu_resource = M('menu_resource','','DB_system')->where("level = 1 ")->select();
    	$this->assign('lst_menu_resource', $lst_menu_resource);
    	
    	$this->display('auth/menugrouprelation');
    }
    
    public function usergrouprelation(){
    	$this->display('auth/usergrouprelation');
    }
    
    public function adduser(){
    	$this->display('auth/adduser');
    }
    
    public function userdetail(){
    	$user_id  = I('user_id');
    	$this->assign('user_id', $user_id);
    	$user_info = M('whaley_user','','DB_system')->where("user_id = '$user_id'")->find();
    	$this->assign('user_info', $user_info);
    	$this->display('auth/userdetail');
    }
    
    public function userrule(){
    	$this->display('auth/userrule');
    }
    
    
}