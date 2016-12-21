
menugrouprelationPage = {
		'BindEvent': function(){
			$('#btn_menu_search').bind('click', function(){
				var menu_id = $('#menu_id').val();
				var menu_name = $('#menu_name').val();
				var menu_domain = $('#menu_domain').val();
				var parent_id = $('#parent_id').val();
				
				var level = $('#level').val();
				var result = AuthService.menuResourcequery(menu_id, menu_name,menu_domain,level,parent_id);
				menugrouprelationPage.BindTable(result.Result);
			});
			
			$('#btn_menu_add').bind('click', function(){
				var chk_menu_id =[]; 
				var chk_menu_name =[]; 
				$('input[name="checkOneBox"]:checked').each(function(){ 
					chk_menu_id.push($(this).attr('menu_id')); 
					chk_menu_name.push($(this).attr('menu_name'));
				}); 
				
				parent.document.getElementById('group_menu_relation_text').value = chk_menu_name;
				parent.document.getElementById('hidden_menu_relation_text').value = chk_menu_id;

				var index = parent.layer.getFrameIndex(window.name); //获取当前窗体索引
			    parent.layer.close(index); //执行关闭
				
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
			
		},
		'BindTable': function(result){
			lst_domain = result.datas;
			var html = '';
			for(var index in lst_domain){
				var domain = lst_domain[index];
				var tr_html = "<tr>";
				tr_html += "<td>" + "<input type='checkbox' level="+domain.level+" parent_id='"+domain.parent_id+"' menu_id='"+domain.menu_id+"' menu_name='" + domain.menu_name + "' style='position:relative;width: 50px; opacity: 100;' name='checkOneBox'>"+"</td>";
				tr_html += "<td>" + domain.menu_name+"</td>";
				tr_html += "<td>" + domain.menu_domain + "</td>"
				tr_html += "<td>" + domain.menu_domain_desc + "</td>"
				tr_html += "<td>" + domain.menu_url + "</td>"
				tr_html += "<td>" + domain.level + "</td>"
				tr_html += "</tr>";
				html += tr_html;
			} 
			$('#search_result tbody').html('');
			$('#search_result tbody').append(html);
			$('#search_result tbody input').bind("click", function(){
				var parent_id = $(this).attr("parent_id");
				var menu_id = $(this).attr("menu_id");
				var level = $(this).attr("level");
				if(this.checked && level==2){
					
				$("input[menu_id='"+parent_id+"']").each(function(){
				    	this.checked =true;
				 }); 
				}
				if(level==1){
				 if(this.checked){
					 $("input[parent_id='"+menu_id+"']").each(function(){
					    	this.checked =true;
					 }); 
				 }else{
					 $("input[parent_id='"+menu_id+"']").each(function(){
					    	this.checked =false;
					 }); 
				 }
				}
			});
			
		} 
};

$(document).ready(function(){
	menugrouprelationPage.BindEvent();
});


