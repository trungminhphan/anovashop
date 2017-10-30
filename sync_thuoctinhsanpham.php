<?php
require_once('header_none.php');
check_permis($users->is_admin());
$thuoctinhsanpham = new ThuocTinhSanPham();
include('inc/Requests/library/Requests.php');
Requests::register_autoloader();

$data = array(
			'apikey' => 'd248a12a-317e-40db-aed5-41d27ef6a1db',
			'secretkey' => 'ac56f97c-1ea2-4a56-89ec-3a29b55fa556',
			'Content-Type' => 'application/json'
		);
$link = 'http://teraapp.net:12000/v1/synchonizeData/getVariantList';
$response = Requests::post($link, $data, array());
$str = $response->body;
$arr = json_decode($str, true);

$blninsert = false; $count = 0;
if($arr['variants']){
	foreach($arr['variants'] as $key => $item){
		if(!$thuoctinhsanpham->check_sync($item['id'])){
			$blninsert = true;
			$item['date_post'] = new MongoDate();
			$thuoctinhsanpham->data_sync = $item;
			$thuoctinhsanpham->sync();
			$count++;
		}
	}
}

if($blninsert == true) echo 'Thêm mới <b>' . $count. '</b> thành công';
else echo 'Không có sản phẩm mới';
echo ' <a href="sanpham.html">Trở về danh mục sản phẩm</a>';

?>