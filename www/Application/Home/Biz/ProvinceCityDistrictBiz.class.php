<?php
namespace Home\Biz;

/**
 *
 * @author wanghui
 *        
 */
class ProvinceCityDistrictBiz {
	
	
	public static function citylist($city_en) {
		
		// 参数检查start
		if(empty($city_en['page_index'])){
			$city_en['page_index'] = 1;
		}
		
		if(empty($city_en['page_index'])){
			$city_en['page_size'] = C('PromoterConfig.listpage_search_default_size');
		}
		// 参数检查end
		
		// 搜索条件start
		$condition_array = array();
	
		$con = array();
		$province_id=$city_en['province_id'];
		if(!empty($province_id)){
			array_push($con, "pe.province_id = '$province_id'");
		}
		$city_name=$city_en['city_name'];
		if(!empty($city_name)){
			array_push($con, "cy.city_name like '%$city_name%'");
		}
		// 搜索条件end
		$return_en = array();
		$condition_sql = implode(' AND ', $con);
		
		$countsql = "
			select 	count(*) as num
				from  province pe 
				left join city cy on pe.province_id=cy.province_id ";
		if(!empty($con)){
			$countsql = $countsql." WHERE ".$condition_sql;
		}
		
		$sql = "
			select pe.province_id,pe.province_name,cy.city_id,cy.city_name 
				from  province pe 
				left join city cy on pe.province_id=cy.province_id ";
		$startRow =	(intval($city_en['page_index'])-1)*intval($city_en['page_size']);
		if(!empty($con)){
			$sql = $sql." WHERE ".$condition_sql." limit ".$startRow.",".$city_en['page_size'];
		}else{
			$sql = $sql." limit ".$startRow.",".$city_en['page_size'];
		}
		$total_count_resule = M('','','DB_system')->query($countsql);
		
		$total_count = intval($total_count_resule[0]['num']);
		
		$return_en['total_count'] = $total_count;
		$lst_city_en = M('','','DB_system')->query($sql);
		
		$return_en['lst_city_en'] = $lst_city_en;
	
		$total_page = 0;
		$rem = $total_count % $city_en['page_size'];
		if($rem == 0){
			$total_page = $total_count / $city_en['page_size'];
		}else{
			$total_page = floor($total_count / $city_en['page_size']) + 1;
		}
		$return_en['total_page'] = $total_page;
		
		return $return_en;
	}
	
	public static function addcity( $province_id,$city_name){
		if(!empty($province_id)){
			$sql="select  MAX(city_id) city_id from  city where province_id =".$province_id;
			$max_city_id = M('','','DB_system')->query($sql);
			$city_id = intval($max_city_id[0]['city_id']);
		}
		$add_city_en = array(
				'city_id'	=> $city_id+1,
				'city_name'	=> $city_name,
				'province_id'	=> $province_id
		);
		M('city','','DB_system')->add($add_city_en);
	}
	
	public static function updateCity($city_id, $city_name){
		M('city','','DB_system')->where("city_id ='%s'",$city_id)->setField('city_name', $city_name);
	}
	
	
	public static function districtlist($district_en) {
	
		// 参数检查start
		if(empty($district_en['page_index'])){
			$district_en['page_index'] = 1;
		}
	
		if(empty($district_en['page_index'])){
			$district_en['page_size'] = C('PromoterConfig.listpage_search_default_size');
		}
		// 参数检查end
	
		// 搜索条件start
		$condition_array = array();
	
		$con = array();
		$province_id=$district_en['province_id'];
		if(!empty($province_id)){
			array_push($con, "pe.province_id = '$province_id'");
		}
		$city_name=$district_en['city_name'];
		if(!empty($city_name)){
			array_push($con, "cy.city_name like '%$city_name%'");
		}
		$district_name=$district_en['district_name'];
		if(!empty($district_name)){
			array_push($con, "dt.district_name like '%$district_name%'");
		}
		// 搜索条件end
		$return_en = array();
		$condition_sql = implode(' AND ', $con);
	
		$countsql = "
			select 	count(*) as num
				from  province pe
				left join city cy on pe.province_id=cy.province_id
				left join district dt on dt.city_id=cy.city_id ";
		if(!empty($con)){
			$countsql = $countsql." WHERE ".$condition_sql;
		}
	
		$sql = "
			select pe.province_id,pe.province_name,cy.city_id,
			 cy.city_name,dt.district_id,dt.district_name
				from  province pe
				left join city cy on pe.province_id=cy.province_id
				left join district dt on dt.city_id=cy.city_id ";
		$startRow =	(intval($district_en['page_index'])-1)*intval($district_en['page_size']);
		if(!empty($con)){
			$sql = $sql." WHERE ".$condition_sql." limit ".$startRow.",".$district_en['page_size'];
		}else{
			$sql = $sql." limit ".$startRow.",".$district_en['page_size'];
		}
		$total_count_resule = M('','','DB_system')->query($countsql);
	
		$total_count = intval($total_count_resule[0]['num']);
	
		$return_en['total_count'] = $total_count;
		$lst_district_en = M('','','DB_system')->query($sql);
	
		$return_en['lst_district_en'] = $lst_district_en;
	
		$total_page = 0;
		$rem = $total_count % $district_en['page_size'];
		if($rem == 0){
			$total_page = $total_count / $district_en['page_size'];
		}else{
			$total_page = floor($total_count / $district_en['page_size']) + 1;
		}
		$return_en['total_page'] = $total_page;
	
		return $return_en;
	}
	
	public static function adddistrict( $city_id,$district_name){
		if(!empty($city_id)){
			$sql="select  MAX(district_id) district_id from  district where city_id =".$city_id;
			$max_district_id = M('','','DB_system')->query($sql);
			$district_id = intval($max_district_id[0]['district_id']);
		}
		$add_district_en = array(
				'district_id'	=> $district_id+1,
				'district_name'	=> $district_name,
				'city_id'	=> $city_id
		);
		M('district','','DB_system')->add($add_district_en);
	}
	
	public static function updatedistrict($district_id, $district_name){
		M('district','','DB_system')->where("district_id ='%s'",$district_id)->setField('district_name', $district_name);
	}
} 