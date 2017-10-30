<?php
	//DEFINE QUYEN CHO TUNG NGUOI
	define("ADMIN", 1);
	define("STORE", 2);

	$target_files = 'uploads/files/';
	$target_files_regis = 'uploads_regis/';
	$folder_regis = '../lanhsuthutuc/';
	$target_images = 'uploads/images/';

	$uploads_folder = 'uploads/';
	$files_extension = array('pdf', 'zip', 'rar', 'doc', 'docx', 'xls', 'png', 'gif', 'jpg', 'jpeg', 'bmp', 'rtf');
	$images_extension = array('png', 'gif', 'jpg', 'jpeg', 'bmp');
	$valid_formats = array("jpg", "png", "gif", "zip", "bmp", "doc", "docx", "pdf", "xls", "xlsx", "ppt", "pptx", 'zip', 'rar');
	$max_file_size = 50*1024*1024*1024; //50MB

	$arr_gioitinh = array('M' => 'Nam', 'F' => 'Nữ');
	$arr_dungdenngay = array('D' => 'Ngày', 'M' => 'Tháng', 'Y' => 'Năm');

	$arr_tinhtrang = array(
		0 => 'Chưa giao hàng',
		2 => 'Đang giao hàng',
		1 => 'Đã giao hàng',
		3 => 'Huỷ đơn hàng'
	);

	$arr_thanhtoan = array(
		0 => 'Chưa thanh toán',
		1 => 'Tiền mặt',
		2 => 'Chuyển khoản'
	);
?>