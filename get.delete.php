<?php
require_once('header_none.php');
check_permis($users->is_admin());
$id = isset($_GET['id']) ? $_GET['id'] : '';
$collect = isset($_GET['collect']) ? $_GET['collect'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : 'index.html';
$a = explode("?", $url);

if($id && $collect){
	if($collect == 'sanpham') $c = new SanPham();
	if($collect == 'donvitinh') $c = new DonViTinh();
	if($collect == 'khachhang') $c = new KhachHang();
	if($collect == 'donhang') $c = new DonHang();
	if($collect == 'diachi') $c = new DiaChi();
	$c->id = $id;
	if($c->delete()) transfers_to($a[0] . '?msg=Xóa thành công');
} else {
	echo 'Không thể xóa. <a href="index.html">Trở về</a>';
}
?>