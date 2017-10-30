<?php
require_once('header_none.php');
check_permis($users->is_admin());
$khachhang = new KhachHang();
include('inc/Requests/library/Requests.php');
Requests::register_autoloader();

$data = array(
			'apikey' => 'd248a12a-317e-40db-aed5-41d27ef6a1db',
			'secretkey' => 'ac56f97c-1ea2-4a56-89ec-3a29b55fa556',
			'Content-Type' => 'application/json'
		);
$link = 'http://teraapp.net:12000/v1/synchonizeData/getCustomerList';
$response = Requests::post($link, $data, array());
$str = $response->body;
$arr = json_decode($str, true);

$blninsert = false; $count = 0;
if($arr['customers']){
	foreach($arr['customers'] as $key => $item){
		if(!$khachhang->check_sync($item['id'])){
			$blninsert = true;
			$item['date_post'] = new MongoDate();
			$khachhang->data_sync = $item;
			$khachhang->sync();
			$count++;
		} else {
			$s = $khachhang->get_one_by_id($item['id']);
			$id_khachhang = $s['_id'];
			$khachhang->id = $id_khachhang;
			$item['date_post'] = new MongoDate();
			$khachhang->data_sync = $item;
			$khachhang->edit_sync();
		}
	}
}

if($blninsert == true) echo 'Thêm mới <b>' . $count. '</b> thành công';
else echo 'Không có khách hàng mới';
echo ' <a href="donhang.html">Trở về danh mục khách hàng</a>';

?>