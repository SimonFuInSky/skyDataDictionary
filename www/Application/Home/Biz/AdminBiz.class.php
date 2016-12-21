<?php
namespace Home\Biz;

/**
 *
 * @author Simon
 *        
 */
class AdminBiz {
	
	/**
	 * 用户登陆
	 */
	public static function login($user_id, $password) {
		$user_info = M('whaley_user','','DB_system')->where("user_name='%s'",$user_id)->find();
		if(empty($user_info)){
			$user_info = M('whaley_user','','DB_system')->where("user_mobile='%s'",$user_id)->find();
			if(empty($user_info)){
				return '用户不存在';
			}
		}

		$user_id=$user_info['user_name'];
		$encrypt_password = md5(C('SystemConfig.encrypt_key').$password);
		if($user_info['password'] != $encrypt_password){
			return '用户名密码不匹配';
		}

        $safe_code = md5($user_id.C('SystemConfig.encrypt_key').date("y-m-d", time()));

		$config_domains =C('AdminConfig.login_cookie_domain');
		$domains = explode(",", $config_domains);
		foreach ($domains as $domain){			
			cookie(C('AdminConfig.login_cookie_name'),
                $user_id,
                array('expire'=>C('AdminConfig.login_cookie_expire'),'domain'=>$domain));
            cookie(C('AdminConfig.login_safecode_cookie_name'),
                $safe_code,
                array('expire'=>C('AdminConfig.login_cookie_expire'),'domain'=>$domain));
		}

		return '';
	}
	
	public static function logout($user_id) {
		cookie('login_user_id', $user_id, array('expire'=>-1,'domain'=>C('AdminConfig.login_cookie_domain')));
	}


    /**
     * 验证请求参数中user_id和cookie中的user_id一致性
     */
    public static function verifyUser(){
        $login_user_id = cookie(C('AdminConfig.login_cookie_name'));
        $login_safecode_cookie = cookie(C('AdminConfig.login_safecode_cookie_name'));
        $safe_code = md5($login_user_id.C('SystemConfig.encrypt_key').date("y-m-d", time()));

        if($safe_code != $login_safecode_cookie){
            //安全校验错误


            return false;
        }
        else
        {
            return true;
        }
    }

} 