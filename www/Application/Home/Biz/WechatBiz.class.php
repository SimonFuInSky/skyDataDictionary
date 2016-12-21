<?php
namespace Home\Biz;


class WechatBiz{
	/**
	 * @Fields $success_errcode :返回成功的code
	 */
	private static $success_errcode = 0;
	/**
	 * Description: 获取标签列表
	 * @version 2016年9月22日 下午4:02:21 
	 * @author lin.yujiao@whaley.cn
	 */
	public static function getTagList($access_token){
		$tag_list_url  = "https://qyapi.weixin.qq.com/cgi-bin/tag/list?access_token=$access_token";
		$list_result = json_decode(file_get_contents($tag_list_url), true);
		return $list_result;
	}
	/**
	 * Description: 测试与公司距离
	 * @version 2016年9月23日 下午4:41:32 
	 * @author lin.yujiao@whaley.cn
	 */
	public static function getDistanceForWhaley($user_lat, $user_lng){
		$distance = self::getDistance($user_lat, $user_lng, C('WeChatConfig.meeting_latitude'), C('WeChatConfig.meeting_longitude'));
		return $distance;
	}
	private function getDistance($lat1, $lng1, $lat2, $lng2, $miles = true){
		//圆周率π在角度里为180°,Math.PI/180就为1°
		$pi80 = M_PI / 180;
		//WGS84坐标系 长半轴
		//千米
		//$earth_radius = 6372.797; 
		//米
		$earth_radius = 6378137 ; 
		$lat1 *= $pi80;
		$lat2 *= $pi80;
	/* 	$lng1 *= $pi80;
		$lng2 *= $pi80;  */
		
		$dlat = $lat1 - $lat2;
		$dlng = ($lng1 - $lng2) * $pi80;
		
		$slat = sin($dlat/2);
		$slng = sin($dlng/2);
		$distance =  2 * $earth_radius * asin(sqrt($slat * $slat + cos($lat1)* cos($lat2) * $slng * $slng));
		return $distance;
	}

	/**
	 * Description: 获取标签列表
	 * @version 2016年9月22日 下午4:02:21
	 * @author lin.yujiao@whaley.cn
	 */
	public static function getTagMemberList($access_token,$tag_name){
		//调用接口获取所有标签
		$tag_list_url  = self::getTagList($access_token);
		if($tag_list_url["errcode"] == self::$success_errcode){
			$tag_id = null;
			foreach ($tag_list_url["taglist"] as $index => $object){
				if($object["tagname"] == $tag_name){
					$tag_id = $object["tagid"];
					break;
				}
			}
			//找到所属标签后 进一步获取标签内成员
			if(!empty($tag_id)){
				$tag_member_result = self::getTagMemberTagId($access_token, $tag_id);
				if($tag_member_result["errcode"] == self::$success_errcode){
					return $tag_member_result["userlist"];
				}
			}
		}
		return null;
	}
	/**
	 * Description: 获取某标签下成员
	 * @version 2016年9月22日 下午4:30:56 
	 * @author lin.yujiao@whaley.cn
	 */
	public static function getTagMemberTagId($access_token, $tag_id){
		$tag_member_url  = "https://qyapi.weixin.qq.com/cgi-bin/tag/get?access_token=$access_token&tagid=$tag_id" ;
		$tag_member_result = json_decode(file_get_contents($tag_member_url), true);
		return $tag_member_result;
	}
	
	
	
	/**
	 * Description: 通过CODE与TOKEN获取当前登录的企业用户信息
	 * @version 2016年9月22日 下午3:53:45 
	 * @author lin.yujiao@whaley.cn
	 */
	public static function getUserinfo($access_token, $code){
		$user_url  = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=$access_token&code=$code";
		$user_result = json_decode(file_get_contents($user_url), true);
		return $user_result;
	}
	/**
	 * Description: 获取微信JS 配置信息
	 * @version 2016年9月22日 下午3:53:45 
	 * @author lin.yujiao@whaley.cn
	 */
	public static function getWeichatJsConfig($access_token){
		$config_result = self::get_signpackage($access_token);
		$config_result["jsApiList"] = array( // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	      'openLocation',//这里不知道你用的地理接口是哪个就两个都写上了
	      'getLocation'
	    );
		//需要access_token，这个用来获取jsapi_ticket。
		//获取到后就是签名(sign).
		//签名需要的参数有：jsapi_ticket，noncestr，timestamp及url(当前的url)
		//采用字段名的ASCII码升序，使用URL键值对的格式(key=value&key=value)拼接
		//如：jsapi_ticket=xxx&noncestr=xxx&timestamp=xxx&url=xxx
		//最后对拼接出来的字符串用sha1签名，得到sign。放入config的参数里。
		return $config_result;
	}

	/**
	 * 
	 * Description: 通过open_id和token获取通讯录内的用户信息
	 * @version 2016年9月22日 下午3:57:21 
	 * @author lin.yujiao@whaley.cn
	 */
	public static function getContactinfo($access_token, $open_id){
		$company_user_url  = "https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=$access_token&userid=$open_id";
		$company_user_result = json_decode(file_get_contents($company_user_url), true);
		return $company_user_result;
	}
	
	
	/**
	 * Description: 缓存TOKEN方法
	 * @version 2016年8月31日 上午10:38:09
	 * @author lin.yujiao@whaley.cn
	 * @return mixed
	 */
	public static function accessToken() {
		$tokenFile = "./access_token.txt";//缓存文件名
		$data = json_decode(file_get_contents($tokenFile));
		if ($data->expire_time < time() or !$data->expire_time) {
			$corpid = C('WeChatConfig.corpid');
			$secret = C('WeChatConfig.secret');
			$token_url  = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$corpid&corpsecret=$secret";
			$token_result = json_decode(file_get_contents($token_url), true);
			$access_token = $token_result['access_token'];
			if($access_token) {
				$data->expire_time = time() + 7000;
				$data->access_token = $access_token;
				$fp = fopen($tokenFile, "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		} else {
			$access_token = $data->access_token;
		}
		return $access_token;
	}
	
	/**
	 * Description: 缓存jsApi的ticket
	 * @version 2016年9月23日 下午1:22:45
	 * @author lin.yujiao@whaley.cn
	 */
	private function get_jsapi_ticket($access_token){
		$ticketFile = "./jsapi_ticket.txt";//缓存文件名
		$data = json_decode(file_get_contents($ticketFile));
		if (empty($data) or $data->expire_time < time() or !$data->expire_time) {
			if(empty($access_token)){
				$access_token = self::accessToken();
			}
			$ticket_url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$access_token";
			$ticket_result = json_decode(file_get_contents($ticket_url), true);
			$ticket = $ticket_result["ticket"];
			if($ticket) {
				$data->expire_time = time() + 7000;
				$data->jsapi_ticket = $ticket;
				$fp = fopen($ticketFile, "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		} else {
			$ticket = $data->jsapi_ticket;
		}
		return $ticket;
	}
	
	private function create_nonce_str($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	/**
	 * Description: 获取签名
	 * @version 2016年9月23日 下午1:32:49 
	 * @author lin.yujiao@whaley.cn
	 */
	public function get_signpackage($access_token) {
		$jsapiTicket = self::get_jsapi_ticket($access_token);
		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$timestamp = time();
		$nonceStr = self::create_nonce_str();
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array(
			"appId"     => C('WeChatConfig.corpid'),
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string
		);
		return $signPackage;
	}
	
	private function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		// 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
		// 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
		curl_setopt($curl, CURLOPT_URL, $url);
	
		$res = curl_exec($curl);
		curl_close($curl);
	
		return $res;
	}
}