<?php
require_once('header_none.php');
check_permis($users->is_admin());
$donhang = new DonHang();$danhmuccongty = new DanhMucCongTy();
include('inc/Requests/library/Requests.php');
Requests::register_autoloader();

$data = array(
		'apikey' => 'd248a12a-317e-40db-aed5-41d27ef6a1db',
		'secretkey' => 'ac56f97c-1ea2-4a56-89ec-3a29b55fa556',
		'Content-Type' => 'application/json'
	);
$link = 'http://teraapp.net:12000/v1/synchonizeData/getOrderList';
$response = Requests::post($link, $data, array());
$str = $response->body;
$arr = json_decode($str, true);
$blninsert = false; $count = 0;
if($arr['orders']){
	foreach($arr['orders'] as $key => $order){
		$danhmuccongty->addresslevelone = $order['addresslevelone'];
		$danhmuccongty->addressleveltwo = $order['addressleveltwo'];
		//$danhmuccongty->addresslevelthree = $order['addresslevelthree'];
		$ct = $danhmuccongty->get_one_by_address1();
		$id_congty = $ct['_id'] ? new MongoId($ct['_id']) : '';
		if(!$id_congty){
			$ct = $danhmuccongty->get_one_by_address2();
			$id_congty = $ct['_id'] ? new MongoId($ct['_id']) : '';
		}
		if(!$donhang->check_sync($order['id'])){
			$ngaymua = new MongoDate($order['createdDate']/1000);
			$blninsert = true;
			$order['ngaymua'] = $ngaymua;
			$order['date_post'] = new MongoDate();
			$order['id_congty'] = $id_congty;
			$donhang->data_sync = $order;
			$donhang->sync();
			$count++;
		} else {
			$s = $donhang->get_one_by_id($order['id']);
			$id_donhang = $s['_id'];
			$donhang->id = $id_donhang;
			$ngaymua = new MongoDate($order['createdDate']/1000);
			$order['ngaymua'] = $ngaymua;
			$order['date_post'] = new MongoDate();
			$order['id_congty'] = $id_congty;
			if(isset($s['tinhtrang'])) $order['tinhtrang'] = $s['tinhtrang'];
			$donhang->data_sync = $order;
			$donhang->edit_sync();
		}
	}
}
if($blninsert == true) echo 'Thêm mới <b>' . $count. '</b> thành công';
else echo 'Không có đơn hàng mới';
echo ' <a href="donhang.html">Trở về đơn hàng</a>';
?>
