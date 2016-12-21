<?php
namespace Home\Controller;

use Home\Biz\AdminBiz;
use Home\Biz\AuthBiz;
use Home\Biz\InvoiceBiz;
use Home\Biz\JobinfoBiz;
use Home\Biz\MeetingBiz;
use Home\Biz\CommonBiz;
use Home\Biz\ProvinceCityDistrictBiz;
use Home\Biz\VoteBiz;
use Home\Biz\WechatBiz;
use Think\Controller;



/**
 * 页面ajax请求服务处理Controller
 * @author Simon
 *
 */
class AjaxServiceController extends Controller {
	
    /**
     * Ajax服务中转类
     */
	public function center($moudle, $util){

        if(in_array($moudle ,array("auth", "city", "invoice", "jobinfo", 'meeting', 'vote'))){
            if(AdminBiz::verifyUser() == false){
                $this->responseFail(5, '非法用户！');
            }
        }



		switch(strtolower($moudle)){
			case 'admin':
				$this->adminService($util);
				return;
			case 'auth':

				$this->authService($util);
				return;
			case 'city':
				$this->cityService($util);
				return;
			case 'invoice':
				$this->invoiceService($util);
				return;
			case 'jobinfo':
				$this->jobinfoService($util);
				return;
			case 'meeting':
				$this->meetingService($util);
				return;
			case 'vote':
				$this->voteService($util);
				return;
			case 'common' :
				$this->commonService($util);
				return;
			default:
				$this->responseFail(5, '无法找到服务模块');
		}
		
    }
    
    public function commonService($util)
    {
    	switch ($util) {
    		case 'getAllProvince' :
    			$lst_en = CommonBiz::getAllProvince();
    			$this->responseSuccess($lst_en);
    			break;
    		case 'getCityListByProvinceId' :
    			$province_id = I('province_id');
    			$lst_en = CommonBiz::getCityListByProvinceId($province_id);
    			$this->responseSuccess($lst_en);
    			break;
    		case 'getDistrictListByCityId' :
    			$city_id = I('city_id');
    			$lst_en = CommonBiz::getDistrictByCityId($city_id);
    			$this->responseSuccess($lst_en);
    			break;
    		case 'getoptions' :
    			$options = CommonBiz::getOptions(I('key'));
    			if (empty($options)) {
    				$this->responseFail(5, C('OPTIONS.NOTFOUND'));
    			} else {
    				$this->responseSuccess($options);
    			}
    			break;
    		default :
    			$this->responseFail(5, '无法找到服务方法');
    			break;
    	}
    }
    /**
     * Description: 会议模块
     * @version 2016年9月26日 下午4:17:52 
     * @author lin.yujiao@whaley.cn
     * @param $util
     */
    public function meetingService($util){
    	$login_user = cookie ( 'login_user_id' );
    	switch(strtolower($util)){
    		case 'querymeetinglist' :
    			$result = MeetingBiz::queryMeetingList(I('meeting_name'), I('start_time'), I('end_time'), I('pageSize'), I('index'));
    			$this-> responseSuccess($result);
    			return;
    		case 'querymembersbymeetingid' :
    			$resultList = MeetingBiz::querymembersByMeetingId(I('meeting_id'));
    			$this->responseSuccess($resultList);
    			return;
    		case 'gettagmember' :
    			$tag_members_result = WechatBiz::getTagMemberTagId(I('access_token'),I('tag_id'));
    			if($tag_members_result['errcode'] != 0){
    				$this->responseFail(1, $tag_members_result['errmsg']);
    			}else{
    				$tag_members = $tag_members_result['userlist'];
	    			MeetingBiz::removeExistMember($tag_members, I('meeting_id'));
	    			$this->responseSuccess($tag_members);
    			}
    			return;
    		case 'saveorupdate':
    			$meeting_id = I('meeting_id');
    			/* $latitude = empty(I('latitude'))?C('WeChatConfig.meeting_latitude'):I('latitude');
    			$longitude = empty(I('longitude'))?C('WeChatConfig.meeting_longitude'):I('longitude'); */
    			$latitude = I('latitude');
    			$longitude = I('longitude');
    			if(empty($latitude) || empty($longitude)){
	    			$latitude = C('WeChatConfig.meeting_latitude');
	    			$longitude = C('WeChatConfig.meeting_longitude');
    			}
    			$meeting = array(
    				'meeting_name' => I('meeting_name'),
    				'start_time' => I('start_time'),
    				'end_time' => I('end_time'),
    				'remark' => I('remark'),
    				'member_count' => I('member_count'),
    				'latitude' => $latitude,
    				'longitude' => $longitude,
    				'sign_in_from' => I('sign_in_from'),
    				'sign_in_to' => I('sign_in_to'),
    			);
    			$add_members = I('members');
    			$login_user = empty($login_user)?'whaleyinfo.www.meetingBiz':$login_user;
    			if (empty ($meeting_id)) {
    				$msg = MeetingBiz::addMeeting($meeting, $login_user, $add_members);
    			} else {
    				$msg = MeetingBiz::updateMeeting($meeting, $meeting_id, $login_user, $add_members);
    			}
    			if (!empty($msg)) {
    				$this->responseFail(500, $msg);
    			} else {
    				$this->responseSuccess();
    			}
    			return;
    		default:
    			$this->responseFail(5, '无法找到服务方法');
    	}
    }
    /**
     * Description: 投票模块
     * @version 2016年11月23日 下午1:57:22 
     * @author lin.yujiao@whaley.cn
     * @param unknown $util
     */
    public function voteService($util){
    	$login_user = cookie ( 'login_user_id' );
    	switch(strtolower($util)){
    		case 'queryvotelist' :
    			$result = VoteBiz::queryVoteList(I('vote_name'), I('start_time'), I('end_time'), I('pageSize'), I('index'));
    			$this-> responseSuccess($result);
    			return;
    		case 'queryitemsbyvoteid' :
    			$result = VoteBiz::queryItemsByVoteid(I('vote_id'));
    			$this-> responseSuccess($result);
    			return;
    		case 'saveorupdate':
    	    	$vote_id = I('vote_id');
    			$vote = array(
    				'vote_name' => I('vote_name'),
    				'check_box_max' => I('check_box_max'),
    				'start_time' => I('start_time'),
    				'end_time' => I('end_time'),
    				'remark1' => I('remark1')
    			);
    			$add_items = I('add_items');
    			$login_user = empty($login_user)?'whaleyinfo.www.voteBiz':$login_user;
    			$msg = '';
    			if (empty ($vote_id)) {
    				$msg = VoteBiz::addVote($vote, $login_user, $add_items);
    			} else {
    				$edit_items= I('edit_items');
    				$del_items = I('del_items');
    				$msg = VoteBiz::updateVote($vote, $vote_id, $login_user, $add_items, $edit_items, $del_items);
    			}
    			if (!empty($msg)) {
    				$this->responseFail(500, $msg);
    			} else {
    				$this->responseSuccess();
    			}
    		default:
    			$this->responseFail(5, '无法找到服务方法');
    	}
    }
    
    
    /**
     * admin 模块
     * @param string $util 方法名
     */
    public function adminService($util){
    	switch(strtolower($util)){
    		case 'login':
    			//用户登陆
    			$user_id = I('param.user_id');
    			$password = I('param.password');
    			
    			$error_msg = AdminBiz::login($user_id, $password);
    			
    			if(empty($error_msg)){
    				$this->responseSuccess();
    			}
    			else{
    				$this->responseFail(5, $error_msg);
    			}
    			return;
    		case 'logout':
    				//用户登陆
    			$user_id = I('param.user_id');
    			$error_msg = AdminBiz::logout($user_id);
    			if(empty($error_msg)){
    				$this->responseSuccess();
    			}
    			else{
    				$this->responseFail(5, $error_msg);
    			}
    			return;
    		default:
    			$this->responseFail(5, '无法找到服务方法');
    	}
    }
    
    
    public function authService($util){
    	switch(strtolower($util)){
    		case 'domainquery':
    			//用户登陆
    			$domain_name = I('param.domain_name');
    			$domain_url = I('param.domain_url');
    			$domain_id = I('param.domain_id');
    			 
    			$reultList = AuthBiz::queryDomainByPara($domain_name, $domain_url,$domain_id);
    			$this->responseSuccess($reultList);
    			return;
    			
    		case 'adddomain':

    			$domain_name = I('param.domain_name');
    			$domain_url = I('param.domain_url');
    			$default_url = I('param.default_url');
    			$reultList = AuthBiz::addDomain($domain_name, $domain_url,$default_url);
    			$this->responseSuccess();
    			return;
    		case 'updatedomain':
    			$domain_id = I('param.domain_id');
    			$domain_name = I('param.domain_name');
    			$domain_url = I('param.domain_url');
    			$default_url = I('param.default_url');
    			$reultList = AuthBiz::updateDomain($domain_id,$domain_name, $domain_url,$default_url);
    			$this->responseSuccess();
    				return;
    		case 'resetpassword':
    			$uname = I('param.uname');
    			$originalpass = I('param.originalpass');
    			$newpass = I('param.newpass');
    			$confirmpass = I('param.confirmpass');
    			$reultList = AuthBiz::resetPassword($uname,$originalpass, $newpass,$confirmpass);
    			$this->responseSuccess($reultList);
    				return;
    		case 'menuresourcequery':
    			$menu_id = I('param.menu_id');
    			$menu_name = I('param.menu_name');
    			$menu_domain = I('param.menu_domain');
    			$level = I('param.level');
    			$parent_id = I('param.parent_id');
    			$reultList = AuthBiz::queryMenuResourceByPara($menu_id, $menu_name,$menu_domain,$level,$parent_id);
    			$this->responseSuccess($reultList);
    		    	return;
    	   case 'userquery':
    			$user_name = I('param.user_name');
    		    $user_nick = I('param.user_nick');
    		    $user_mobile = I('param.user_mobile');
    		    $user_id = I('param.user_id');
    		    $reultList = AuthBiz::queryUserByPara($user_name, $user_nick,$user_mobile,$user_id);
    		    $this->responseSuccess($reultList);
    	            return;
    	   case 'groupquery':
    		   	$group_name = I('param.group_name');
    		   	$group_id = I('param.group_id');
    		    $reultList = AuthBiz::queryGroupByPara($group_name, $group_id);
    		    $this->responseSuccess($reultList);
    		        return;
    	   case 'addgroup':
    	    	$group_name = I('param.group_name');
    		    $group_desc = I('param.group_desc');
    		    $menu_relation = I('param.menu_relation');
    		    $user_relation = I('param.user_relation');
    		    $error_msg = AuthBiz::addGroup($group_name, $group_desc,$menu_relation,$user_relation);
    		    if(empty($error_msg)){
    		    $this->responseSuccess();
    		    }else{
    		    	$this->responseFail(5, $error_msg);
    		    }
    		       return;
    	   case 'updatemenuresource':
    		     $menu_id = I('param.menu_id');
    		     $menu_name = I('param.menu_name');
    		     $menu_url = I('param.menu_url');
    		     $menu_display = I('param.menu_display');
    		     $reultList = AuthBiz::updatemenuResourceByMenuId($menu_id, $menu_name,$menu_url,$menu_display);
    		     $this->responseSuccess($reultList);
    		      return;
    	   case 'addmenuresource':
    		     $menu_name = I('param.menu_name');
    		     $menu_domain = I('param.menu_domain');
    		     $menu_url = I('param.menu_url');
    		     $parent_id = I('param.parent_id');
    		     $level = I('param.level');
    		     $menu_display = I('param.menu_display');
    		     $reultList = AuthBiz::addMenuResource( $menu_name,$menu_domain,$menu_url,$parent_id,$level,$menu_display);
    		     $this->responseSuccess($reultList);
    		      return;
    	   case 'removeuserrelation':
    		      $relatn_id = I('param.relatn_id');
    		      AuthBiz::removeUserRelation($relatn_id);
    		      $this->responseSuccess();
    		      return;
    	   case 'removemenurelation':
    		      $relatn_id = I('param.relatn_id');
    	   	      AuthBiz::removeMenuRelation($relatn_id);
    		      $this->responseSuccess();
    		      return;
    		      
    	   case 'addtogrouprelationmenu':
    		      $group_id = I('param.group_id');
    		      $menu_relation = I('param.menu_relation');
    		      AuthBiz::addToGroupRelationMenu($group_id,$menu_relation);
    		      $this->responseSuccess();
    		      return;
    	   case 'addtogrouprelationuser':
    		      $group_id = I('param.group_id');
    		      $user_relation = I('param.user_relation');
    		      AuthBiz::addToGroupRelationUser($group_id,$user_relation);
    		      $this->responseSuccess();
    		      return;
    	   case 'adduser':
    		      $user_name = I('param.user_name');
    		      $user_nick = I('param.user_nick');
    		      $user_mobile = I('param.user_mobile');
    		      $password = I('param.password');
    		      $user_address = I('param.user_address');
    		      $error_msg=AuthBiz::addUser($user_name,$user_nick,$user_mobile,$password,$user_address);
    	          if(empty($error_msg)){
    		       $this->responseSuccess();
    		      }else{
    		    	$this->responseFail(5, $error_msg);
    		      }
    		      return;  
    	   case 'updateuser':
            	  $user_id = I('param.user_id');
    		      $user_name = I('param.user_name');
    		      $user_nick = I('param.user_nick');
    		      $user_mobile = I('param.user_mobile');
    		      $password = I('param.password');
    		      $user_address = I('param.user_address');
    		      AuthBiz::updateUser($user_id,$user_name,$user_nick,$user_mobile,$password,$user_address);
    		      $this->responseSuccess();
    		      	return;
    		case 'userrulequery':
    		      $user_name = I('param.user_name');
    		      $reultList=AuthBiz::userRuleQuery($user_name);
    		      $this->responseSuccess($reultList);
    		      return;
    		case 'deleteuserrule':
    		      $rule_id = I('param.rule_id');
    		      AuthBiz::deleteUserRule($rule_id);
    		      $this->responseSuccess();
    		      return;
    		case 'adduserrulesingle':
    		      $user_name = I('param.user_name');
    		      $lst_menu = I('param.lst_menu');
    		      
    		      $error_msg=AuthBiz::addUserRuleSingle($user_name,$lst_menu);
    		      if(empty($error_msg)){
    		      	$this->responseSuccess();
    		      }else{
    		      	$this->responseFail(5, $error_msg);
    		      }
    		      return;
    		      
     		default:
    			$this->responseFail(5, '无法找到服务方法');
    	}
    }
    
    public function cityService($util){
    	switch(strtolower($util)){
    		case 'citylist':
    			$city_en = array (
    			'province_id' => I ( 'province_id' ),
    			'city_name' => I ( 'city_name' ),
   				'page_index' => I ( 'page_index' ),
   				'page_size' => I ( 'page_size' )
   				);
    			$return_en = ProvinceCityDistrictBiz::citylist( $city_en );
    			$this->responseSuccess ( $return_en );
    			return;
    		case 'addcity':
    			$province_id = I('param.province_id');
    			$city_name = I('param.city_name');
    			$reultList = ProvinceCityDistrictBiz::addcity( $province_id,$city_name);
    			$this->responseSuccess($reultList);
    			return;
    		case 'updatecity':
    			$city_id = I('param.city_id');
    			$city_name = I('param.city_name');
    			$reultList = ProvinceCityDistrictBiz::updateCity($city_id,$city_name);
    			$this->responseSuccess($reultList);
    			return;
    		case 'districtlist':
    			$district_en = array (
    			'province_id' => I ( 'province_id' ),
    			'city_name' => I ( 'city_name' ),
    			'district_name' => I ( 'district_name' ),
    			'page_index' => I ( 'page_index' ),
    			'page_size' => I ( 'page_size' )
    			);
    			$return_en = ProvinceCityDistrictBiz::districtlist( $district_en );
    			$this->responseSuccess ( $return_en );
    			return;
    		case 'adddistrict':
    			$city_id = I('param.city_id');
    			$district_name = I('param.district_name');
    			$reultList = ProvinceCityDistrictBiz::adddistrict( $city_id,$district_name);
    			$this->responseSuccess($reultList);
    			return;
    		case 'updatedistrict':
    			$district_id = I('param.district_id');
    			$district_name = I('param.district_name');
    			$reultList = ProvinceCityDistrictBiz::updatedistrict($district_id,$district_name);
    			$this->responseSuccess($reultList);
    			return;
    		default:
    			$this->responseFail(5, '无法找到服务方法');
    	}
    }
    
    
    public function invoiceService($util){
    	switch(strtolower($util)){
    		case 'downpdf':
    			//用户登陆
    			$telephone = I('param.telephone');
    			$thirdOrderId = I('param.thirdOrderId');
    			$reultList = InvoiceBiz::downLoadInvoice($telephone, $thirdOrderId);
    			
    			$this->responseSuccess($reultList);
    			return;
    		default:
    			$this->responseFail(5, '无法找到服务方法');
    	}
    }
    
    public function jobinfoService($util){
    	switch(strtolower($util)){
    		case 'jobinfoquery':
    			$job_name = I('param.job_name');
    			$job_desc = I('param.job_desc');
    			$reultList = JobinfoBiz::queryJobinfoByNmae($job_name, $job_desc);
    			$this->responseSuccess($reultList);
    			return;
    		default:
    			$this->responseFail(5, '无法找到服务方法');
    	}
    }
    
   
    
    public function responseSuccess($result){
    	//0，正确；1，参数错误；2,安全码校验失败;3，无法反序列化；4,服务器错误;5，业务逻辑错误
    	$return_json = array(
    			'IsSuccess' => 1,
    			'ErrorMsg' => '',
    			'ErrorCode' => 0,
    			'Result' => $result,
    	);
    	$this->ajaxReturn($return_json);
    }
    
    public function responseFail($error_code, $error_msg){
    	//0，正确；1，参数错误；2,安全码校验失败;3，无法反序列化；4,服务器错误;5，业务逻辑错误
    	$return_json = array(
    			'IsSuccess' => 0,
    			'ErrorMsg' => $error_msg,
    			'ErrorCode' => $error_code,
    			'Result' => null,
    	);
    	$this->ajaxReturn($return_json);
    }
}