<?php
namespace Home\Controller;
use Think\Controller;
class ProvinceCityDistrictController extends  LoginBaseController {
	
	public function city(){
		$lst_province = M('province','','DB_system')->select();
		$lst_city = M('city','','DB_system')->select();
		$this->assign('lst_province', $lst_province);
		$this->assign('lst_city', $lst_city);
		$this->display('city/citylist');
	}
	
	public function addcity(){
		$lst_province = M('province','','DB_system')->select();
		$this->assign('lst_province', $lst_province);
		$this->display('city/addcity');
	}
	
	public function cityupdate(){
		$city_id = I('city_id');
		$lst_city = M('city','','DB_system')->where("city_id='$city_id'")->find();
		$province_id=$lst_city['province_id'];
		$lst_province = M('province','','DB_system')->where("province_id='$province_id'")->find();
		$this->assign('lst_city', $lst_city);
		$this->assign('lst_province', $lst_province);
		$this->display('city/cityupdate');
	}
	
	public function district(){
		$lst_province = M('province','','DB_system')->select();
		$this->assign('lst_province', $lst_province);
		$this->display('district/districtlist');
	}
	
	public function adddistrict(){
		$lst_city = M('city','','DB_system')->select();
		$this->assign('lst_city', $lst_city);
		$this->display('district/adddistrict');
	}
	
	
	public function districtupdate(){
		$district_id = I('district_id');
		$lst_district = M('district','','DB_system')->where("district_id='$district_id'")->find();
		$city_id=$lst_district['city_id'];
		$lst_city = M('city','','DB_system')->where("city_id='$city_id'")->find();
		$this->assign('lst_city', $lst_city);
		$this->assign('lst_district', $lst_district);
		$this->display('district/districtupdate');
	}
}