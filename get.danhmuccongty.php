<?php
require_once('header_none.php');
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$danhmuccongty = new DanhMucCongTy(); $donhang = new DonHang();
if($act == 'del' && $id){
	$danhmuccongty->id = $id; $dm = $danhmuccongty->get_one();
	if($donhang->check_dmcongty($id)){
		transfers_to('noigiaohang.html?msg=Không thể xoá, ràng buộc trường dữ liệu các thông tin Trứng, Rau Quả, Giết mổ!');
	} else {
		if($danhmuccongty->delete()) transfers_to('noigiaohang.html?msg=Xóa thành công!');
		else transfers_to('noigiaohang.html?msg=Không thể xóa, ràng buộc nơi giết mổ');
	}
}

if($act == 'edit'){
	$danhmuccongty->id = $id; $dm = $danhmuccongty->get_one();
	$arr = array(
		'id' => $id,
		'act' => $act,
		'erp_id' => isset($dm['erp_id']) ? $dm['erp_id'] : '',
		'ten' => $dm['ten'],
		'diachi' => $dm['diachi'],
		'diachi1' => isset($dm['addresslevelone']) ? $dm['addresslevelone'] : '',
		'diachi2' => isset($dm['addressleveltwo']) ? $dm['addressleveltwo'] : '',
		'diachi3' => isset($dm['addresslevelthree']) ? $dm['addresslevelthree'] : '',
		'tenduong' => isset($dm['tenduong']) ? $dm['tenduong'] : ''
	);
	echo json_encode($arr);
}
?>
