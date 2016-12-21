<?php
namespace Home\Biz;

/**
 *
 * @author Xuyafei
 *        
 */
class AuthBiz {
	
	
	public static function queryDomainByPara($domain_name, $domain_url,$domain_id) {
		$con = array();
		if(!empty($domain_name)){
			array_push($con, "domain_name ='$domain_name'");
		}
		if(!empty($domain_url)){
			array_push($con, "domain_url = '$domain_url'");
		}
		if(!empty($consigneeName)){
			array_push($con, "domain_id = '$domain_id'");
		}
		
		
		$lst_domains = M ( 'menu_domain','','DB_system')->field("`domain_id`,`domain_name`,`domain_url`,`default_url`")
		       		->where (join(" AND ",$con) )->select ();
		
		 $resultMap = array('datas'=>$lst_domains);
		 
		 return $resultMap;
	}
 
	
	public static function addDomain($domain_name, $domain_url,$default_url){
	  $uuid = CommonBiz::getUUid();
	  $data['domain_id'] =$uuid;
	  $data['domain_name'] = $domain_name;
	  $data['domain_url'] = $domain_url;
	  $data['default_url'] = $default_url;
	  //保存新的invoice
	  M('menu_domain','','DB_system')->data($data)->add();
	}
	
	public static function updateDomain($domain_id,$domain_name, $domain_url,$default_url){
		$domain['domain_id']		= $domain_id;
		$domain['domain_name']	    = $domain_name;
		$domain['domain_url']		= $domain_url;
		$domain['default_url']		= $default_url;
		M('menu_domain','','DB_system')->where("domain_id='$domain_id'")->save($domain);
	}
 
	public static function resetPassword($uname,$originalpass,$newpass,$confirmpass){
		//通过用户名获取密码
		$whaley_user = M('whaley_user','','DB_system')->where("user_name = '$uname'")->find();
		//判断登陆密码是否正确
		$err_msg = '';
		if($whaley_user['password']==md5(C('SystemConfig.encrypt_key').$originalpass)){
			//判断两次输入密码相同
			if($newpass==$confirmpass){
				M('whaley_user','','DB_system')->where("user_name='$uname'")->setField('password',md5(C('SystemConfig.encrypt_key').$newpass));
				$err_msg='密码修改成功';
			}else{
				$err_msg='新密码两次输入不相同';
			}
		}else{
			$err_msg='原密码错误';
		}
		return $err_msg;
	}
	
	public static function queryMenuResourceByPara($menu_id, $menu_name,$menu_domain,$level,$parent_id) {
		$con = array();
		if(!empty($menu_id)){
			array_push($con, "menu_id ='$menu_id'");
		}
		if(!empty($menu_name)){
			array_push($con, "menu_name = '$menu_name'");
		}
		if(!empty($menu_domain)){
			array_push($con, "menu_domain = '$menu_domain'");
		}
		if(!empty($level)){
			array_push($con, "level = '$level'");
		}
		
		if(!empty($parent_id)){
			array_push($con, "parent_id = '$parent_id'");
		}
		
		$lst_domains = M ( 'menu_resource','','DB_system')->field()->where (join(" AND ",$con) )->select ();
		
		foreach($lst_domains as $key => $en){
			$lst_domains[$key]['menu_display_text'] = StringBiz::getMenuDisplayText($en['menu_display']);
			$lst_domains[$key]['level_text'] = StringBiz::getLevelText($en['level']);
		}
		
		$resultMap = array('datas'=>$lst_domains);
		return $resultMap;
	}
	
	public static function updatemenuResourceByMenuId($menu_id, $menu_name,$menu_url,$menu_display){
				$update_menu_resource_en = array(
						'menu_id'	=> $menu_id,
						'menu_name'	=> $menu_name,
						'menu_url'	=> $menu_url,
						'menu_display'	=> $menu_display,
						'update_date'	=> date("y-m-d H:i:s", time())
				);
				M( 'menu_resource','','DB_system')->where("menu_id ='%s'", $menu_id)->save($update_menu_resource_en);
	}
	
	public static function addMenuResource( $menu_name,$menu_domain,$menu_url,$parent_id,$level,$menu_display){
				$parent_name = "";
				$menu_domain_ls = M('menu_domain','','DB_system')->where("domain_url='$menu_domain'")->find();
				$menu_domain_desc = $menu_domain_ls['domain_name'];
				$default_url = $menu_domain_ls['default_url'];
				if($level ==2){
					$menu_resource = M('menu_resource','','DB_system')->where("menu_id='$parent_id'")->find();
					$parent_name = $menu_resource['menu_name'];
				}
				$add_menu_resource_en = array(
						'menu_id'	=> uniqid(),
						'menu_name'	=> $menu_name,
						'menu_domain'	=> $menu_domain,
						'menu_domain_desc'	=> $menu_domain_desc,
						'menu_url'	=> $menu_url,
						'parent_id'	=> $parent_id,
						'parent_name'	=> $parent_name,
						'level'	=> $level,
						'default_url'	=> $default_url,
						'menu_display'	=> $menu_display,
						'create_user'	=> "whaleyinfo.www AuthBiz.addMenuResource",
						'create_date'	=> date("y-m-d H:i:s", time())
				);
				M('menu_resource','','DB_system')->add($add_menu_resource_en);
	}
	

	public static function queryUserByPara($user_name, $user_nick,$user_mobile,$user_id) {
		$con = array();
		if(!empty($user_name)){
			array_push($con, "user_name ='$user_name'");
		}
		if(!empty($user_nick)){
			array_push($con, "user_nick = '$user_nick'");
		}
		if(!empty($user_mobile)){
			array_push($con, "user_mobile = '$user_mobile'");
		}
		if(!empty($user_id)){
			array_push($con, "user_id = '$user_id'");
		}
	
		$lst_users = M ('whaley_user','','DB_system')->field()
		->where (join(" AND ",$con) )->select();
		
		$resultMap = array('datas'=>$lst_users);
		
		return $resultMap;
	}
	
	public static function queryGroupByPara($group_name, $group_id) {
		$con = array();
		if(!empty($group_name)){
			array_push($con, "group_name ='$group_name'");
		}
		if(!empty($group_id)){
			array_push($con, "group_id = '$group_id'");
		}
	
		$lst_groups = M ( 'user_group','','DB_system')->field()
		->where (join(" AND ",$con) )->select ();
	
		$resultMap = array('datas'=>$lst_groups);
			
		return $resultMap;
	}
	
	public static function addGroup($group_name, $group_desc,$menu_relation,$user_relation) {
	 
		$group_id = uniqid();
		
		$group_exist = M('user_group','','DB_system')->where("group_name='$group_name'")->find();
		
		if($group_exist!=null){
			return '该组名已经存在';	
		}
		
		
		$menu_ids=explode(",",$menu_relation);
		$user_ids=explode(",",$user_relation);
		
		/**
		 * 保存user_group表
		 */
		$add_group_en = array(
				'group_id'	=> $group_id,
				'group_name'	=> $group_name,
				'group_desc'	=> $group_desc,
				'create_date'	=> date("y-m-d H:i:s", time())
		);
		M('user_group','','DB_system')->add($add_group_en);
		

		
		foreach ($user_ids as $user_id){
			//保存group user
			$user_en = M('whaley_user','','DB_system')->where("user_id='$user_id'")->find();
				
			$group_user_relation_en = array(
					'relation_id'	=> uniqid(),
					'group_id'      =>$group_id,
					'group_name'	=> $group_name,
					'user_id'       =>$user_en['user_id'],
					'user_name'       =>$user_en['user_name'],
					'create_date'	=> date("y-m-d H:i:s", time())
			);
			M('group_user_relation','','DB_system')->add($group_user_relation_en);
			
			//保存group menu
			foreach ($menu_ids as $menu_id){
				$menu_en = M('menu_resource','','DB_system')->where("menu_id='$menu_id'")->find();
				$group_menu_relation_en = array(
						'relation_id'	=> uniqid(),
						'group_id'      =>$group_id,
						'group_name'	=> $group_name,
						'menu_id'       =>$menu_en['menu_id'],
						'menu_name'       =>$menu_en['menu_name'],
						'menu_domain'       =>$menu_en['menu_domain'],
						'create_date'	=> date("y-m-d H:i:s", time())
				);
				
				M('group_resouce_relation','','DB_system')->add($group_menu_relation_en);
				$user_rule = M('user_rule','','DB_system')->where("menu_id='$menu_id' and user_id='$user_id'")->find();
				if($user_rule==null){
					$user_rule_en = array(
						'rule_id'     =>uniqid(),
					    'user_id'     =>$user_id,
					    'user_name'   =>$user_en['user_name'],
						'menu_id'     =>$menu_id,
						'level'       =>$menu_en['level'],
						'menu_domain' =>$menu_en['menu_domain'],
						'menu_domain_desc'=>$menu_en['menu_domain_desc'],
						'default_url'     =>$menu_en['default_url']
					);
					$user_rule = M('user_rule','','DB_system')->add($user_rule_en);
				}
			}
		}
		return '';
	}
	
	public static function removeUserRelation($relatn_id){
		
		$group_user_relation = M('group_user_relation','','DB_system')->where("relation_id='$relatn_id'")->find();
		
		$user_id = $group_user_relation['user_id'];
		
		$group_id = $group_user_relation['group_id'];
		
		$lst_resouce_relation = M('group_resouce_relation','','DB_system')->where("group_id='$group_id'")->select();
		
		foreach ($lst_resouce_relation as $resouce_relation){
			$menu_id = $resouce_relation['menu_id'];
			M('user_rule','','DB_system')->where("user_id='$user_id' and menu_id = '$menu_id'")->delete();
		}
		/**
		 * 删除用户的话，要把这个用户在这个组的所有权限都删掉，再删掉这张表记录
		 */
		 M('group_user_relation','','DB_system')->where("relation_id='$relatn_id'")->delete();
		
	}
	
	public static function removeMenuRelation($relatn_id){
		
		$group_resource_relation = M('group_resouce_relation','','DB_system')->where("relation_id='$relatn_id'")->find();
		
		$menu_id = $group_resource_relation['menu_id'];
		
		$group_id = $group_resource_relation['group_id'];
		
		$lst_user_relation = M('group_user_relation','','DB_system')->where("group_id='$group_id'")->select();
		
		/**
		 * 删除一个页面的时候，要把这个组里有这个权限的人，都踢掉这个页面的权限
		 */
		foreach ($lst_user_relation as $user_relation){
			$user_id = $user_relation['user_id'];
			M('user_rule','','DB_system')->where("user_id='$user_id' and menu_id = '$menu_id'")->delete();
		}
		M('group_resouce_relation','','DB_system')->where("relation_id='$relatn_id'")->delete();
	}
	
	
	/**
	 * 用户组添加页面
	 * @param unknown $group_id
	 * @param unknown $menu_relation
	 */
	public static function addToGroupRelationMenu($group_id,$menu_relation){
		
		
	  $menu_ids=explode(",",$menu_relation);
	  
	  $user_group_en=M('user_group','','DB_system')->where("group_id = '$group_id'")->find();
		
	 /**
	  * 查询到这个组所有用户
	  * @var unknown
	  */	
	  $lst_group_user_relation = M('group_user_relation','','DB_system')->where("group_id='$group_id'")->select();
		
	  foreach ($menu_ids as $menu_id){
	  	
	  	/**
	  	 * 查询下这个页面，该组是不是已经存在这个页面了
	  	 * @var unknown
	  	 */
		  $group_menu_en_check= M('group_resouce_relation','','DB_system')	->where("group_id = '$group_id' and menu_id ='$menu_id' ")->find();  	
	  	/**
	  	 * 该组不存在该页面的时候，进行处理
	  	 */
		  if($group_menu_en_check==null){
		  	/**
		  	 * 找到这页面，并且配置给这个组和用户
		  	 */
		  	$menu_resource_en=M('menu_resource','','DB_system')->where("menu_id = '$menu_id'")->find();
		  	
		  	foreach ($lst_group_user_relation as $group_user_relation){
		  		
		  		if(!self::checkExistsRule($group_user_relation['user_id'],$menu_id)){
		  		$user_rule_en = array(
		  				'rule_id'     =>uniqid(),
		  				'user_id'     =>$group_user_relation['user_id'],
		  				'user_name'   =>$group_user_relation['user_name'],
		  				'menu_id'     =>$menu_id,
		  				'level'       =>$menu_resource_en['level'],
		  				'menu_domain' =>$menu_resource_en['menu_domain'],
		  				'menu_domain_desc'=>$menu_resource_en['menu_domain_desc'],
		  				'default_url'     =>$menu_resource_en['default_url']
		  		);
		  		$user_rule = M('user_rule','','DB_system')->add($user_rule_en);
		  	  }
		  	}
		  	/**
		  	 * 保存group_resouce_relation
		  	 */
		  	$group_menu_relation_en = array(
		  			'relation_id'	=> uniqid(),
		  			'group_id'      =>$user_group_en['group_id'],
		  			'group_name'	=> $user_group_en['group_name'],
		  			'menu_id'       =>$menu_resource_en['menu_id'],
		  			'menu_name'       =>$menu_resource_en['menu_name'],
		  			'menu_domain'       =>$menu_resource_en['menu_domain'],
		  			'create_date'	=> date("y-m-d H:i:s", time())
		  	);
		  	
		  	M('group_resouce_relation','','DB_system')->add($group_menu_relation_en);
		  	
		  }
	  }
	  
	}
	
	public static function addToGroupRelationUser($group_id,$user_relation){
		$user_ids=explode(",",$user_relation);
		 
		$user_group_en=M('user_group','','DB_system')->where("group_id = '$group_id'")->find();
		
		/**
		 * 查询这个组的所有页面
		 * @var Ambiguous $lst_group_user_relation
		 */
		$lst_group_menu_relation = M('group_resouce_relation','','DB_system')->where("group_id='$group_id'")->select();
		
		foreach ($user_ids as $user_id){
			
			/**
			 * 先查看这个权限组有没有这个用户
			 */
			$group_user_en_check= M('group_user_relation','','DB_system')	->where("group_id = '$group_id' and user_id ='$user_id' ")->find();
				
			$whaley_user_en=M('whaley_user','','DB_system')->where("user_id = '$user_id'")->find();
				
			/**
			 * 如果不存在，将这个组的所有页面权限赋值给这个用户
			 */
			if($group_user_en_check ==null){
				
				
				foreach ($lst_group_menu_relation as $group_menu_relation){
					$menu_id=$group_menu_relation['menu_id'];
					
					if(!self::checkExistsRule($user_id,$menu_id)){
					$menu_resource_en=M('menu_resource','','DB_system')->where("menu_id = '$menu_id'")->find();
					$user_rule_en = array(
							'rule_id'     =>uniqid(),
							'user_id'     =>$user_id,
							'user_name'   =>$whaley_user_en['user_name'],
							'menu_id'     =>$menu_id,
							'level'       =>$menu_resource_en['level'],
							'menu_domain' =>$menu_resource_en['menu_domain'],
							'menu_domain_desc'=>$menu_resource_en['menu_domain_desc'],
							'default_url'     =>$menu_resource_en['default_url']
					);
					 M('user_rule','','DB_system')->add($user_rule_en);
				  }
				}
				
				
				$group_user_relation_en = array(
						'relation_id'	=> uniqid(),
						'group_id'      =>$group_id,
						'group_name'	=> $user_group_en['group_name'],
						'user_id'       =>$whaley_user_en['user_id'],
						'user_name'       =>$whaley_user_en['user_name'],
						'create_date'	=> date("y-m-d H:i:s", time())
				);
				M('group_user_relation','','DB_system')->add($group_user_relation_en);
			}
		}
		
		
	}
	
	public static function checkExistsRule($user_id,$menu_id){
		$user_rule_en = M('user_rule','','DB_system')->where("user_id = '$user_id' and menu_id = '$menu_id'")->find();
		if($user_rule_en !=null){
			return true;
		}
		return false;
	}
	
	public static function addUser($user_name,$user_nick,$user_mobile,$password,$user_address){
		$user_en = M('whaley_user','','DB_system')->where("user_name='$user_name'")->find();
		if($user_en!=null){
			return '用户名已经存在';
		}
		$add_user_en = array(
				'user_id'	=> uniqid(),
				'user_name'	=> $user_name,
				'password'	=> md5(C('SystemConfig.encrypt_key').$password),
				'user_mobile'=>$user_mobile,
				'user_nick'  =>$user_nick,
				'user_address'  =>$user_address,
				'create_date'	=> date("y-m-d H:i:s", time())
		);
		M('whaley_user','','DB_system')->add($add_user_en);
		return '';
	}
	
	public static function updateUser($user_id,$user_name,$user_nick,$user_mobile,$password,$user_address){
		$user_info['user_id']		= $user_id;
		$user_info['user_name']	    = $user_name;
		$user_info['user_nick']		= $user_nick;
		$user_info['user_mobile']		= $user_mobile;
		$user_info['user_address']		= $user_address;
		if(!empty($password)){
			$user_info['password']		= md5(C('SystemConfig.encrypt_key').$password);
		}
		M('whaley_user','','DB_system')->where("user_id='$user_id'")->save($user_info);
	}
	
	public static function userRuleQuery($user_name){
		
		$sql = "SELECT ur.rule_id as rule_id,ur.user_name as user_name,ur.`level` as level ,ur.`menu_domain_desc` as menu_domain_desc,mr.`menu_name` as menu_name FROM user_rule ur LEFT JOIN menu_resource mr ON ur.menu_id = mr.`menu_id` ";
		if(!empty($user_name)){
			$sql = $sql." where ur.user_name = '$user_name'";
		}
		
		$lst_user_rule = M('', '', 'DB_system')->query($sql);
		
		$resultMap = array (
				'datas' => $lst_user_rule
		);
		
		return $resultMap;
	}
	
	public static function deleteUserRule($rule_id){
		M('user_rule','','DB_system')->where("rule_id='$rule_id'")->delete();
	}
	
	public static function addUserRuleSingle($user_name,$lst_menu){
		
		$user_info_en = M('whaley_user','','DB_system')->where("user_name = '$user_name'")->find();
		
		if($user_info_en==null){
			return '用户不存在';
		}
		
		$menu_ids=explode(",",$lst_menu);
		foreach ($menu_ids as $menu_id){
			//不存在的规则，添加进去
			if(!self::checkExistsRule($user_info_en['user_id'],$menu_id)){
				$menu_resource_en=M('menu_resource','','DB_system')->where("menu_id = '$menu_id'")->find();
				
				$user_rule_en = array(
						'rule_id'     =>uniqid(),
						'user_id'     =>$user_info_en['user_id'],
						'user_name'   =>$user_info_en['user_name'],
						'menu_id'     =>$menu_id,
						'level'       =>$menu_resource_en['level'],
						'menu_domain' =>$menu_resource_en['menu_domain'],
						'menu_domain_desc'=>$menu_resource_en['menu_domain_desc'],
						'default_url'     =>$menu_resource_en['default_url']
				);
				$user_rule = M('user_rule','','DB_system')->add($user_rule_en);
			}
		}
		
		return '';
		
	}
	
} 