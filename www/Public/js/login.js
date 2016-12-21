/**
 * 
 */




LoginPage = {
		'BindEvent': function(){
			$('#btnSubmit').bind('click', function(){
				var user_id = $('#txtUserId').val();
				var pwd = $('#txtPwd').val();
				var result = AdminService.Login(user_id, pwd);
				if(result.IsSuccess == 1){
					  window.location.href =  ServerConfig.AdminConfig.jump_page;
				}
				else{
					alert('登录失败:' + result.ErrorMsg);
				}
			});
			
			$('#logout').bind('click', function() {
				var user_id = $('#user_id').val();
				AdminService.Logout(user_id);
				window.location.href = ServerConfig.AdminConfig.login_page;
			});
			$('#shouPage').bind('click', function(){
				window.location.href = ServerConfig.AdminConfig.jump_page;
			});
		}
};


$(document).ready(function(){
	LoginPage.BindEvent();
});


