<?php
namespace Home\Controller;
use Home\Biz\MeetingBiz;
use Think\Controller;
class VoteController extends LoginBaseController {
    /**
     * Description: 投票查询
     * @version 2016年11月22日 下午1:31:13 
     * @author lin.yujiao@whaley.cn
     */
    public function voteList(){
    	$this->assign('page_name', "投票查询");
    	$this->display('meeting/voteList');
    }
    /**
     * Description: 投票新增
     * @version 2016年11月22日 下午1:31:13 
     * @author lin.yujiao@whaley.cn
     */ 
    public function addVotePage(){
    	$this->assign('operation','ADD');
    	$this->assign('page_name', "投票新增");
    	$vote = array(
    		'check_box_max' => 1
    	);
    	$this->assign('vote', $vote);
    	$this->display('meeting/voteInfo');
    }
    /**
     * Description: 投票编辑
     * @version 2016年11月22日 下午1:31:13 
     * @author lin.yujiao@whaley.cn
     */
    public function editVotePage()
    {
    	$vote = M('vote_subject', '', 'DB_system')->where('vote_id = "%s"', array(I('vote_id')))->find();
    	$this->assign('vote', $vote);
    	$this->assign('operation','EDIT');
    	$this->assign('page_name', "投票编辑");
    	$this->display('meeting/voteInfo');
    }
    /**
     * Description: 投票图表
     * @version 2016年11月22日 下午1:31:13 
     * @author lin.yujiao@whaley.cn
     */
    public function voteCharts()
    {
    	$vote_id = I('vote_id');
    	$vote = M('vote_subject', '', 'DB_system')->where('vote_id = "%s"', array(I('vote_id')))->find();
    	$this->assign('vote', $vote);
    	$this->assign('page_name', "投票结果图表");
    	$this->display('meeting/voteCharts');
    }
    
    
    /**
     * Description: 投票记录EXCEL导出
     * @version 2016年11月22日 
     * @author lin.yujiao@whaley.cn
     */
    public function voteRecordExcel(){
    	/* Vendor('phpExcel.PHPExcel');
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
    	$objWriter->save('php://output'); */
    }
    
    /**
     * Description: 二维码生成
     * @version 2016年11月22日 
     * @author lin.yujiao@whaley.cn
     */
    public function voteQrCode(){
    	$vote_id = I('vote_id');
    	if($vote_id){
    		$appid = C('WeChatConfig.corpid');
    		$value = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='. $appid . '&redirect_uri=http%3a%2f%2fo.whaley.cn%2fwechat%2fshowVote%3Fvote_id%3D' . $vote_id . '&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
    		Vendor('phpqrcode.phpqrcode');
    		$errorCorrectionLevel = 'L';//容错级别
    		$matrixPointSize = 6;//生成图片大小
    		//生成二维码图片
    		\QRcode::png($value, './vote_qrcode_old.png', $errorCorrectionLevel, $matrixPointSize, 2);
    		$logo = "./Public/assets/img/whaley_logo.png";//准备好的logo图片
    		$QR = 'vote_qrcode_old.png';//已经生成的原始二维码图
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
    		imagepng($QR, 'vote_qrcode.png');
    		echo '<img style="margin-left:10px;" src="/vote_qrcode.png">';
    		//\QRcode::png($url);
    	} 
    }
    
}