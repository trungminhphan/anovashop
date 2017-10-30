<?php
require_once('header_none.php');
$sanpham = new SanPham();
$id = isset($_POST['id']) ? $_POST['id'] : '';
$act = isset($_POST['act']) ? $_POST['act'] : '';
$url = isset($_POST['url']) ? $_POST['url'] : '';
$id_user = $users->get_userid();
$sanpham->id = $id;

if($act == 'soluong'){
	$soluong = isset($_POST['soluong']) ? $_POST['soluong'] : 0;
	$sanpham->soluong = $soluong;
	if($sanpham->inc_soluong()){
		if($url) transfers_to($url . '?msg=Thêm số lượng thành công.');
		else transfers_to('donhang.htmlmsg=Thêm số lượng thành công.');
	}
}

?>