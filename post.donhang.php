<?php
require_once('header_none.php');
$donhang = new DonHang();$sanpham = new SanPham();$tonkho = new TonKho();
$danhmuccongty = new DanhMucCongTy();
$id = isset($_POST['id']) ? $_POST['id'] : '';
$act = isset($_POST['act']) ? $_POST['act'] : '';
$url = isset($_POST['url']) ? $_POST['url'] : '';
$id_user = $users->get_userid();
$id_congty = $users->get_id_congty();
$donhang->id = $id;$dh = $donhang->get_one();
$user_default = $users->get_one_default();
if($user_default['id_congty']){
	$danhmuccongty->id = $user_default['id_congty'];
	$dmct = $danhmuccongty->get_one();
	$erp_id = isset($dmct['erp_id']) ? $dmct['epr_id'] : '';
} else { $erp_id = ''; }
$l = explode("?", $url); $url = $l[0];
if($act == 'noigiaohang'){
	$id_congty = isset($_POST['id_congty']) ? $_POST['id_congty'] : '';
	$donhang->id_congty = $id_congty;
	if($donhang->push_congty()){
		if($url) transfers_to($url . '?msg=Chuyển nơi giao hàng thành công');
		else transfers_to('donhang.htmlmsg=Chuyển nơi giao hàng thành công!');
	}
}

if($act == 'tinhtrang'){
	require('phpmailer/PHPMailerAutoload.php');
	$mail = new PHPMailer;
	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'legomarketingteam@gmail.com';             // SMTP username
	$mail->Password = 'qyohhytkzlfrapos';                        // SMTP password
	$mail->SMTPSecure = 'ssl';                          // SMTP password
	$mail->Port = 465;                                   // TCP port to connect to
	$mail->CharSet = 'UTF-8';
	$mail->setFrom('legomarketingteam@gmail.com', $user_default ['person']);
	$mail->addAddress('beetrangtran@gmail.com', 'THÔNG TIN ĐƠN HÀNG');
	$mail->addAddress('khang.nguyen@anovacorp.vn', 'THÔNG TIN ĐƠN HÀNG');
	//$mail->addAddress('trungminhphan@gmail.com', 'THÔNG TIN ĐƠN HÀNG');
	if(isset($user_default['email']) && $user_default['email']){
        $mail->addAddress($user_default['email'], 'THÔNG TIN ĐƠN HÀNG');
    }
    if(isset($dh['email']) && $dh['email']){
        $mail->addAddress($dh['email'], 'THÔNG TIN ĐƠN HÀNG');
    }
	$mail->isHTML(true);
	$id_tinhtrang = isset($_POST['id_tinhtrang']) ? $_POST['id_tinhtrang'] : '';
	$id_thanhtoan = isset($_POST['id_thanhtoan']) ? $_POST['id_thanhtoan'] : '';
	$noidung = isset($_POST['noidung']) ? $_POST['noidung'] : '';
	$arr_dinhkem = array();
	$dinhkem_aliasname = isset($_POST['dinhkem_aliasname']) ? $_POST['dinhkem_aliasname'] : '';
	$dinhkem_filename = isset($_POST['dinhkem_filename']) ? $_POST['dinhkem_filename'] : '';
	$dinhkem_type = isset($_POST['dinhkem_type']) ? $_POST['dinhkem_type'] : '';
	$dinhkem_size = isset($_POST['dinhkem_size']) ? $_POST['dinhkem_size'] : '';
	if($dinhkem_aliasname){
	    foreach ($dinhkem_aliasname as $key => $value) {
	        array_push($arr_dinhkem, array('filename' => $dinhkem_filename[$key], 'aliasname' => $value, 'type' => $dinhkem_type[$key], 'size' => $dinhkem_size[$key]));
	    }
	}
	$arr_tinhtrang = array(
		't' => intval($id_tinhtrang),
		'tt' => intval($id_thanhtoan),
		'dinhkem' => $arr_dinhkem,
		'noidung' => $noidung,
		'id_user' => new MongoId($id_user),
		'id_congty' => new MongoId($id_congty),
		'date_post' => new MongoDate()
	);
	if($id_tinhtrang == 1){
		if(isset($dh['orderItems']) && $dh['orderItems']){
			foreach($dh['orderItems'] as $item){
				$tonkho->soluong = -$item['quantity'];
				$tonkho->inc_soluong_by_itemId($item['itemId'], $erp_id);
			}
		}
	}

	$donhang->tinhtrang = $arr_tinhtrang;
	if($donhang->push_tinhtrang()){
		include('inc/Requests/library/Requests.php');
		Requests::register_autoloader();
		$mail->Subject = $user_default ['person']. ' - THÔNG TIN ĐƠN HÀNG';
	    $response = Requests::get('http://103.27.236.20/anovashop/get.chitietdonhang_email.html?id='.$id.'&user='.$user_default ['person']);
	    $mail->Body    = $response->body;
	    $mail->AltBody = $response->body;
	    $mail->send();
		if($url) transfers_to($url.'?msg=Cập nhật tình trạng thành công');
		else transfers_to('donhang.html?msg=Cập nhật tình trạng thành công!');
	}
}
//itemId
//noigiaohang
//edit
//insert
//tinhtrang
?>
