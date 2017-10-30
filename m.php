<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();$sanpham = new SanPham();$tonkho = new TonKho();$danhmuccongty = new DanhMucCongTy();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.html?url=' . $_SERVER['REQUEST_URI']); }
$user_default = $users->get_one_default();
$id_congty = $users->get_id_congty();
if($user_default['id_congty']){
    $danhmuccongty->id = $user_default['id_congty'];
    $dmct = $danhmuccongty->get_one();
    $erp_id = isset($dmct['erp_id']) ? $dmct['epr_id'] : '';
} else { $erp_id = ''; }
$donhang = new DonHang();
if(isset($_POST['submit'])){
	$madonhang = isset($_POST['madonhang']) ? $_POST['madonhang'] : '';
	if($users->is_admin()){
		$dh = $donhang->get_one_by_id($madonhang);
	} else {
		$dh = $donhang->get_one_by_id_congty($madonhang, $id_congty);
	}

	$id_donhang = isset($dh['_id']) ? $dh['_id'] : '';
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
	$mail->setFrom('legomarketingteam@gmail.com', 'ANOVASHOP');
	$mail->addAddress('beetrangtran@gmail.com', 'THÔNG TIN ĐƠN HÀNG');
	$mail->addAddress('khang.nguyen@anovacorp.vn', 'THÔNG TIN ĐƠN HÀNG');
    if(isset($user_default['email']) && $user_default['email']){
        $mail->addAddress($user_default['email'], 'THÔNG TIN ĐƠN HÀNG');
    }
    if(isset($dh['email']) && $dh['email']){
        $mail->addAddress($dh['email'], 'THÔNG TIN ĐƠN HÀNG');
    }
	$mail->isHTML(true);
	if($id_donhang){
		$donhang->id = $id_donhang;
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
			$dh = $donhang->get_one();
			if(isset($dh['orderItems']) && $dh['orderItems']){
				foreach($dh['orderItems'] as $item){
                    $tonkho->soluong = -$item['quantity'];
                    $tonkho->inc_soluong_by_itemId($item['itemId'], $erp_id);
					//$sanpham->soluong = -$item['quantity'];
					//$sanpham->inc_soluong_by_itemId($item['itemId']);
				}
			}
		}
		$donhang->tinhtrang = $arr_tinhtrang;
		if($donhang->push_tinhtrang()){
			include('inc/Requests/library/Requests.php');
			Requests::register_autoloader();
			$mail->Subject = 'ANOVASHOP - THÔNG TIN ĐƠN HÀNG';
		    $response = Requests::get('http://103.27.236.20/anovashop/get.chitietdonhang_email.html?id='.$id_donhang.'&user='.$user_default ['person']);
		    $mail->Body    = $response->body;
	    	$mail->AltBody = $response->body;
		    $mail->send();
			if($url) transfers_to($url.'?msg=Cập nhật tình trạng thành công');
			else transfers_to('m.html?msg=Cập nhật tình trạng thành công!');
		}
	} else {
		$msg = 'Đơn hàng không tồn tại';
	}
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Nova Shop</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="Hệ thống quản lý truy xuất nguồn gốc trang trại Anova Shop" />
    <meta content="Hệ thống quản lý truy xuất nguồn gốc trang trại Anova Shop" />
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">-->
	<link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="assets/css/animate.min.css" rel="stylesheet" />
	<link href="assets/css/style.min.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<link href="assets/css/theme/blue.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
    <link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" />
    <link href="assets/plugins/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" />
	<!-- ================== BEGIN BASE JS ================== -->
	<link href="assets/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet" />
	<link href="assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
	<link href="assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />
	<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
	<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
</head>
<body>
<!-- end #page-loader -->
<!-- begin #page-container -->
<div id="page-container" class="page-container fade page-without-sidebar page-header-fixed page-with-top-menu">
	<!-- begin #header -->
	<div id="header" class="header navbar navbar-default navbar-fixed-top">
		<!-- begin container-fluid -->
		<div class="container-fluid">
			<!-- begin mobile sidebar expand / collapse button -->
			<div class="navbar-header">
				<a href="index.html" class="navbar-brand">
				<?php
				if(isset($user_default['hinhanh']) && $user_default['hinhanh']){
					echo '<img src="image.html?id='.$user_default['hinhanh'].'" height="30px;" align="left">';
				} else {
					echo '<img src="images/default_logo.png" alt="" height="30" align="left"/>';
				}
				?>&nbsp;&nbsp;&nbsp;ANOVA SHOP
				</a>
				<button type="button" class="navbar-toggle" data-click="top-menu-toggled">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
		</div>
		<!-- end container-fluid -->
	</div>
	<!-- end #header -->
	<!-- begin #top-menu -->
	<div id="top-menu" class="top-menu" style="margin-top: -55px;">
        <!-- begin top-menu nav -->
        <ul class="nav">
            <li>
                <a href="logout.html?url=m.html">
                    <i class="fa fa-sign-out"></i>
                    <span>Đăng xuất</span>
                </a>
            </li>
        </ul>
        <!-- end top-menu nav -->
	</div>
	<!-- end #top-menu -->
	<!-- begin #content -->
	<div id="content" class="content">
	<div class="row" style="margin-top: -60px;">
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" class="form-horizontal" data-parsley-validate="true" name="dinhkemform" id="dinhkemform">
    <div class="col-md-12">
    	<div class="form-group">
    		<label class="col-md-12 control-label">Mã đơn hàng</label>
    		<div class="col-md-12">
    			<input type="text" name="madonhang" id="madonhang" value="<?php echo isset($madonhang) ? $madonhang : ''; ?>" class="form-control" data-parsley-required="true" />
    		</div>
    	</div>
        <div class="form-group">
            <label class="col-md-12 control-label">Tình trạng</label>
            <div class="col-md-12">
                <input type="hidden" name="act" value="tinhtrang" />
                <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <select name="id_tinhtrang" id="id_tinhtrang" class="form-control select2" style="width:100%;">
                <?php
                	if($arr_tinhtrang){
                		foreach ($arr_tinhtrang as $key => $value) {
                			echo '<option value="'.$key.'">'.$value.'</option>';
                		}
                	}
                ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-12 control-label">Thanh toán</label>
            <div class="col-md-12">
                <select name="id_thanhtoan" id="id_thanhtoan" class="form-control select2" style="width:100%;">
                <?php
                    if($arr_thanhtoan){
                        foreach ($arr_thanhtoan as $key => $value) {
                            echo '<option value="'.$key.'">'.$value.'</option>';
                        }
                    }
                ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-12 control-label">Đính kèm:</label>
            <div class="col-md-12">
                <span class="btn btn-success fileinput-button">
                    <i class="fa fa-plus"></i>
                    <span>Chọn tập tin đính kèm...</span>
                    <input type="file" name="dinhkem[]" class="dinhkem" accept="*" multiple="multiple">
                </span>
            </div>
        </div>
        <div class="progress progress-striped active">
            <div class="progress-bar" style="width:0%">0%</div>
        </div>
        <div id="dinhkem_list">
        </div>
        <div class="form-group">
        	<label class="col-md-12 control-label">Nội dung</label>
            <div class="col-md-12">
            	<textarea name="noidung" id="noidung" rows="5" placeholder="Nội dung tình trạng" class="form-control"></textarea>
            </div>
        </div>
        <div class="form-group text-center">
        	<button type="submit" name="submit" id="submit" class="btn btn-success">Cập nhật</button>
        </div>
    </div>
    </form>
	<?php require('footer.php'); ?>
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
	<script type="text/javascript" src="assets/js/dinhkem.js"></script>
	<script src="assets/plugins/parsley/dist/parsley.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
	    $(document).ready(function() {
	    	upload_files();delete_file();$(".progress").hide();
	    	$(".select2").select2();
	    	<?php if(isset($msg) && $msg): ?>
		        $.gritter.add({
		            title:"Thông báo !",
		            text:"<?php echo $msg; ?>",
		            image:"assets/img/login.png",
		            sticky:false,
		            time:""
		        });
		    <?php endif; ?>
	        App.init();
	    });
	</script>
