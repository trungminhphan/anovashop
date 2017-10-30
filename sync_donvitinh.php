<?php
require_once('header_none.php');
check_permis($users->is_admin());
$donvitinh = new DonViTinh();
include('inc/Requests/library/Requests.php');
Requests::register_autoloader();

$data = array(
			'apikey' => 'd248a12a-317e-40db-aed5-41d27ef6a1db',
			'secretkey' => 'ac56f97c-1ea2-4a56-89ec-3a29b55fa556',
			'Content-Type' => 'application/json'
		);
$link = 'http://teraapp.net:12000/v1/synchonizeData/getUnitList';
$response = Requests::post($link, $data, array());
//var_dump($response->body);
$str = $response->body;
$arr = json_decode($str, true);
$blninsert = false; $count = 0;
if($arr['units']){
	foreach($arr['units'] as $key => $sp){
		if(!$donvitinh->check_sync($sp['id'])){
			$blninsert = true;
			$sp['date_post'] = new MongoDate();
			$donvitinh->data_sync = $sp;
			$donvitinh->sync();
			$count++;
		} else {
			$s = $donvitinh->get_one_by_id($sp['id']);
			$id_donvitinh = $s['_id'];
			$donvitinh->id = $id_donvitinh;
			$date_post = $s['date_post'];
			$sp['date_post'] = $date_post;
			$donvitinh->data_sync = $sp;
			$donvitinh->edit_sync();
		}
	}
}

if($blninsert == true) echo 'Thêm mới <b>' . $count. '</b> thành công';
else echo 'Không có đơn vị tính mới';
echo ' <a href="donvitinh.html">Trở về danh mục đơn vị tính</a>';
?>