/**
 * 
 */




UserGroupRelationPage = {
		'BindEvent': function(){
			$('#btn_user_search').bind('click', function(){
				var user_name = $('#user_name').val();
				var user_nick = $('#user_nick').val();
				var user_mobile = $('#user_mobile').val();
				var user_id = $('#user_id').val();
				var result = AuthService.userquery(user_name, user_nick,user_mobile,user_id);
				UserGroupRelationPage.BindTable(result.Result);
			});
			$('#allCheck').bind('click', function(){ 
				  if(this.checked==true){
						 $('input[name="checkOneBox"]').each(function(){
						    	this.checked =true;
						 }); 
					  }else{
							 $('input[name="checkOneBox"]').each(function(){
							    	this.checked =false;
							 });   
					  }
			});
			
			$('#btn_user_add').bind('click', function(){
				var chk_user_id =[]; 
				var chk_user_name =[]; 
				$('input[name="checkOneBox"]:checked').each(function(){ 
					chk_user_id.push($(this).attr('user_id')); 
					chk_user_name.push($(this).attr('user_name'));
				}); 
				
				parent.document.getElementById('group_user_relation_text').value = chk_user_name;
				parent.document.getElementById('hidden_user_relation_text').value = chk_user_id;

				var index = parent.layer.getFrameIndex(window.name); //获取当前窗体索引
			    parent.layer.close(index); //执行关闭
				
			});
		},
		'BindTable': function(result){
			lst_user = result.datas;
			
			var html = '';
			for(var index in lst_user){
				var user = lst_user[index];
				var tr_html = "<tr>";
				tr_html += "<td>" + "<input type='checkbox' user_id="+user.user_id+" user_name='"+user.user_nick+ "' style='position:relative;width: 50px; opacity: 100;' name='checkOneBox'>"+"</td>";
				tr_html += "<td>" + user.user_id+"</td>";
				tr_html += "<td>" + user.user_name+"</td>";
				tr_html += "<td>" + user.user_nick + "</td>"
				tr_html += "<td>" + user.user_mobile + "</td>"
				tr_html += "<td>" + user.user_birthday + "</td>"
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
	UserGroupRelationPage.BindEvent();
});


