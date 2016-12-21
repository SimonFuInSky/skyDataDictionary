<?php
namespace Home\Biz;

class StringBiz{
	/**
	 * 获取menu_display状态文本
	 */
	public static function getMenuDisplayText($source){
		switch($source){
			case 0:
				return '不显示';
				break;
			case 1:
				return '显示';
				break;
			default:
				return strval($source);
				break;
		}
	}
	
	/**
	 * 获取level状态文本
	 */
	public static function getLevelText($source){
		switch($source){
			case 1:
				return '一级菜单';
				break;
			case 2:
				return '二级菜单';
				break;
			default:
				return strval($source);
				break;
		}
	}

	
	/**
	 * 获取promoter_info的promoter_type的状态文本
	 */
	public static function getPromoterTypeText($source){
		switch($source){
			case 1:
				return '个人';
				break;
			case 2:
				return '经销商';
				break;
			default:
				return strval($source);
				break;
		}
	}
	
	
	
	/**
	 * 将字符串转化为UTF-8编码
	 */
	public static function convertUTF8($str){
		if(empty($str)) return '';
		return iconv('gb2312', 'utf-8', $str);
	}
	
	
	
	public static function getImageFullUrl($str, $type){
		switch ($type){
			case 1:
				//推广人上传图片
				return C('UrlConfig.promoter_upload_image_url_prifix').$str;
			default:
				return $str;
		}
	}
	
	
	public static function getImageFullUrlList($str_source, $type, $spilt_str = '|'){
		$str_array = explode($spilt_str, $str_source);
		$return_array = array();
		
		foreach($str_array as $str){
			$image_url = StringBiz::getImageFullUrl($str, $type);
			array_push($return_array, $image_url);
		}
		return $return_array;
		
	}
	
	
	/**
	 * 判断字符串是否合法字符串
	 * @param unknown $str
	 */
	public static function isAddress($str){
		$str_len = mb_strlen($str, 'gb2312');
		if($str_len < 5 || $str_len > 50){
			return false;
		}
		
		if(!StringBiz::isExistChinese($str)){
			return false;
		}
		
		return true;
	}
	
	
	/**
	 * 判断字符串是否存在中文
	 * @param unknown $str
	 */
	public static function isExistChinese($str){
		$x = mb_strlen($str,'gb2312');
		$y = strlen($str);
		
		if($x == $y){
			return false;
		}
		else{
			return true;
		}
		
	}
	
	
	/**
	 * 判断字符串是否手机号码
	 * @param unknown $str
	 */
	public static function isCellphone($str){
		if(preg_match("/^((1[3,5,8][0-9])|(14[5,7])|(17[0,1,6,7,8]))\d{8}$/", $str)){
			//验证通过
			return true;
		}else{
			//手机号码格式不对
			return false;
		}
	}
	
	/**
	 * 判断字符串是否固话号码
	 * @param unknown $str
	 */
	public static function isPhone($str){
		if(preg_match("/^([0-9]{3,4}-)?[0-9]{7,8}$/", $str)){
			//验证通过
			return true;
		}else{
			//手机号码格式不对
			return false;
		}
	}
	
	
	// 身份证校验 start
	/**
	 * 是否身份证号码
	 */
	public static function isIdentity($id_card){
		if(strlen($id_card) == 18)
		{
			return StringBiz::idcard_checksum18($id_card);
		}
		elseif((strlen($id_card) == 15))
		{
			$id_card = StringBiz::idcard_15to18($id_card);
			return StringBiz::idcard_checksum18($id_card);
		}
		else
		{
			return false;
		}
	}
	
	public static function idcard_verify_number($idcard_base)
	{
		if(strlen($idcard_base) != 17)
		{
			return false;
		}
		//加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		//校验码对应值
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
		$checksum = 0;
		for ($i = 0; $i < strlen($idcard_base); $i++)
		{
			$checksum += substr($idcard_base, $i, 1) * $factor[$i];
		}
		$mod = $checksum % 11;
		$verify_number = $verify_number_list[$mod];
		return $verify_number;
	}
	// 将15位身份证升级到18位
	public  static function idcard_15to18($idcard){
		if (strlen($idcard) != 15){
			return false;
		}else{
			// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
			if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
				$idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
			}else{
				$idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
			}
		}
		$idcard = $idcard.StringBiz::idcard_verify_number($idcard);
		return $idcard;
	}
	// 18位身份证校验码有效性检查
	public static function idcard_checksum18($idcard){
		if (strlen($idcard) != 18){ return false; }
		$idcard_base = substr($idcard, 0, 17);
		if (StringBiz::idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
			return false;
		}else{
			return true;
		}
	}
	// 身份证校验 end
}