/**
 * 
 */

joninfo = {
		'BindEvent': function(){
			$('#btn_search').bind('click', function(){
				var job_name = $('#job_name').val();
				var job_desc = $('#job_desc').val();
				var result = jobinfoService.jobinfoquery(job_name, job_desc);
				joninfo.BindTable(result.Result);
			});
		},
		'BindTable': function(result){
			lst_domain = result.datas;
			var html = '';
			for(var index in lst_domain){
				var domain = lst_domain[index];
				var tr_html = "<tr>";
				tr_html += "<td class='taskDesc'> <span class='in-progress'>" + domain.job_name+"</span></td>";
				tr_html += "<td class='taskDesc'> <span class='in-progress'>" + domain.job_desc+"</span></td>";
				tr_html += "<td class='taskDesc'> <span class='in-progress'>" + domain.last_execute_start_date + "</span></td>"
				tr_html += "<td class='taskDesc'> <span class='in-progress'>" + domain.last_execute_end_date + "</span></td>"
				tr_html += "<td class='taskDesc'> <span class='in-progress'>" + domain.last_execute_time + "</span></td>"
				tr_html += "<td class='taskDesc'> <span class='in-progress'>" + domain.execute_status + "</span></td>"
				tr_html += "<td class='taskDesc'> <span class='in-progress'>" + domain.last_key_time + "</span></td>"
				tr_html += "<td class='taskOptions'> <span class='in-progress'>" + "<a style='color:blue;' href='/jobinfo/queryjobDetailByjobName?job_name="+ domain.job_name + "'>详情</a></span></td>"
				tr_html += "</tr>";
				html += tr_html;
			} 
			$('#search_result tbody').html('');
			$('#search_result tbody').append(html);
		}
};

$(document).ready(function(){
	joninfo.BindEvent();
});


