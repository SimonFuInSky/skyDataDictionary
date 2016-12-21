<?php
namespace Home\Controller;
use Think\Controller;
class JobinfoController extends  LoginBaseController {
	
    public function jobinfoquery(){
    	$this->display('jobinfo/list');
    }
    
    public function queryjobDetailByjobName(){
    	$job_name = I('job_name');
    	$job_detail = M('job_detail','','DB_system')->where("job_name='$job_name'")->select();
    	$this->assign('job_detail', $job_detail);
    	$this->display('jobinfo/jobdetailList');
    }
  
}