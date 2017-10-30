<?php
function __autoload($class_name) {
   require_once('cls/class.' . strtolower($class_name) . '.php');
}
require_once('inc/functions.inc.php');
$session = new SessionManager();
$users = new Users();
$gridfs = new GridFS();$config = new Config();
if($users->isLoggedIn()){
    transfers_to('./index.html');   
} 
require('inc/config.inc.php');
$ht = $config->get_one();
$url = isset($_GET['url']) ? $_GET['url'] : '';
if(isset($_POST['submit'])){
    $username = $_POST['username'] ? $_POST['username'] : '';
    $password = $_POST['password'] ? $_POST['password'] : '';
    $url = $_POST['url'] ? $_POST['url'] : '';
    if ($users->authenticate($username, $password)) {
        $users->push_logs_in();
        if($url) transfers_to($url);
        else transfers_to('index.html');
        /*if($users->is_admin()){
            transfers_to('index.html');
        } else if($users->is_farmer()){
            transfers_to('nongtrai.html');
        } else if($users->is_factory()){
            transfers_to('nhamay.html');
        } else if($users->is_packer()){
            transfers_to('donggoi.html');
        } else if($users->is_retail()){
            transfers_to('banle.html');
        } else {
            if($url) transfers_to($url);
            else transfers_to("./index.html");
        }*/
    } else {
        $alert = true;
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
	<title>ANOVA SHOP |</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="Hệ thống quản lý truy xuất nguồn gốc trang trại ANOVA SHOP" />
    <meta content="Hệ thống quản lý truy xuất nguồn gốc trang trại ANOVA SHOP" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="assets/css/animate.min.css" rel="stylesheet" />
	<link href="assets/css/style.min.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
	<script src="assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
</head>
<body class="pace-top bg-white">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
	    <!-- begin login -->
        <div class="login login-with-news-feed">
            <!-- begin news-feed -->
            <div class="news-feed">
                <div class="news-image">
                    <?php if(isset($ht['image']) && $ht['image']): ?>
                        <img src="image.php?id=<?php echo $ht['image']; ?>" data-id="login-cover-image" alt="" />
                    <?php else: ?>
                        <img src="assets/img/login-bg/bg-login.png" data-id="login-cover-image" alt="" />
                    <?php endif; ?>
                </div>
                <div class="news-caption">
                    <h4 class="caption-title">
                    <?php if(isset($ht['image']) && $ht['image']): ?>
                        <img src="image.php?id=<?php echo $ht['image_1']; ?>" style="margin-top: -10px;width:166px; height:28px;" />
                    <?php else: ?>
                        <img src="images/star.png" style="margin-top: -10px;width:166px; height:28px;"/>
                    <?php endif; ?>
                    <?php echo isset($ht['text']) ? $ht['text'] : 'ANOVA SHOP'; ?></h4>
                </div>
            </div>
            <!-- end news-feed -->
            <!-- begin right-content -->
            <div class="right-content">
                <!-- begin login-header -->
                <div class="login-header">
                    <div class="brand">
                        <span class="fa fa-home"></span> <?php echo isset($ht['title']) ? $ht['title'] : 'ANOVA SHOP'; ?>
                        <small>ĐĂNG NHẬP HỆ THỐNG</small>
                    </div>
                    <div class="icon">
                        <i class="fa fa-sign-in"></i>
                    </div>
                </div>
                <!-- end login-header -->
                <!-- begin login-content -->
                <div class="login-content">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="margin-bottom-0" id="loginform">
                        <input type="hidden" name="url" id="url" value="<?php echo isset($url) ? $url : ''; ?>">
                        <div class="form-group m-b-15">
                            <input type="text" name="username" id="username" class="form-control input-lg" placeholder="Tài khoản đăng nhập" required />
                        </div>
                        <div class="form-group m-b-15">
                            <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Mật khẩu" required />
                        </div>
                        <div class="login-buttons">
                            <button type="submit" name="submit" id="submit" class="btn btn-success btn-block btn-lg">Đăng nhập hệ thống</button>
                        </div>
                        <hr />
                    </form>
                </div>
                <!-- end login-content -->
            </div>
            <!-- end right-container -->
        </div>
        <!-- end login -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
		$(document).ready(function() {
			App.init();
            $.gritter.add({
                title:"Thông báo đăng nhập hệ thống!",
                text:"Vui lòng điền đầy đủ thông tin đăng nhập.",
                image:"assets/img/login.png",
                sticky:false,
                time:""
            });
		});
       
	</script>
</body>
</html>