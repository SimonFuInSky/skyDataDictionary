<?php
return array(
	//'配置项'=>'配置值'
	'MULTI_MODULE' => false,
	'DEFAULT_MODULE' => 'Home',
	'URL_HTML_SUFFIX'=>'.php',
		
	'URL_PARAMS_BIND_TYPE'  =>  1,
		
	'SHOW_ERROR_MSG'		=>  true,
// 	'TMPL_EXCEPTION_FILE'	=> 'Public/tpl/api_exception.tpl',
		
	//默认数据库whaley_trade
// 	'DB_TYPE'   => 'mysql', // 数据库类型
// 	'DB_HOST'   => '172.16.18.105', // 服务器地址
// 	'DB_NAME'   => 'whaley_trade', // 数据库名
// 	'DB_USER'   => 'fuhaishan', // 用户名
// 	'DB_PWD'    => '123456', // 密码
// 	'DB_PORT'   => 3306, // 端口
// 	'DB_PREFIX' => '', // 数据库表前缀
	
	
	//whaley_system数据库
	'DB_system'	=> 'mysql://fuhaishan:123456@172.16.18.105:3306/whaley_system',
	 'DB_trade'	=> 'mysql://fuhaishan:123456@172.16.18.105:3306/whaley_trade',
	'SystemConfig'	=> array(
			'encrypt_key'			=> 'rlQu78NZpd2OwhQG',
			'current_domain'		=> 'www',
	),
	'AdminConfig'	=> array(
			//登陆的cookie参数设置
			'login_cookie_expire'	=> '7200',
			'login_cookie_domain'	=> '.whaley.cn',
			'login_page'			=> 'http://local.admin.whaley.cn/admin/login',
			'orderinfo_page'		=> 'http://local.order.whaley.cn/order/orderinfo',
			'jump_page'             =>  'http://local.admin.whaley.cn/admin/jump',
			'error_page'            =>  'http://local.admin.whaley.cn/admin/errorauth',
            'error_info_page'       =>  'http://local.admin.whaley.cn/admin/errorPage',
			'system_domain'         =>  'www',

            'login_cookie_name'		=> 'login_user_id',
            'login_safecode_cookie_name'	=> 'admin_safecode_login',
	),		
	'DomainMapping'	=> array(
			'www'		=> 'http://local.admin.whaley.cn/',
			'order'		=> 'http://local.order.whaley.cn/',
			'product'	=> 'http://local.product.whaley.cn/',
			'kf'		=> 'http://local.kf.whaley.cn/',
			'fz'		=> 'http://local.fz.whaley.cn/',
			'wms'		=> 'http://local.wms.whaley.cn/',
	),
	'OrderConfig'	=> array(
		'orderinfo_page'			=>	'http://local.order.whaley.cn/Order/orderinfo',
		'invoicename_page'			=> 'http://local.order.whaley.cn/Invoice/invoicename',
		'payee_register_no'         =>   '12345'
		),
		
	'AUTH_CONFIG' => array(
				'AUTH_ON' => true, //认证开关
				'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
				'AUTH_GROUP' => 'whaley_auth_group', //用户组数据表名
				'AUTH_GROUP_ACCESS' => 'whaley_auth_group_access', //用户组明细表
				'AUTH_RULE' => 'whaley_auth_rule', //权限规则表
				'AUTH_USER' => 'whaley_user'//用户信息表
		),
	
);