<?php
return array(


	//'配置项'=>'配置值'
	'WeChatConfig'	=> array(
		'corpid'	=> 'wx1d6e80e9d4595040',
		'secret'	=> 'RniZ0mwcxGBswBm9UqsM8--gNloqogUcOo68jxbRe5pxd5NQ4w52WMUh2f1TjSgp',
		'meeting_longitude' => '121.628430', 
		'meeting_latitude' => '31.205080', 
	),
	'MeetingConfig' => array(
		'tag_name' => '测试会议',
		'start_range' => '120 minute',
		'end_range' => '0 minute',
	),
	//下拉框配置
	'OPTIONS' => array(
			//会议签到状态
			'MEETING_SIGN_IN'	=> array('NONE'=>'未签到','NORMAL'=>'已签到','LATE'=>'迟到'),
			//会议签出状态
			'MEETING_SIGN_OFF' => array('NONE'=>'未签退','NORMAL'=>'已签退'),
			'MEETING_FROM_TYPE' => array('TAG'=>'标签','TEMP'=>'临时'),
			'NOTFOUND'	=> 'OPTIONS NOT FOUND',
	),
);