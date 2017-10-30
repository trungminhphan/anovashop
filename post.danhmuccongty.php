<?php
require_once('header_none.php');
$danhmuccongty = new DanhMucCongTy();
$id = isset($_POST['id']) ? $_POST['id'] : '';
$act = isset($_POST['act']) ? $_POST['act'] : '';
$url = isset($_POST['url']) ? $_POST['url'] : '';
$erp_id = isset($_POST['erp_id']) ? $_POST['erp_id'] : '';
$ten = isset($_POST['ten']) ? $_POST['ten'] : '';
$diachi = isset($_POST['diachi']) ? $_POST['diachi'] : '';
$addresslevelone = isset($_POST['addresslevelone']) ? $_POST['addresslevelone'] : '';
$addressleveltwo = isset($_POST['addressleveltwo']) ? $_POST['addressleveltwo'] : '';
$addresslevelthree = isset($_POST['addresslevelthree']) ? $_POST['addresslevelthree'] : '';
$tenduong = isset($_POST['tenduong']) ? $_POST['tenduong'] : '';
$danhmuccongty->erp_id = $erp_id;
$danhmuccongty->ten = $ten;
$danhmuccongty->diachi = $diachi;
$danhmuccongty->addresslevelone = $addresslevelone;
$danhmuccongty->addressleveltwo = $addressleveltwo;
$danhmuccongty->addresslevelthree = $addresslevelthree;
$danhmuccongty->tenduong = $tenduong;

if($act == 'edit'){
	$danhmuccongty->id = $id;
	if($danhmuccongty->edit()) {
		if($url) transfers_to($url);
		else transfers_to('danhmuccongty.html?msg=Chỉnh sửa thành công!');
	}
} else {
	if($danhmuccongty->insert()){
		if($url) transfers_to($url);
		else transfers_to('danhmuccongty.html?msg=Thêm nơi bán lẻ thành công!');
	}
}
?>
