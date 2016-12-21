<?php
namespace Home\Biz;
use Home\Biz\CommonBiz;
/**
 * Description: 投票主题
 * @version 2016年11月22日 下午2:21:45 
 * @author lin.yujiao@whaley.cn
 */
class VoteBiz {
	/**
	 * Description: 投票主题查询
	 * @version 2016年11月22日 下午2:22:15 
	 * @author lin.yujiao@whaley.cn
	 */
    public static function queryVoteList($vote_name, $start_time, $end_time, $pageSize, $index) {
    	//查询条件筛选部分
    	if (!empty ($vote_name)) {
    		$condition_array['vote_name'] = array('like', "%" . $vote_name . "%");
    	}
    	if (!empty ($start_time)) {
    		$condition_array['start_time'] = array('egt', $start_time);
    	}
    	if (!empty ($end_time)) {
    		$condition_array['end_time'] = array('elt', $end_time);
    	}
    	$condition_array['delete_flag'] = array('eq', 0);
    	//M方法实例化投票模型
    	$vote = M('vote_subject','','DB_system');
    	//查询
    	$list_vote_infos = $vote->where($condition_array)->page($index, $pageSize)->select();
    	// 总记录数
    	$totalCount = $vote->where($condition_array)->count();
    	//构造分页返回信息
    	$resultMap = CommonBiz::fillPageResult($list_vote_infos, $totalCount, $pageSize, $index);
    	return $resultMap;
    }
    
    
    
    /**
     * Description: 新增投票
	 * @version 2016年11月22日 下午2:22:15 
     * @author lin.yujiao@whaley.cn
     */
    public static function addVote($vote, $login_user, $add_items){
    	$vote ['vote_id'] = self::generateVoteId();
    	$vote ['create_date'] = date("y-m-d H:i:s", time());
    	$vote ['create_user'] = $login_user;
    	$vote ['is_transparent'] = 1;
    	$vote ['delete_flag'] = 0;
    	//校验
    	$msg = self::validate($vote);
    	//无拦截执行新增功能
    	if (empty($msg)) {
    		self::saveVoteItems($vote, $add_items, null, null, $login_user);
    		$success_count = M('vote_subject','','DB_system')->add($vote);
    		//更新成功 返回条数为1
    		if($success_count != 1){
    			$msg = '新增投票失败';
    		}
    	}
    	return $msg;
    }
    
    /**
     * Description: 更新投票
	 * @version 2016年11月22日 下午2:22:15 
     * @author lin.yujiao@whaley.cn
     */
    public static function updateVote($vote, $vote_id, $login_user, $add_items, $edit_items, $del_items)
    {
    	$vote ['vote_id'] = $vote_id;
    	$vote ['update_date'] = date("y-m-d H:i:s", time());
    	$vote ['update_user'] = $login_user;
    	//校验
    	$msg = self::validate($vote);
    	//无拦截执行更新功能
    	if (empty($msg)) {
    		self::saveVoteItems($vote, $add_items, $edit_items, $del_items, $login_user);
    		$success_count = M('vote_subject','','DB_system')->save($vote);
    		//更新成功 返回条数为1
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
    private function saveVoteItems($vote, $add_items, $edit_items, $del_items, $login_user){
    	//新增操作
    	if(!empty($add_items) && count($add_items) > 0){
    		$add_entitys = array();
    		foreach($add_items as $element) {
    			array_push($add_entitys, array(
    				'item_id' => self::generateVoteItemId(),
    				'vote_id' => $vote ['vote_id'],
    				'item_name' => $element['item_name'],
    				'item_count' => 0,
    				'create_user' => $login_user,
    				'create_date' => date("y-m-d H:i:s", time())
    			));
    		}
    		M('vote_items','','DB_system')->addAll($add_entitys);
    	}
    	//更新操作
    	if(!empty($edit_items) && count($edit_items) > 0){
    		foreach($edit_items as $element) {
    			$update_data = array(
    				'item_id' => $element['item_id'],
    				'item_name' => $element['item_name'],
    				'update_date'=> date("y-m-d H:i:s", time()),
    				'update_user'=> $login_user
    			);
    			M('vote_items','','DB_system')->save($update_data);
    		}
    	}
    	//删除操作
    	if(!empty($del_items) && count($del_items) > 0){
    		$del_array['item_id'] = array('IN', $del_items);
    		M('vote_items','','DB_system')->where($del_array)->delete();
    	}
    	return 0;
    }
	/**
	 * Description: 生成投票主题ID
	 * @version 2016年11月23日 下午2:13:30 
	 * @author lin.yujiao@whaley.cn
	 * @return string
	 */
    public static function generateVoteId()
    {
    	$seq = CommonBiz::getNewSeq('vote_id') + 10000000;
    	return 'VT' . $seq;
    }
   /**
    * Description: 生成投票项ID
    * @version 2016年11月23日 下午2:13:11 
    * @author lin.yujiao@whaley.cn
    * @return string
    */
    public static function generateVoteItemId()
    {
    	$seq = CommonBiz::getNewSeq('vote_item_id') + 10000000;
    	return 'VTI' . $seq;
    }
    /**
     * Description: 校验
     * @version 2016年9月12日 下午4:56:15
     * @author lin.yujiao@whaley.cn
     * @param unknown $meeting
     */
    public static function validate($vote){
    	//判空
    	if (empty($vote['vote_name'])) {
    		return "投票主题未填写";
    	} else if (empty($vote['start_time'])) {
    		return "开始时间未填写";
    	}else if(empty($vote['end_time'])){
    		return "结束时间未填写";
    	}
    	$start_time = $vote['start_time'];
    	//开始时间戳
    	$s_time = strtotime($start_time);
    	//结束时间戳
    	$e_time = strtotime($vote['end_time']);
    	if($s_time == false){
    		return "开始时间输入不合法";
    	}
    	if($e_time == false){
    		return "结束时间输入不合法";
    	}
    	if($s_time > $e_time){
    		return "开始时间不能比结束时间晚";
    	}
    	if($vote['check_box_max'] < 1){
    		return "最多可选数量不合法";
    	}
    }
    
	/**
	 * Description: 查询投票选项
	 * @version 2016年11月23日 下午3:59:59 
	 * @author lin.yujiao@whaley.cn
	 */
    public static function queryItemsByVoteid($vote_id) {
    	$lst_item_infos;
    	//查询条件筛选部分
    	if (!empty ($vote_id)) {
    		$condition_array['vote_id'] = array('eq', $vote_id);
    		//M方法实例化模型
    		$items = M('vote_items','','DB_system');
    		//查询
    		$lst_item_infos = $items->where($condition_array)->order("create_date")->select();
    	}else{
    		$lst_item_infos = array();
    	}
    	return $lst_item_infos;
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