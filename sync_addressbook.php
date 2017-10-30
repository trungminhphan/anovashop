<?php
require_once('header_none.php');
$diachi = new DiaChi();
include('inc/Requests/library/Requests.php');
Requests::register_autoloader();
$data = array(
			'apikey' => 'd248a12a-317e-40db-aed5-41d27ef6a1db',
			'secretkey' => 'ac56f97c-1ea2-4a56-89ec-3a29b55fa556',
			'Content-Type' => 'application/json'
		);
$link = 'http://teraapp.net:12000/v1/synchonizeData/getAddressBookList';
$response = Requests::post($link, $data, array());

$str = $response->body;
$arr = json_decode($str, true);
$blninsert = false; $count = 0;
if($arr['addressBooks']){
	foreach($arr['addressBooks'] as $key => $dc){
		if(!$diachi->check_sync($dc['id'])){
			$blninsert = true;
			$dc['date_post'] = new MongoDate();
			$diachi->data_sync = $dc;
			$diachi->sync();
			$count++;
		} else {
			$s = $diachi->get_one_by_id($dc['id']);
			$id_diachi = $s['_id'];
			$diachi->id = $id_diachi;
			$date_post = $s['date_post'];
			$dc['date_post'] = $date_post;
			$diachi->data_sync = $dc;
			$diachi->edit_sync();
		}
	}
}
if($blninsert == true) echo 'Thêm mới <b>' . $count. '</b> thành công';
else echo 'Không có địa chỉ mới';
echo ' <a href="diachi.html">Trở về danh mục địa chỉ</a>';

?>