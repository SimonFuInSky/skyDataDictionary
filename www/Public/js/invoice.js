
DownInvoicePage = {
		'BindEvent': function(){
			$('#downloadPdf').bind('click', function(){ 
				
				var telephone = $('#telephone').val();
				var thirdOrderId = $('#third_order_id').val();
				
				if(telephone ==''){
					alert('请输入联系方式');
					return;
				}
				if(thirdOrderId ==''){
					alert('订单编号请输入');
					return;
				}
				
				var result = InvoiceService.DownInvoicePdf(telephone, thirdOrderId);
				if(result.Result.flag == 1){
					window.open(result.Result.message);		
				}
				else{
					alert('失败原因:' + result.Result.message);
				}
				
			});
		}
};

$(document).ready(function(){
	DownInvoicePage.BindEvent();
});


