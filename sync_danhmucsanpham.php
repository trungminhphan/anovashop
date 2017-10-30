<?php
require_once('header_none.php');
check_permis($users->is_admin());
//$danhmucsanpham = new DanhMucSanPham();
include('inc/Requests/library/Requests.php');
Requests::register_autoloader();

$data = array(
			'apikey' => 'd248a12a-317e-40db-aed5-41d27ef6a1db',
			'secretkey' => 'ac56f97c-1ea2-4a56-89ec-3a29b55fa556',
			'Content-Type' => 'application/json'
		);
$link = 'http://teraapp.net:12000/v1/synchonizeData/getCategoryList';
$response = Requests::post($link, $data, array());
//var_dump($response->body);
$str = $response->body;
$arr = json_decode($str, true);
/*$blninsert = false; $count = 0;
if($arr['items']){
	foreach($arr['items'] as $key => $sp){
		if(!$sanpham->check_sync($sp['id'])){
			$blninsert = true;
			$sp['soluong'] = 0;
			$sp['date_post'] = new MongoDate();
			$sanpham->data_sync = $sp;
			$sanpham->sync();
			$count++;
		} else {
			$s = $sanpham->get_one_by_id($sp['id']);
			$id_sanpham = $s['_id'];
			$sanpham->id = $id_sanpham;
			$soluong = $s['soluong'];
			$date_post = $s['date_post'];
			$sp['soluong'] = $soluong;
			$sp['date_post'] = $date_post;
			$sanpham->data_sync = $sp;
			$sanpham->sync();
		}
	}
}

if($blninsert == true) echo 'Thêm mới <b>' . $count. '</b> thành công';
else echo 'Không có sản phẩm mới';
echo ' <a href="sanpham.html">Trở về danh mục sản phẩm</a>';
*/
?>