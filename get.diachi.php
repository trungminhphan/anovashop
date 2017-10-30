<?php
require_once('header_none.php'); 
$id = isset($_GET['id']) ? $_GET['id'] : '';
$id_diachi2 = isset($_GET['id_diachi2']) ? $_GET['id_diachi2'] : '';
$diachi = new DiaChi();

$query = array('parentid' => $id);
$diachi_list = $diachi->get_list_condition($query);

if($diachi_list){
	echo '<option value="">Chọn địa chỉ</option>';
	foreach ($diachi_list as $dc) {
		echo '<option value="'.$dc['id'].'"'.(($id_diachi2 && $dc['id'] == $id_diachi2) ? ' selected' : '').'>'.$dc['name'].'</option>';
	}
}
?>