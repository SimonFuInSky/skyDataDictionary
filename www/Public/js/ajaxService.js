/**
 * 所有的ajax服务调用
 */

AdminService = {
	'Login' : function(user_id, pwd) {
		// 登陆功能
		var lst_param = new Array();
		lst_param.push('user_id=' + user_id);
		lst_param.push('password=' + pwd);
		var str_param = lst_param.join('&');
		var result = null;
		
		$.ajax({
			type : 'post',
			url : '/AjaxService/center/admin/login',
			data : str_param,
			dataType : 'json',
			async : false,
			success : function(data) {
				result = data;
			},
        	  error : function(e, err_name, err_text) {
        		  result = {};
        		  result.IsSuccess = 0;
        		  result.ErrorMsg = err_text;
        		  result.ErrorCode = 4;
        	  }
		});
		return result;
	},
	'Logout' : function(user_id, pwd) {
		// 登陆功能
		var lst_param = new Array();
		lst_param.push('user_id=' + user_id);
		var str_param = lst_param.join('&');
		var result = null;
		
		$.ajax({
			type : 'post',
			url : '/AjaxService/center/admin/logout',
			data : str_param,
			dataType : 'json',
			async : false,
			success : function(data) {
				result = data;
			},
        	  error : function(e, err_name, err_text) {
        		  result = {};
        		  result.IsSuccess = 0;
        		  result.ErrorMsg = err_text;
        		  result.ErrorCode = 4;
        	  }
		});
		return result;
	}
};

OrderService = {

};

InvoiceService = {

		'DownInvoicePdf' : function(telephone, thirdOrderId) {
			// 登陆功能
			var lst_param = new Array();
			lst_param.push('telephone=' + telephone);
			lst_param.push('thirdOrderId=' + thirdOrderId);
			var str_param = lst_param.join('&');
			
			var result = null;
			
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/invoice/downPdf',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	        	  error : function(e, err_name, err_text) {
	        		  result = {};
	        		  result.IsSuccess = 0;
	        		  result.ErrorMsg = err_text;
	        		  result.ErrorCode = 4;
	        	  }
			});
			return result;
		}

};

AuthService = {
	       'domainquery' : function(domain_name,domain_url,domain_id) {
			// 登陆功能
			var lst_param = new Array();
			
			lst_param.push('domain_name=' + domain_name);
			lst_param.push('domain_url=' + domain_url);
			lst_param.push('domain_id=' + domain_id);
			var str_param = lst_param.join('&');
			var result = null;
			
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/domainquery',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	        	  error : function(e, err_name, err_text) {
	        		  result = {};
	        		  result.IsSuccess = 0;
	        		  result.ErrorMsg = err_text;
	        		  result.ErrorCode = 4;
	        	  }
			});
			return result;
		},'adddomain' : function(domain_name,domain_url,default_url) {
			// 登陆功能
			var lst_param = new Array();
			
			lst_param.push('domain_name=' + domain_name);
			lst_param.push('domain_url=' + domain_url);
			lst_param.push('default_url=' + default_url);
			var str_param = lst_param.join('&');
			var result = null;
			
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/adddomain',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	        	  error : function(e, err_name, err_text) {
	        		  result = {};
	        		  result.IsSuccess = 0;
	        		  result.ErrorMsg = err_text;
	        		  result.ErrorCode = 4;
	        	  }
			});
			return result;
		},'updateDomain' : function(domain_id,domain_name,domain_url,default_url) {
			// 登陆功能
			var lst_param = new Array();
			lst_param.push('domain_id=' + domain_id);
			lst_param.push('domain_name=' + domain_name);
			lst_param.push('domain_url=' + domain_url);
			lst_param.push('default_url=' + default_url);
			var str_param = lst_param.join('&');
			var result = null;
			
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/updatedomain',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	        	  error : function(e, err_name, err_text) {
	        		  result = {};
	        		  result.IsSuccess = 0;
	        		  result.ErrorMsg = err_text;
	        		  result.ErrorCode = 4;
	        	  }
			});
			return result;
		},
		
		'resetPassword' : function(uname,originalpass,newpass,confirmpass) {
			var lst_param = new Array();
			lst_param.push('uname=' + uname);
			lst_param.push('originalpass=' + originalpass);
			lst_param.push('newpass=' + newpass);
			lst_param.push('confirmpass=' + confirmpass);
			var str_param = lst_param.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/resetpassword',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	        	  error : function(e, err_name, err_text) {
	        		  result = {};
	        		  result.IsSuccess = 0;
	        		  result.ErrorMsg = err_text;
	        		  result.ErrorCode = 4;
	        	  }
			});
			return result;
		},
		
		'menuResourcequery':function(menu_id,menu_name,menu_domain,level,parent_id) {
			var lst_param = new Array();
			lst_param.push('menu_id=' + menu_id);
			lst_param.push('menu_name=' + menu_name);
			lst_param.push('menu_domain=' + menu_domain);
			lst_param.push('level=' + level);
			lst_param.push('parent_id=' + parent_id);

			var str_param = lst_param.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/menuresourcequery',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	        	  error : function(e, err_name, err_text) {
	        		  result = {};
	        		  result.IsSuccess = 0;
	        		  result.ErrorMsg = err_text;
	        		  result.ErrorCode = 4;
	        	  }
			});
			return result;
		},
		'UpdateMenuResource': function(menu_id,menu_name,menu_url,menu_display){
			var lst_sku = new Array();
			lst_sku.push('menu_id=' 	+ menu_id);
			lst_sku.push('menu_name=' 	+ menu_name);
			lst_sku.push('menu_url=' 	+ menu_url);
			lst_sku.push('menu_display=' 	+ menu_display);
			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/updatemenuresource',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'AddMenuResource': function(menu_name,menu_domain,menu_url,parent_id,level,menu_display){
			var lst_sku = new Array();
			lst_sku.push('menu_name=' 	+ menu_name);
			lst_sku.push('menu_domain=' 	+ menu_domain);
			lst_sku.push('menu_url=' 	+ menu_url);
			lst_sku.push('parent_id=' 	+ parent_id);
			lst_sku.push('level=' 	+ level);
			lst_sku.push('menu_display=' 	+ menu_display);
			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/addmenuresource',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'groupquery': function(group_name,group_id){
			
			var lst_sku = new Array();
			lst_sku.push('group_name=' 	+ group_name);
			lst_sku.push('group_id=' 	+ group_id);
 
			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/groupquery',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'userquery': function(user_name, user_nick,user_mobile,user_id){
			var lst_sku = new Array();
			lst_sku.push('user_name=' 	+ user_name);
			lst_sku.push('user_nick=' 	+ user_nick);
			lst_sku.push('user_mobile=' 	+ user_mobile);
			lst_sku.push('user_id=' 	+ user_id);

			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/userquery',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'addgroup': function(group_name,group_desc,menu_relation,user_relation){
			var lst_sku = new Array();
			lst_sku.push('group_name=' 	+ group_name);
			lst_sku.push('group_desc=' 	+ group_desc);
			lst_sku.push('menu_relation=' 	+ menu_relation);
			lst_sku.push('user_relation=' 	+ user_relation);

			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/addgroup',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'removeUserRelation': function(relatn_id){
			var lst_sku = new Array();
			lst_sku.push('relatn_id=' 	+ relatn_id);
			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/removeUserRelation',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'removeMenuRelation': function(relatn_id){
			var lst_sku = new Array();
			lst_sku.push('relatn_id=' 	+ relatn_id);
			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/removeMenuRelation',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'addToGroupRelationMenu': function(group_id,menu_relation){
			var lst_sku = new Array();
			lst_sku.push('group_id=' 	+ group_id);
			lst_sku.push('menu_relation=' 	+ menu_relation);

			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/addToGroupRelationMenu',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'addToGroupRelationUser': function(group_id,user_relation){
			var lst_sku = new Array();
			lst_sku.push('group_id=' 	+ group_id);
			lst_sku.push('user_relation=' 	+ user_relation);
			
			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/addToGroupRelationUser',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'addUser': function(user_name,user_nick,user_mobile,password,user_address){
			var lst_sku = new Array();
			lst_sku.push('user_name=' 	+ user_name);
			lst_sku.push('user_nick=' 	+ user_nick);
			lst_sku.push('user_mobile=' 	+ user_mobile);
			lst_sku.push('password=' 	+ password);
			lst_sku.push('user_address=' 	+ user_address);

			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/addUser',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'updateUser': function(user_id,user_name,user_nick,user_mobile,user_address,password){
			var lst_sku = new Array();
			lst_sku.push('user_id=' 	+ user_id);
			lst_sku.push('user_name=' 	+ user_name);
			lst_sku.push('user_nick=' 	+ user_nick);
			lst_sku.push('user_mobile=' 	+ user_mobile);
			lst_sku.push('password=' 	+ password);
			lst_sku.push('user_address=' 	+ user_address);

			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/updateUser',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'userrulequery': function(user_name){
			var lst_sku = new Array();
			lst_sku.push('user_name=' 	+ user_name);

			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/userrulequery',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'deleteUserRule': function(rule_id){
			var lst_sku = new Array();
			lst_sku.push('rule_id=' 	+ rule_id);

			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/deleteUserRule',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'addUserRuleSigle': function(user_name,hidden_menu_relation_text){
			var lst_sku = new Array();
			lst_sku.push('user_name=' 	+ user_name);
			lst_sku.push('lst_menu=' 	+ hidden_menu_relation_text);

			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/auth/addUserRuleSingle',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		}
		

};

CityListService = {
		'CityList': function(province_id,city_name,page_index, page_size){
			var lst_city = new Array();
			lst_city.push('province_id=' 	+ province_id);
			lst_city.push('city_name=' 	+ city_name);
			lst_city.push('page_index=' 	+ page_index);
			lst_city.push('page_size=' 	+ page_size);
			var str_param = lst_city.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/city/citylist',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'AddCity': function(province_id,city_name){
			var lst_city = new Array();
			lst_city.push('province_id=' 	+ province_id);
			lst_city.push('city_name=' 	+ city_name);
			var str_city = lst_city.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/city/addcity',
				data : str_city,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'UpdateCity': function(city_id,city_name){
			var lst_sku = new Array();
			lst_sku.push('city_id=' 	+ city_id);
			lst_sku.push('city_name=' 	+ city_name);
			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/city/updatecity',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
};



DistrictListService = {
		'DistrictList': function(province_id,city_name,district_name,page_index, page_size){
			var lst_city = new Array();
			lst_city.push('province_id=' 	+ province_id);
			lst_city.push('city_name=' 	+ city_name);
			lst_city.push('district_name=' 	+ district_name);
			lst_city.push('page_index=' 	+ page_index);
			lst_city.push('page_size=' 	+ page_size);
			var str_param = lst_city.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/city/districtlist',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'AddDistrict': function(city_id,district_name){
			var lst_city = new Array();
			lst_city.push('city_id=' 	+ city_id);
			lst_city.push('district_name=' 	+ district_name);
			var str_city = lst_city.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/city/adddistrict',
				data : str_city,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
		'UpdateDistrict': function(district_id,district_name){
			var lst_sku = new Array();
			lst_sku.push('district_id=' 	+ district_id);
			lst_sku.push('district_name=' 	+ district_name);
			var str_sku = lst_sku.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/city/updatedistrict',
				data : str_sku,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	      	  error : function(e, err_name, err_text) {
	    		  result = {};
	    		  result.IsSuccess = 0;
	    		  result.ErrorMsg = err_text;
	    		  result.ErrorCode = 4;
	    	  }
			});
			return result;
		},
};

jobinfoService = {
		'jobinfoquery':function(job_name,job_desc) {
			var lst_param = new Array();
			lst_param.push('job_name=' + job_name);
			lst_param.push('job_desc=' + job_desc);
			var str_param = lst_param.join('&');
			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/jobinfo/jobinfoquery',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;
				},
	        	  error : function(e, err_name, err_text) {
	        		  result = {};
	        		  result.IsSuccess = 0;
	        		  result.ErrorMsg = err_text;
	        		  result.ErrorCode = 4;
	        	  }
			});
			return result;
		}
};
CommonService = {
		'getAllProvince': function(){
			var lst_param = new Array();
			var str_param = lst_param.join('&');
			var result = null;

			$.ajax({
				type : 'post',
				url : '/AjaxService/center/common/getAllProvince',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;

				},
				error : function(e, err_name, err_text) {
					result = {};
					result.IsSuccess = 0;
					result.ErrorMsg = err_text;
					result.ErrorCode = 4;
				}
			});
			return result;
		},
		'getCityListByProvinceId': function(province_id){
			// 登陆功能
			var lst_param = new Array();
			lst_param.push('province_id=' + province_id);
			var str_param = lst_param.join('&');


			var result = null;

			$.ajax({
				type : 'post',
				url : '/AjaxService/center/common/getCityListByProvinceId',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;

				},
				error : function(e, err_name, err_text) {
					result = {};
					result.IsSuccess = 0;
					result.ErrorMsg = err_text;
					result.ErrorCode = 4;
				}
			});
			return result;
		},
		'getDistrictListByCityId': function(city_id){
			// 登陆功能
			var lst_param = new Array();
			lst_param.push('city_id=' + city_id);
			var str_param = lst_param.join('&');

			var result = null;
			$.ajax({
				type : 'post',
				url : '/AjaxService/center/common/getDistrictListByCityId',
				data : str_param,
				dataType : 'json',
				async : false,
				success : function(data) {
					result = data;

				},
				error : function(e, err_name, err_text) {
					result = {};
					result.IsSuccess = 0;
					result.ErrorMsg = err_text;
					result.ErrorCode = 4;
				}
			});
			return result;
		},
	};
