GroupPage = {
		'BindEvent': function(){
			$('#btn_search').bind('click', function(){
				var group_name = $('#group_name').val();
				var group_id = $('#group_id').val();
				var result = AuthService.groupquery(group_name, group_id);
				GroupPage.BindTable(result.Result);
			});
		},
		'BindTable': function(result){
			lst_group = result.datas;
			
			var html = '';
			for(var index in lst_group){
				var group = lst_group[index];
				var tr_html = "<tr>";
				tr_html += "<td>" + group.group_name+"</td>";
				tr_html += "<td>" + group.group_desc+"</td>";
				tr_html += "<td>" + group.group_id+"</td>";
				tr_html += "<td>"+"<a style='color:blue;' href='/auth/groupdetail?group_id="+ group.group_id +"  '>查看详情</a></td>"
				tr_html += "</tr>";
				html += tr_html;
			} 
			$('#search_result tbody').html('');
			$('#search_result tbody').append(html);
			
		}
};

$(document).ready(function(){
	GroupPage.BindEvent();
});


