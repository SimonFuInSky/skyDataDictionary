/**
 * 
 */




DomainPage = {
		'BindEvent': function(){
			$('#btn_search').bind('click', function(){
				//domain 名称
				var domain_name = $('#domain_name').val();
				
				//domain 默认url
				var domain_url = $('#domain_url').val();
				//domain_id
				var domain_id = $('#domain_id').val();
				
				var result = AuthService.domainquery(domain_name, domain_url,domain_id);
				
				DomainPage.BindTable(result.Result);

				
			});
		},
		'BindTable': function(result){
			lst_domain = result.datas;
			
			var html = '';
			for(var index in lst_domain){
				var domain = lst_domain[index];
				var tr_html = "<tr>";
				tr_html += "<td>" + domain.domain_id+"</td>";
				tr_html += "<td>" + domain.domain_name+"</td>";
				tr_html += "<td>" + domain.domain_url + "</td>"
				tr_html += "<td>" + domain.default_url + "</td>"
				tr_html += "<td>"+"<a style='color:blue;' href='/auth/domaindetail?domain_id="+ domain.domain_id + "&domain_name="+domain.domain_name+"&domain_url="+domain.domain_url+"&default_url="+domain.default_url+"  '>查看详情</a></td>"
				tr_html += "</tr>";
				html += tr_html;
			} 
			$('#search_result tbody').html('');
			$('#search_result tbody').append(html);
			
		}
};


$(document).ready(function(){
	DomainPage.BindEvent();
});


