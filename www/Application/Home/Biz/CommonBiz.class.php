<?php
namespace Home\Biz;
class CommonBiz {
	
	public static function getUUid(){
		static $guid = '';
		$uid = uniqid("", true);
		$data = $namespace;
		$data .= $_SERVER['REQUEST_TIME'];
		$data .= $_SERVER['HTTP_USER_AGENT'];
		$data .= $_SERVER['LOCAL_ADDR'];
		$data .= $_SERVER['LOCAL_PORT'];
		$data .= $_SERVER['REMOTE_ADDR'];
		$data .= $_SERVER['REMOTE_PORT'];
		$hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
		$guid =
		substr($hash,  0,  8) .
		'-' .
		substr($hash,  8,  4) .
		'-' .
		substr($hash, 12,  4) .
		'-' .
		substr($hash, 16,  4) .
		'-' .
		substr($hash, 20, 12) ;
		return $guid;
	}
	
	
	public static function buildRoleAuth($lst_rules) {
	
		$menus = array();
	
			
		foreach ($lst_rules as $rule_sigle){
			$menu_id = $rule_sigle['menu_id'];
			$menu = M('menu_resource','','DB_system')->where("menu_id = '%s' and menu_display = 1", $menu_id)->find();
			if(!empty($menu)){
				array_push($menus,$menu);
			}
		}
			
		$lst_menu1_en = array();
			
		foreach($menus as $menu){
			$parent_name = $menu['parent_name'];
			$parent_id = $menu['parent_id'];
	
			$f_lst_menu1_en = array_filter($lst_menu1_en,
					function($item) use ($parent_id){
							
						return $parent_id == $item['parent_id'];
							
					});
	
			if(empty($f_lst_menu1_en)){
				$menu2_en = array(
						'menu_name'	=> $menu['menu_name'],
						'menu_url'	=> "/".$menu['menu_url'],
				);
				$lst_menu2_en = array();
				array_push($lst_menu2_en, $menu2_en);
					
				$new_menu1_en = array(
						'parent_id' => $parent_id,
						'parent_name' => $parent_name,
						'lst_menu2'		=> $lst_menu2_en,
				);
				array_push($lst_menu1_en, $new_menu1_en);
			}
			else{
					
				$menu2_en = array(
						'menu_name'	=> $menu['menu_name'],
						'menu_url'	=> "/".$menu['menu_url'],
				);
					
				$curr_menu1_en 		= each($f_lst_menu1_en);
				$curr_menu1_en_key 	= $curr_menu1_en['key'];
				array_push($lst_menu1_en[$curr_menu1_en_key]['lst_menu2'], $menu2_en);
			}
	
		}
		return $lst_menu1_en;
	
	}
	
	public static function checkRuleAuth($user_id,$local_domain,$request_url) {
	
		$menu = M('menu_resource','','DB_system')->where("menu_domain = '%s' and menu_url='%s' ", $local_domain, $request_url)->find();
	
		$menu_id = $menu['menu_id'];
	
		$user_rule = M('user_rule','','DB_system')->where("user_name = '%s' and menu_id='%s' ", $user_id, $menu_id)->find();
	
		if($user_rule !=null){
			return true;
		}
	
		return false;
	}
	
	public static function getGetParamsStr(){
		$get_str_array = array();
		$all_get_keys = array_keys($_GET);
	
		foreach($all_get_keys as $key){
			if(!is_numeric($key)){
				array_push($get_str_array, $key."=".$_GET[$key]);
			}
		}
		$get_str = implode('&', $get_str_array);
		return $get_str;
	}
	
	public static function getPostParamsStr(){
		$get_str_array = array();
		$all_get_keys = array_keys(I('post.'));
		foreach($all_get_keys as $key){
			if(!is_numeric($key)){
				array_push($get_str_array, $key."=".$_POST[$key]);
			}
		}
		$get_str = implode('&', $get_str_array);
		return $get_str;
	}
	
	
	public static function getParamsStr(){
		$str_array 	= array();
		$get_str 	= CommonBiz::getGetParamsStr();
		$post_str 	= CommonBiz::getPostParamsStr();
		array_push($str_array, $get_str);
		array_push($str_array, $post_str);
	
		$str 		= implode('&', $str_array);
		return trim($str, '&');
	}
	
	public static function saveOperation($userId,$type,$domain,$moudle,$util,$paramsText)
	{
		//写入order_payment
		$user_operation_log_en = array(
				'user_id'		=> $userId,
				'type'	=> $type,
				'domain'=> $domain,
				'moudle'=> $moudle,
				'util'		=> $util,
				'param_text'=>$paramsText,
				'create_user'	=> 'UserOperationBiz',
				'create_date'	=> date("y-m-d H:i:s", time()),
		);
		M('user_operation_log','','DB_system')->add($user_operation_log_en);
	
	}
	
	/**
	 * Description: 计算页码
	 */
	public static function caculateTotalPage($totalCount, $pageSize)
	{
		$count = intval($totalCount / $pageSize);
	
		$countLeft = $totalCount % $pageSize;
	
		if ($countLeft != 0) {
			$count = $count + 1;
		}
		return $count;
	}
	/**
	 * Description: 填充分页返回数据
	 */
	public static function fillPageResult($datas, $totalCount, $pageSize, $pageNo)
	{
		// 当前页记录数
		$currentCount = $datas . $pageSize;
		// 下一页页码
		$nextIndex = $pageNo + 1;
		// 上一页页码 如果是第一页，页码仍然为1
		$preIndex = 1;
		if ($pageNo > 1) {
			$preIndex = $pageNo - 1;
		}
		$totalPage = self::caculateTotalPage($totalCount, $pageSize);
		$resultMap = array(
				'totalCount' => $totalCount,
				'totalPage' => $totalPage,
				'currentPage' => $currentCount,
				'nextIndex' => $nextIndex,
				'preIndex' => $preIndex,
				'index' => $pageNo,
				'datas' => $datas
		);
		return $resultMap;
	}
	/**
	 * Description: 获取配置内的OPTIONS的内容
	 * @version 2016年9月9日 下午4:47:40
	 * @author lin.yujiao@whaley.cn
	 */
	public static function getOptions($configKey , $exclude){
		$options = C('OPTIONS.' . $configKey);
		//数组排他操作
		if(!empty($exclude) && is_array($exclude) && is_array($options)){
			foreach ($exclude as $key=> $value){
				unset($options[$value]);
			}
			//$options = array_diff_key($options, $exclude);
		}
		return $options;
	}
	
	/**
	 * 获取不重复唯一键
	 */
	public static function getNewSeq($type) {
		M ( 'seq', '', 'DB_system' )->execute ( "INSERT INTO `seq` (`name`) VALUES ('$type')" );
		$seq = M ( 'seq', '', 'DB_system' )->max ( 'id' );
		$seq = ( int ) $seq;
		return $seq;
	}
	
	public static function getAllProvince() {
		$lst_en = M ( 'province', '', 'DB_system' )->select();
		return $lst_en;
	}
	public static function getCityListByProvinceId($province_id) {
		$lst_en = M ( 'city', '', 'DB_system' )->where ( "province_id='%s'", $province_id)->select();
		return $lst_en;
	}
	public static function getCityListByOption($province_id,$province_name) {
		if (!empty ($province_id)) {
			$condition_array['province_id'] = array('eq', $province_id);
		} else if (!empty ($province_name)) {
			$condition_array['province_name'] = array('eq', $province_name);
		}else{
			return array();
		}
		$lst_en = M ( 'city', '', 'DB_system' )->where($condition_array)->select();
		return $lst_en;
	}
	public static function getDistrictByCityId($city_id) {
		$lst_en = M ( 'district', '', 'DB_system' )->where ( "city_id='%s'", $city_id )->select ();
		return $lst_en;
	}
	public static function getDistrictByOption($city_id,$city_name) {
		if (!empty ($city_id)) {
			$condition_array['city_id'] = array('eq', $city_id);
		}else if (!empty ($city_name)) {
			$condition_array['city_name'] = array('eq', $city_name);
		}else{
			return array();
		}
		$lst_en = M ( 'district', '', 'DB_system' )->where($condition_array)->select ();
		return $lst_en;
	}
}