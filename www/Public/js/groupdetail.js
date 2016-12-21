GroupDetailPage= {
		'BindEvent': function(){
			$('#btn_add_resource_relation').bind('click', function(){
				
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
			
			$('#btn_add_user_relation').bind('click', function(){
				layer.open({
			        type: 2,
			        title: '新增用户关系',
			        fix: true,
			        shadeClose: true,
			        maxmin: true,
			        area: ['1000px', '500px'],
			        content: '/auth/usergrouprelation',
			        end: function(){
			            layer.tips('绑定成功', '#photosDemo', {tips: 1})
			        }
			    });
			
			});
			
			
			$('#btn_add_resource').bind('click', function(){
				/**
				 * 获取要添加的menu_id
				 */
				var group_id= $('#group_id').val();
				var menu_relation =  $('#hidden_menu_relation_text').val();
				
				if(menu_relation==''){
					alert('您没有要追加的页面');
					return ;
				}
				
				AuthService.addToGroupRelationMenu(group_id,menu_relation);
				alert("添加成功");
				window.location.reload();
			
			});
			
			$('#btn_add_user').bind('click', function(){
				/**
				 *  获取要添加 的user
				 */
				var group_id= $('#group_id').val();
				
				var user_relation =  $('#hidden_user_relation_text').val();
				
				AuthService.addToGroupRelationUser(group_id,user_relation);
				
				alert("添加成功");
				window.location.reload();

			});
		},
		'removeUserRelation':function(relatn_id){
			AuthService.removeUserRelation(relatn_id);
			alert("删除成功");
			window.location.reload();
			
		},
		'removeMenuRelation':function(relatn_id){
			AuthService.removeMenuRelation(relatn_id);
			alert("删除成功");
			window.location.reload();
		}
		
		
};

$(document).ready(function(){
	GroupDetailPage.BindEvent();
});

