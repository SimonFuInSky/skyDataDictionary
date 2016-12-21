<?php
namespace Home\Controller;
use Think\Controller;

/**
 * @author Simon
 *
 */
class BaseController extends Controller {
 
	function _initialize(){
		$ServerConfig['AdminConfig'] = C('AdminConfig');
		$ServerConfig['OrderConfig'] = C('OrderConfig');
		$this->assign('ServerConfig', json_encode($ServerConfig));
	}
}