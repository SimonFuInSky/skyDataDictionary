<?php
namespace Home\Biz;
use Home\Biz\CommonBiz;
/**
 * @Title: 会议记录
 * Description: 
 * @version 2016年9月26日 下午4:15:04 
 * @author lin.yujiao@whaley.cn
 */
class MeetingBiz {
	/**
	 * Description: 查询会议记录
	 * @version 2016年9月26日 下午4:15:14 
	 * @author lin.yujiao@whaley.cn
	 * @param $meeting_name
	 * @param $start_time
	 * @param $end_time
	 * @param $pageSize
	 * @param $index
	 */
    public static function queryMeetingList($meeting_name, $start_time, $end_time, $pageSize, $index) {
    	//查询条件筛选部分
    	if (!empty ($meeting_name)) {
    		$condition_array['meeting_name'] = array('like', "%" . $meeting_name . "%");
    	}
    	if (!empty ($start_time)) {
    		$condition_array['start_time'] = array('egt', $start_time);
    	}
    	if (!empty ($end_time)) {
    		$condition_array['end_time'] = array('elt', $end_time);
    	}
    	$condition_array['delete_flag'] = array('eq', 0);
    	//M方法实例化会议模型
    	$meeting = M('meeting','','DB_system');
    	//查询
    	$list_meeting_infos = $meeting->where($condition_array)->page($index, $pageSize)->select();
    	// 总记录数
    	$totalCount = $meeting->where($condition_array)->count();
    	//构造分页返回信息
    	$resultMap = CommonBiz::fillPageResult($list_meeting_infos, $totalCount, $pageSize, $index);
    	return $resultMap;
    }
    /**
     * Description: 查询会议参加人
     * @version 2016年9月27日 上午11:27:24 
     * @author lin.yujiao@whaley.cn
     * @param $meeting_id
     * @param $start_time
     * @param $end_time
     * @param $pageSize
     * @param $index
     */
    public static function querymembersByMeetingId($meeting_id) {
    	$list_member_infos;
    	//查询条件筛选部分
    	if (!empty ($meeting_id)) {
    		$condition_array['meeting_id'] = array('eq', $meeting_id);
    		//M方法实例化会议参加人模型
    		$member = M('meeting_member','','DB_system');
    		//查询
    		$list_member_infos = $member->where($condition_array)->order("employee_code")->select();
    	}else{
    		$list_member_infos = array();
    	}
    	return $list_member_infos;
    }
    
    
    
    /**
     * Description: 新增会议
     * @version 2016年9月27日 下午2:05:10 
     * @author lin.yujiao@whaley.cn
     * @param $meeting
     * @param $login_user
     */
    public static function addMeeting($meeting, $login_user, $add_members)
    {
    	$meeting ['meeting_id'] = self::generateMeetingId();
    	$meeting ['create_date'] = date("y-m-d H:i:s", time());
    	$meeting ['create_user'] = $login_user;
    	$meeting ['delete_flag'] = 0;
    	$meeting ['member_count'] = 0;
    	//校验
    	$msg = self::validate($meeting, $add_members);
    	//无拦截执行新增功能
    	if (empty($msg)) {
    		$success_count = self::addMeetingMembers($add_members, $meeting , $login_user);
    		//-1表示批量更新失败 其他表示新增成功数量
    		if($success_count >= 0){
    			$meeting ['member_count'] = self::queryMemberCount($meeting ['meeting_id']);
    			$success_count = M('meeting','','DB_system')->add($meeting);
    		}
    		//会议更新成功 返回条数为1
    		if($success_count != 1){
    			$msg = '新增会议失败';
    		}
    	}
    	return $msg;
    }
    
    /**
     * Description: 更新会议
     * @version 2016年9月27日 下午2:04:45 
     * @author lin.yujiao@whaley.cn
     * @param $meeting
     * @param $meeting_id
     * @param $login_user
     * @return string
     */
    public static function updateMeeting($meeting, $meeting_id, $login_user, $add_members)
    {
    	$meeting ['meeting_id'] = $meeting_id;
    	$meeting ['update_date'] = date("y-m-d H:i:s", time());
    	$meeting ['update_user'] = $login_user;
    	//校验
    	$msg = self::validate($meeting,$add_members);
    	//无拦截执行更新功能
    	if (empty($msg)) {
    		$success_count = self::addMeetingMembers($add_members, $meeting , $login_user);
    		//-1表示批量更新失败 其他表示新增成功数量
    		if($success_count >= 0){
    			$meeting ['member_count'] = self::queryMemberCount($meeting ['meeting_id']);
    			$success_count = M('meeting','','DB_system')->where('meeting_id="%s"', array($meeting['meeting_id']))->save($meeting);
    		}
    		//会议更新成功 返回条数为1
    		if($success_count != 1){
    			$msg = '更新会议失败';
    		}
    	}
    	return $msg;
    }
    /**
     * Description: 新增会议参加人
     * @version 2016年9月28日 上午10:22:21 
     * @author lin.yujiao@whaley.cn
     * @param unknown $add_members
     */
    private function addMeetingMembers($members, $meeting, $login_user){
    	if(!empty($members) && count($members) > 0){
    		$entitys = array();
    		foreach($members as $element) {
    			array_push($entitys, array(
    				'member_id' => self::generateMeetingMemberId(),
    				'meeting_id' => $meeting ['meeting_id'],
    				'employee_name' => $element['name'],
    				'employee_code' => $element['user_id'],
    				'create_user' => $login_user,
    				'create_date' => date("y-m-d H:i:s", time()),
    				'sign_in_sts' => 'NONE',
    				'sign_off_sts' => 'NONE',
    				'from_type' => 'TAG'//表示来源为微信标签
    			));
    		}
    		$result = M('meeting_member','','DB_system')->addAll($entitys);
    		return ($result == true?count($members):-1);
    	}
    	return 0;
    }
    /**
     * Description: 查询记录数
     * @version 2016年9月28日 下午1:42:03 
     * @author lin.yujiao@whaley.cn
     * @param unknown $meeting_id
     */
    private function queryMemberCount($meeting_id){
    	$condition_array = array();
    	$condition_array['meeting_id'] = array('eq', $meeting_id);
    	// 总记录数
    	return M('meeting_member','','DB_system')-> where($condition_array)->count();
    }
    /**
     * Description: 去除数据库已存在的成员信息
     * @version 2016年9月28日 下午1:42:03 
     * @author lin.yujiao@whaley.cn
     * @param $meeting_id
     */
    public static function removeExistMember(&$member_list,$meeting_id){
    	//不为空时进行去重
    	if(!empty($meeting_id)){
    		$condition_array = array();
    		$condition_array['meeting_id'] = array('eq', $meeting_id);
    		$exist_list =  M('meeting_member','','DB_system')->where($condition_array)->select();
    		if(!empty($exist_list) && count($exist_list) > 0){
    			foreach($member_list as $key => $member){
    				$exist_user_id = $member['userid'];
    				$exist_flag = array_filter(
    					$exist_list,function($item) use ($exist_user_id) {
    						return $item['employee_code'] == $exist_user_id;
    				});
    				if(empty($exist_flag)){
    					continue;
    				}
    				unset($member_list[$key]);
    			}
    		}
    	}
    }
    /**
     * Description:
     * @version 生成会议唯一编码
     * @author lin.yujiao@whaley.cn
     * @return string
     */
    public static function generateMeetingId()
    {
    	$seq = CommonBiz::getNewSeq('meeting_id') + 10000000;
    	return 'MT' . $seq;
    }
    /**
     * Description:
     * @version 生成会议成员唯一编码
     * @author lin.yujiao@whaley.cn
     * @return string
     */
    public static function generateMeetingMemberId()
    {
    	$seq = CommonBiz::getNewSeq('member_id') + 10000000;
    	return 'MTR' . $seq;
    }
    /**
     * Description: 是否存在会议
     * @version 2016年9月26日 下午4:15:14
     * @author lin.yujiao@whaley.cn
     * @param $s_time
     */
    public static function existMeeting($s_time, $meeting_id) {
    	if(!empty($meeting_id)){
    		$condition_array['meeting_id'] = array('neq', $meeting_id);
    	}
    	$condition_array['delete_flag'] = array('neq', 1);
    	$condition_array['start_time'] = array('between', array(date("Y-m-d",$s_time),date("Y-m-d 23:59:59",$s_time)));
    	//M方法实例化会议模型
    	$meeting = M('meeting','','DB_system');
    	//查询
    	$list_meeting_infos = $meeting->where($condition_array)->select();
    	if(count($list_meeting_infos) > 0){
    		return $list_meeting_infos[0];
    	}else{
    		return null;
    	}
    }
    /**
     * Description: 校验
     * @version 2016年9月12日 下午4:56:15
     * @author lin.yujiao@whaley.cn
     * @param unknown $meeting
     */
    public static function validate($meeting, $add_members)
    {
    	//判空
    	if (empty($meeting['meeting_name'])) {
    		return "会议主题未填写";
    	} else if (empty($meeting['start_time'])) {
    		return "开始时间未填写";
    	}else if(empty($meeting['end_time'])){
    		return "结束时间未填写";
    	}
    	$start_time = $meeting['start_time'];
    	//会议开始时间戳
    	$s_time = strtotime($start_time);
    	//会议结束时间戳
    	$e_time = strtotime($meeting['end_time']);
    	//会议开始前签到范围时间戳
    	$sign_from_time = strtotime($meeting['sign_in_from']);
    	//会议开始后签到范围时间戳
    	$sign_to_time = strtotime($meeting['sign_in_to']);
    	if($s_time == false){
    		return "开始时间输入不合法";
    	}
    	if($e_time == false){
    		return "结束时间输入不合法";
    	}
    	if($sign_from_time == false){
    		return "签到范围(开始前)输入不合法";
    	}
    	if($sign_to_time == false){
    		return "签到范围(开始后)输入不合法";
    	}
    	if($s_time > $e_time){
    		return "开始时间不能比结束时间晚";
    	}
    	if($sign_from_time > $sign_to_time){
    		return "签到范围设置不正确";
    	}
    	if($sign_from_time > $s_time || $s_time > $sign_to_time){
    		return "签到范围设置不正确";
    	}
    	$s_date = date("Y-m-d 00:00:00",$s_time);
    	$e_date = date("Y-m-d 00:00:00",$e_time);
    	$sign_from_date = date("Y-m-d 00:00:00",$sign_from_time);
    	$sign_to_date = date("Y-m-d 00:00:00",$sign_to_time);
    	if($s_date != $e_date || $s_date != $sign_from_date || $s_date != $sign_to_date){
    		return "开始结束时间以及签到范围必须在同一天";
    	}
    	/* $end_range = C('MeetingConfig.end_range');
    	$e_range_time = strtotime("$start_time+" . $end_range);
    	if($e_time <= $e_range_time){
    		return "所设置的开始结束时间间隔太短,至少在" . date("Y-m-d H:i:s", $e_range_time) . '之后';
    	} */
    	$exist_meet = self::existMeeting($s_time, $meeting['meeting_id']);
    	if(!empty($exist_meet)){
    		return "设置的开始时间已存在一场会议,目前不允许一天多场";
    	}
    	if(!empty($add_members) && count($add_members) > 0){
    		$condition_array = array();
    		$condition_array['meeting_id'] = array('eq', $meeting['meeting_id']);
    		$in_str = '';
    		foreach($add_members as $element) {
    			$in_str = $in_str . $element['user_id'] . ',';
    		}
    		$in_str = substr($in_str,0,strlen($in_str)-1);
    		$condition_array['employee_code'] = array('in', $in_str);
    		$exist_count =  M('meeting_member','','DB_system')->where($condition_array)->count();
    		if($exist_count > 0){
    			return "添加的员工已经存在";
    		}
    	}
    }
    
    
    
    
    
    
    
    /**
     * Description: 填充EXCEL表头TITLE
     * @version 2016年9月2日 下午3:05:49
     * @author lin.yujiao@whaley.cn
     * @param array $table_header_array
     * @param string $title
     */
    private function fillExcelHeader($table_header_array,$title){
    	$objPHPExcel = new  \PHPExcel();
    	$objPHPExcel->setActiveSheetIndex(0);
    	$objPHPExcel->getActiveSheet()->setTitle($title);
    	//表头字母
    	$header_letter_array = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X');
    	//设置表头(第一行)
    	$row_index = 1;
    	foreach($header_letter_array as $header_letter){
    		$table_row_index = $row_index - 1;
    		$objPHPExcel->getActiveSheet()->setCellValue($header_letter.strval(1),
    				$table_header_array[$table_row_index]);
    		$objPHPExcel->getActiveSheet()->getStyle($header_letter.strval(1))->getFont()->setBold(true);
    		$row_index = $row_index + 1;
    	}
    	return $objPHPExcel;
    }
    /**
     * Description: 会议记录EXCEL
     * @version 2016年9月2日 下午2:57:39
     * @author lin.yujiao@whaley.cn
     * @param $params_en
     */
    public static function meetingRecordExcel($meeting_id){
    	Vendor('phpExcel.PHPExcel');
    	//表头设置start
    	$table_header_array = array(
    		'员工编码','姓名','签到状态','签到时间','签退状态','签退时间','来源'
    	);
    	//表头设置end
    	$objPHPExcel = self::fillExcelHeader($table_header_array, '导出会议记录');
    	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    	$lst_meeting_en = self::querymembersByMeetingId($meeting_id);
    	$opt_in_sts = CommonBiz::getOptions("MEETING_SIGN_IN");
    	$opt_off_sts = CommonBiz::getOptions("MEETING_SIGN_OFF");
    	$opt_met_tpy = CommonBiz::getOptions("MEETING_FROM_TYPE");
    	// 	 return  M('')->getLastSql();
    	//xls数据拼接start
    	//行号，从第二行起
    	$row_num = 2;
    	foreach($lst_meeting_en as $member_en){
	    	//员工编码
	    	$objPHPExcel->getActiveSheet()->setCellValue('A'.strval($row_num),$member_en['employee_code']);
	    	//姓名
	    	$objPHPExcel->getActiveSheet()->setCellValue('B'.strval($row_num),$member_en['employee_name']);
	    	//签入状态
	    	$objPHPExcel->getActiveSheet()->setCellValue('C'.strval($row_num),$opt_in_sts[$member_en['sign_in_sts']]);
	    	//签入时间
	    	$objPHPExcel->getActiveSheet()->setCellValue('D'.strval($row_num),$member_en['sign_in_time']);
	    	//签退状态
	    	$objPHPExcel->getActiveSheet()->setCellValue('E'.strval($row_num),$opt_off_sts[$member_en['sign_off_sts']]);
	    	//签退时间
	    	$objPHPExcel->getActiveSheet()->setCellValue('F'.strval($row_num),$member_en['sign_off_time']);
	    	//来源
	    	$objPHPExcel->getActiveSheet()->setCellValue('G'.strval($row_num),$opt_met_tpy[$member_en['from_type']]);
	    	$row_num = $row_num + 1;
    	}
    	//xls数据拼接end
    	return $objPHPExcel;
    }
    
    
} 