
CommonPage = {
		'BindEvent': function(){
			$('#logout').bind('click', function(){
				var user_id = $('#user_id').val();
				AdminService.Logout(user_id);
				window.location.href =  ServerConfig.AdminConfig.login_page;
			});
		} ,'convertNull' : function (str) { //共通转空操作
	        if (str) return str;
	        return "";
	    },'SetPageIndex': function (total_page, total_count, page_index) {//共通填充分页页脚
	        $('#total_count').text(total_count);
	        $('#total_page').text(total_page);
	        $('#curr_page_index').text(page_index);
	        page_index = parseInt(page_index);
	        if (page_index == 1) {
	            $('#paging .first').addClass('ui-state-disabled');
	            $('#paging .previous').addClass('ui-state-disabled');
	            $('#paging .first').attr('page_index', 1);
	            $('#paging .previous').attr('page_index', 1);
	        }
	        else {
	            $('#paging .first').removeClass('ui-state-disabled');
	            $('#paging .previous').removeClass('ui-state-disabled');
	            $('#paging .first').attr('page_index', 1);
	            $('#paging .previous').attr('page_index', page_index - 1);
	        }
	        if (total_page == page_index) {
	            $('#paging .last').addClass('ui-state-disabled');
	            $('#paging .next').addClass('ui-state-disabled');
	            $('#paging .last').attr('page_index', total_page);
	            $('#paging .next').attr('page_index', total_page);
	        }
	        else {
	            $('#paging .last').removeClass('ui-state-disabled');
	            $('#paging .next').removeClass('ui-state-disabled');
	            $('#paging .last').attr('page_index', total_page);
	            $('#paging .next').attr('page_index', page_index + 1);
	        }
	    }
};
//省市区控件 调用对象为省级select 传入对象为市,区以及回调(初始化)参数
(function($) {
	_commonRegion = function(input,options){
		//省市区及回调函数
		var defaults = {
			province : $(input),
			city : $('#ssl_city'),
			district : $('#ssl_district'),
			option_mode : 'ID'  //可以选择ID或者NAME
		};
		defaults = $.extend(defaults , options);
		this._create =  function(){
			var province_key = (defaults.option_mode == 'ID')?'province_id':'province_name';
			var city_key = (defaults.option_mode == 'ID')?'city_id':'city_name';
			var district_key = (defaults.option_mode == 'ID')?'district_id':'district_name';
			this._bindProvince(province_key);
			this._bindCity(city_key, defaults.province.find("option:selected").attr("text"));
			this._bindDistrict(district_key, defaults.city.find("option:selected").attr("text"));
			defaults.province.bind('change', function() {
				province.commonRegion._bindCity(city_key, defaults.province.find("option:selected").attr("text"));
			});
			defaults.city.bind('change', function() {
				province.commonRegion._bindDistrict(district_key, defaults.city.find("option:selected").attr("text"));
			});
			if(defaults.callback) defaults.callback();
		};
		this._bindProvince = function(province_key) {
			var result = CommonService.getAllProvince();
			if (result.IsSuccess == 1) {
				defaults.province.html('');
				var html = '';
				for ( var index in result.Result) {
					var province_en = result.Result[index];
					html += '<option text="' + province_en.province_id
						+ '" value="' + province_en[province_key] + '">';
					html += province_en.province_name;
					html += '</option>';
				}
				defaults.province.append(html);
				defaults.province.trigger('change');
			} else {
				alert(result.ErrorMsg);
			}
		};
		this._bindCity = function(city_key , province_id) {
			var result = CommonService.getCityListByProvinceId(province_id);
			if (result.IsSuccess == 1) {
				defaults.city.html('');
				var html = '';
				for ( var index in result.Result) {
					var city_en = result.Result[index];
					html += '<option text="' + city_en.city_id + '" value="'
						+ city_en[city_key] + '">';
					html += city_en.city_name;
					html += '</option>';
				}
				defaults.city.append(html);
				defaults.city.trigger('change');
			} else {
				alert(result.ErrorMsg);
			}
		};
		this._bindDistrict = function(district_key , city_id) {
			var result = CommonService.getDistrictListByCityId(city_id);
			if (result.IsSuccess == 1) {
				defaults.district.html('');
				var html = '';
				for ( var index in result.Result) {
					var district_en = result.Result[index];
					html += '<option text="' + district_en.district_id
						+ '" value="' + district_en[district_key] + '">';
					html += district_en.district_name;
					html += '</option>';
				}
				defaults.district.append(html);
				defaults.district.trigger('change');
			} else {
				alert(result.ErrorMsg);
			}
		};
	};
	
	$.fn.commonRegion = function(method , options){		
		if( typeof method == "string"){
			return $.fn.commonRegion.methods[method](this, options);
		}
		else{
			return $.fn.commonRegion.methods["create"](this, method);
		}
	};
	
	$.fn.commonRegion.methods = {
		create:function(ele,options){
			return ele.each(function() {
				if( this.commonRegion == undefined ){
					this.commonRegion = new _commonRegion(this,options);
					this.commonRegion._create();
				}
			});
		}
	};
})(jQuery);

$(document).ready(function(){
	CommonPage.BindEvent();
});

