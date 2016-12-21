<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	//验证当前用户是否登陆
    	$user_id = cookie('login_user_id');
    	
    	
    	if(empty($user_id)){
    		//未登录
    		redirect(C('AdminConfig.login_page'));
    		return;
    	}
    	else{
    		
    		redirect(C('AdminConfig.jump_page'));
    		return;
    	}
    }
    
    
    public function invoice(){
    	$this->display('index/invoice');
    }
    
    public function test(){
    	$local_domain = "local.admin.whaley.cn";
    	$local_domain = str_replace(".whaley.cn", "", $local_domain); 
    	$local_domain = str_replace(".whaleyinfo.cn", "", $local_domain);
    	$index = strrpos($local_domain, ".");
    	$local_domain = substr($local_domain, $index + 1);
    	echo $index;
    	echo $local_domain;
    }
    

    public function ordermap(){
        $lst_order_en = M('order_info', '', 'DB_trade')
            ->field("buyer_name,order_id,consignee_address,lng,lat")
            ->where("order_type=8 AND lng IS NOT NULL AND lng IS NOT NULL 
                AND payment_status = 1")->select();

        $payment_amount = M('order_info', '', 'DB_trade')
            ->where("order_type=8 AND lng IS NOT NULL AND lng IS NOT NULL 
                AND payment_status = 1")->sum('payment_amount');

        $goods_qty = M('order_info', '', 'DB_trade')
            ->where("order_type=8 AND lng IS NOT NULL AND lng IS NOT NULL 
                AND payment_status = 1")->sum('goods_qty');

        $lst_order_detail = M('order_info', '', 'DB_trade')->query("SELECT od.`style_id`, 
                MAX(od.`style_name`) as 'style_name',
                SUM(od.qty) as 'qty', 
                SUM(od.`payment_amount`)  as 'payment_amount'
            FROM order_info oi
                INNER JOIN order_detail od
                    ON oi.`order_id` = od.`order_id`
            WHERE oi.order_type=8 
                AND oi.lng IS NOT NULL 
                AND oi.lng IS NOT NULL 
                AND oi.payment_status = 1
          GROUP BY od.`style_id`
      ");



        $this->assign ( "goods_qty", $goods_qty);
        $this->assign ( "payment_amount", $payment_amount);
        $this->assign ( "lst_order_en", json_encode($lst_order_en));
        $this->assign ( "lst_order_detail", $lst_order_detail);
        $this->display('index/ordermap');
    }
    public function mapTest(){
        $this->display('index/mapTest');
    }

}