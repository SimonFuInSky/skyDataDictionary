<?php
namespace Home\Biz;

class InvoiceBiz {
	/**
	 * 用户登陆
	 */
	public static function downLoadInvoice($telephone, $thirdOrderId) {
		
		$flag =0;
		$message='未找到订单';
		$order_en = M('order_info','','DB_trade')->where("consignee_cellphone = 
				'$telephone' and (third_order_id = '$thirdOrderId' 
				or order_id ='$thirdOrderId')")->find();
		
		if ($order_en!=null){
			 if($order_en['order_type']==1){
			 	$message = '微鲸官方旗舰店订单，请在天猫订单详情下载电子发票';
			 }else {
			  if($order_en['invoice_status']!=12){
				$message = '订单未完成开票';
			 }else{
			 	$order_id = $order_en['order_id'];
			 	$order_invoice_en = M('order_invoice','','DB_trade')->where("order_id ='$order_id' and status=1")->find();
			 	$flag =1;
			 	$message=$order_invoice_en['file_path'];
			 }
		   }
		}
		
		$resultMap = array (
				'flag' => $flag,
				'message' => $message,
		);
		
		return $resultMap;
	}
	
} 