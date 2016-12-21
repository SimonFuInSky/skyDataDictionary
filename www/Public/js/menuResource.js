/**
 * 
 */

menuResourcePage = {
		'BindEvent': function(){
			$('#btn_search').bind('click', function(){
				var menu_id = $('#menu_id').val();
				var menu_name = $('#menu_name').val();
				var menu_domain = $('#menu_domain').val();
				var parent_id = $('#parent_id').val();

				var level = $('#level').val();
				var result = AuthService.menuResourcequery(menu_id, menu_name,menu_domain,level,parent_id);
				menuResourcePage.BindTable(result.Result);
			});
		},
		'BindTable': function(result){
			lst_domain = result.datas;
			var html = '';
			for(var index in lst_domain){
				var domain = lst_domain[index];
				var tr_html = "<tr>";
				tr_html += "<td>" + domain.menu_id+"</td>";
				tr_html += "<td>" + domain.menu_name+"</td>";
				tr_html += "<td>" + domain.menu_domain + "</td>"
				tr_html += "<td>" + domain.menu_domain_desc + "</td>"
				tr_html += "<td>" + domain.menu_url + "</td>"
				tr_html += "<td>" + domain.parent_id + "</td>"
				tr_html += "<td>" + domain.parent_name + "</td>"
				tr_html += "<td>" + domain.level_text + "</td>"
				tr_html += "<td>" + domain.default_url + "</td>"
				tr_html += "<td>" + domain.menu_display_text + "</td>"
				tr_html += "<td>" + "<a style='color:blue;' href='/auth/menuresourcedetail?menu_id="+ domain.menu_id + "'>查看详情</a></td>"
				tr_html += "</tr>";
				html += tr_html;
			} 
			$('#search_result tbody').html('');
			$('#search_result tbody').append(html);
		}
};

$(document).ready(function(){
	menuResourcePage.BindEvent();
});


