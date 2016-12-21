/**
 * 
 */




UserInfoPage = {
		'BindEvent': function(){
			$('#btn_user_search').bind('click', function(){
				var user_name = $('#user_name').val();
				var user_nick = $('#user_nick').val();
				var user_mobile = $('#user_mobile').val();
				var user_id = $('#user_uuid').val();
				var result = AuthService.userquery(user_name, user_nick,user_mobile,user_id);
				UserInfoPage.BindTable(result.Result);
			});
		},
		'BindTable': function(result){
			lst_user = result.datas;
			
			var html = '';
			for(var index in lst_user){
				var user = lst_user[index];
				var tr_html = "<tr>";
				tr_html += "<td>" + user.user_id+"</td>";
				tr_html += "<td>" + user.user_name+"</td>";
				tr_html += "<td>" + user.user_nick + "</td>"
				tr_html += "<td>" + user.user_mobile + "</td>"
				tr_html += "<td>" + user.user_address + "</td>"
				tr_html += "<td>"+"<a style='color:blue;' href='/auth/userdetail?user_id="+ user.user_id + "'>查看详情</a></td>"
				tr_html += "</tr>";
				html += tr_html;
			} 
			$('#search_result tbody').html('');
			$('#search_result tbody').append(html);
			
		}
};


$(document).ready(function(){
	UserInfoPage.BindEvent();
});


