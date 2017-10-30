<?php
require_once('header_none.php');
$filename = isset($_GET['filename']) ? $_GET['filename'] : '';
if(file_exists($uploads_folder.$filename)){
	if(@unlink($uploads_folder.$filename)){
		echo 'Đã xoá thành công';
	} else {
		echo 'Không thể xoá';
	}
}
?>