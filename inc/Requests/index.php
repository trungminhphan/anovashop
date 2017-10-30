<?php

include('library/Requests.php');
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

var_dump($arr['categories']);

/*
http://teraapp.net:12000/v1/synchonizeData/getOrderList //danh sach don hang
url các api: lấy category: teraapp.net:12000/v1/synchonizeData/getCategoryList, //danh muc san pham
lấy product: teraapp.net:12000/v1/synchonizeData/getItemList, //danh sach san pham
lấy variant: teraapp.net:12000/v1/synchonizeData/getVariantList, //thuoc tinh san pham
lấy customer: teraapp.net:12000/v1/synchonizeData/getCustomerList //danh sach khach hang

*/
?>
