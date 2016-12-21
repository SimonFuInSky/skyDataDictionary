<?php
namespace Home\Biz;

/**
 *
 * @author wanghui
 *        
 */
class JobinfoBiz {
	
	/**
	 * 查看job信息
	 * @param unknown $job_name
	 * @param unknown $job_desc
	 */
	public static function queryJobinfoByNmae($job_name, $job_desc) {
		
		$con = array();
		if(!empty($job_name)){
			array_push($con, "job_name ='$job_name'");
		}
		if(!empty($job_desc)){
			array_push($con, "job_desc = '$job_desc'");
		}
		
		$lst_domains = M ( 'job_info','','DB_system')->field()->where (join(" AND ",$con) )->select ();
		
		$resultMap = array('datas'=>$lst_domains);
		return $resultMap;
	}
} 