<?php
namespace Home\Controller;
use Home\Biz\AdminBiz;
use Think\Controller;
use Home\Biz\CommonBiz;

/**
 * 所有需要登陆后查看的页面继承此Controller
 * @author Simon
 *
 */
class LoginBaseController extends BaseController {
	function _initialize(){
		//验证当前用户是否登陆
		$user_id = cookie('login_user_id');
		if(empty($user_id)){
			//未登录
			redirect(C('AdminConfig.login_page'));
			return;
		}

        if(AdminBiz::verifyUser() == false){
            redirect(C('AdminConfig.error_info_page').'?type=22&msg=身份校验失败');
        }

		$local_domain = C('SystemConfig.current_domain');
		//获取这个用户所有权限页面
		$lst_rules=M('user_rule','','DB_system')->where("user_name = '%s' and level = 2 and menu_domain = '%s'", $user_id, $local_domain)->select();
		$lst_auth = CommonBiz::buildRoleAuth($lst_rules);
		//新增上次跳转选择的菜单ID
		$this->assign('menu_module',I('menu_module'));
		$this->assign('menu_url',I('menu_url'));
		
		$this->assign('user_id',$user_id);
		$this->assign('lst_auth',$lst_auth);
		$this->assign('lst_rules',$lst_rules);
		$request_url =I('server.PATH_INFO');
		/**
		 * 如果页面直接访问，没有权限，则跳转
		 */
		if(!CommonBiz::checkRuleAuth($user_id, $local_domain, $request_url)){
			redirect(C('AdminConfig.error_page'));
			return ;
		}
		$ServerConfig['AdminConfig'] = C('AdminConfig');
		$ServerConfig['OrderConfig'] = C('OrderConfig');
		$this->assign('ServerConfig', json_encode($ServerConfig));
	}



}