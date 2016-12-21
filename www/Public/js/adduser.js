
AddUserPage = {
		'BindEvent': function(){
			$('#btn_user_add').bind('click', function(){
				var user_name = $('#user_name').val();
				var user_nick = $('#user_nick').val();
				var user_mobile = $('#user_mobile').val();
				var password = $('#password').val();
				var user_address = $('#user_address').val();

				
				if(user_name==''){
					alert("用户名不能为空");
					return;
				}
				
				if(password==''){
					alert("密码不能为空");
					return;
				}
				
				var result = AuthService.addUser(user_name, user_nick,user_mobile,password,user_address);
				
				if(result.IsSuccess == 1){
					alert('添加成功');				
				}
				else{
					alert('失败原因:' + result.ErrorMsg);
				}
				
			});
		}
};

$(document).ready(function(){
	AddUserPage.BindEvent();
});


