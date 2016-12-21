/**
 * 
 */




UserRulePage = {
		'BindEvent': function(){
			$('#btn_search').bind('click', function(){
				//domain 名称
				var user_name = $('#user_name').val();
				
				var result = AuthService.userrulequery(user_name);
				
				UserRulePage.BindTable(result.Result);
			});
			
			$('#btn_add').bind('click', function(){
				//domain 名称
				var user_name = $('#user_name').val();
				
				if(user_name==''){
					alert("新增权限用户不能为空");
					return ;
				}
				
				layer.open({
			        type: 2,
			        title: '新增页面关系',
			        fix: true,
			        shadeClose: true,
			        maxmin: true,
			        area: ['1000px', '500px'],
			        content: '/auth/menugrouprelation',
			        end: function(){
			            layer.tips('绑定成功', '#photosDemo', {tips: 1})
			        }
			    });
				
				
			});
			
			$('#btn_submit').bind('click', function(){
				//domain 名称
				var user_name = $('#user_name').val();
				
				if(user_name==''){
					alert("新增权限用户不能为空");
					return ;
				}
				
				var hidden_menu_relation_text = $('#hidden_menu_relation_text').val();

				if(hidden_menu_relation_text==''){
					alert("选择需要添加页面的权限");
					return ;
				}
				
				var result = AuthService.addUserRuleSigle(user_name,hidden_menu_relation_text);
				
				if(result.IsSuccess == 1){
					alert("添加成功");
					$('#btn_search').trigger("click");
				}
				else{
					alert('添加失败:' + result.ErrorMsg);
				}
				
				
			});
		},
		'BindTable': function(result){
			lst_user_rule = result.datas;
			
			var html = '';
			for(var index in lst_user_rule){
				var user_rule = lst_user_rule[index];
				var tr_html = "<tr>";
				tr_html += "<td>" +  user_rule.user_name+"</td>";
				tr_html += "<td>" + user_rule.menu_name+"</td>";
				tr_html += "<td>" + UserRulePage.ConvertMenuLevel(user_rule.level) + "</td>"
				tr_html += "<td>" + user_rule.menu_domain_desc + "</td>"
				tr_html += "<td>" + '<a href="javascript:UserRulePage.deleteUserRelation('+"'"+user_rule.rule_id+"'"+')">'+"删除</a>"+ "</td>"
				tr_html += "</tr>";
				html += tr_html;
			} 
			$('#search_result tbody').html('');
			$('#search_result tbody').append(html);
			
		},
		'ConvertMenuLevel':function(obj){
			if(obj == '1'){
				return '一级菜单';
			}  
			return '二级菜单';
		},
		'deleteUserRelation':function(obj){
			var result = AuthService.deleteUserRule(obj);
			
			alert("删除成功");
			
			$('#btn_search').trigger("click");

		}
		
};


$(document).ready(function(){
	UserRulePage.BindEvent();
});


