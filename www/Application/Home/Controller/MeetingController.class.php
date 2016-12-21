<?php
namespace Home\Controller;
use Think\Controller;
use Home\Biz\WechatBiz;
use Home\Biz\MeetingBiz;
use Home\Biz\CommonBiz;
class MeetingController extends LoginBaseController {
    /**
     * Description: 会议列表
     * @version 2016年9月26日 下午3:28:14 
     * @author lin.yujiao@whaley.cn
     */
    public function meetingList(){
    	$this->assign('page_name', "会议查询");
    	$this->display('meeting/meetingList');
    }
    /**
     * Description: 会议新增 
     * @version 2016年9月26日 下午3:28:14
     * @author lin.yujiao@whaley.cn
     */ 
    public function addMeetingPage(){
    	$this->assign('operation','ADD');
    	$this->assign('page_name', "会议新增");
    	$this->assign("sign_in_options" ,json_encode(CommonBiz::getOptions("MEETING_SIGN_IN")));
    	$this->assign("sign_off_options" ,json_encode(CommonBiz::getOptions("MEETING_SIGN_OFF")));
    	$this->assign("from_type_options" ,json_encode(CommonBiz::getOptions("MEETING_FROM_TYPE")));
    	$this->display('meeting/meetingInfo');
    }
    /**
     * Description: 会议编辑
     * @version 2016年9月27日 下午2:25:47
     * @author lin.yujiao@whaley.cn
     */
    public function editMeetingPage()
    {
    	$meeting = M('meeting', '', 'DB_system')->where('meeting_id = "%s"', array(I('meeting_id')))->find();
    	$this->assign('mtg', $meeting);
    	$this->assign('operation','EDIT');
    	$this->assign('page_name', "会议编辑");
    	$this->assign("sign_in_options" ,json_encode(CommonBiz::getOptions("MEETING_SIGN_IN")));
    	$this->assign("sign_off_options" ,json_encode(CommonBiz::getOptions("MEETING_SIGN_OFF")));
    	$this->assign("from_type_options" ,json_encode(CommonBiz::getOptions("MEETING_FROM_TYPE")));
    	$this->display('meeting/meetingInfo');
    }
    /**
     * Description: 获取标签
     * @version 2016年9月26日 下午3:28:14
     * @author lin.yujiao@whaley.cn
     */
    public function getTagMember(){
    	$tag_list;
    	$access_token = WechatBiz::accessToken();
    	if(!empty($access_token)){
    		$tag_list = WechatBiz::getTagList($access_token);
    		$meeting_id = I('meeting_id');
    		//MeetingBiz::removeExistMember($tag_member_result, $meeting_id);
    	}else{
    		$tag_member_result = array();
    		$this->assign('error_msg','微信接口获取标签失败');
    	}
    	$this->assign('lst_tag', $tag_list['taglist']);
    	$this->assign('access_token', $access_token);
    	$this->assign('meeting_id', $meeting_id);
    /* 	$tag_member_result;
    	$access_token = WechatBiz::accessToken();
    	if(!empty($access_token)){
	    	$tag_member_result = WechatBiz::getTagMemberList($access_token, C('MeetingConfig.tag_name'));
	    	$meeting_id = I('meeting_id');
	    	MeetingBiz::removeExistMember($tag_member_result, $meeting_id);
    	}else{
    		$tag_member_result = array();
    		$this->assign('error_msg','微信接口获取成员名单失败');
    	}
    	$this->assign('lst_member_info', $tag_member_result); */
    	$this->display('meeting/meetingMember');
    }
    
    
    /**
     * Description: 会议记录EXCEL导出
     * @version 2016年9月29日 下午4:17:04 
     * @author lin.yujiao@whaley.cn
     */
    public function meetingRecordExcel(){
    	Vendor('phpExcel.PHPExcel');
    	$meeting = M('meeting', '', 'DB_system')->where('meeting_id = "%s"', array(I('meeting_id')))->find();
    	$objPHPExcel = MeetingBiz::meetingRecordExcel($meeting['meeting_id']);
    	//输出到浏览器
    	$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
    	ob_end_clean();
    	header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
    	header('Content-Disposition:attachment;filename="' .$meeting['meeting_name'] . '-' . date("YmdHis",time()) . '.xls"');
    	header("Content-Transfer-Encoding:binary");
    	$objWriter->save('php://output');
    }
    
    /**
     * Description: 二维码生成
     * @version 2016年10月8日 下午5:03:27 
     * @author lin.yujiao@whaley.cn
     */
    public function meetingQrCode(){
    	$start_time = I('start_time');
    	if($start_time){
    		$meeting_key = date("Y-m-d",strtotime($start_time));
    		$appid = C('WeChatConfig.corpid');
    		$value = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='. $appid . '&redirect_uri=http%3a%2f%2fo.whaley.cn%2fwechat%2fmeetingSignIn%3Fmeeting_key%3D' . $meeting_key . '&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
    		Vendor('phpqrcode.phpqrcode');
    		$errorCorrectionLevel = 'L';//容错级别
    		$matrixPointSize = 6;//生成图片大小
    		//生成二维码图片
    		\QRcode::png($value, './meeting_qrcode_old.png', $errorCorrectionLevel, $matrixPointSize, 2);
    		$logo = "./Public/assets/img/whaley_logo.png";//准备好的logo图片
    		$QR = 'meeting_qrcode_old.png';//已经生成的原始二维码图
    		if ($logo !== FALSE) {
    			$QR = imagecreatefromstring(file_get_contents($QR));
    			$logo = imagecreatefromstring(file_get_contents($logo));
    			$QR_width = imagesx($QR);//二维码图片宽度
    			$QR_height = imagesy($QR);//二维码图片高度
    			$logo_width = imagesx($logo);//logo图片宽度
    			$logo_height = imagesy($logo);//logo图片高度
    			$logo_qr_width = $QR_width / 5;
    			$scale = $logo_width/$logo_qr_width;
    			$logo_qr_height = $logo_height/$scale;
    			$from_width = ($QR_width - $logo_qr_width) / 2;
    			//重新组合图片并调整大小
    			imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
    					$logo_qr_height, $logo_width, $logo_height);
    		}
    		//输出图片
    		imagepng($QR, 'meeting_qrcode.png');
    		echo '<img style="margin-left:10px;" src="/meeting_qrcode.png">';
    		//\QRcode::png($url);
    	}
    }
    
}