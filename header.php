<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.html?url=' . $_SERVER['REQUEST_URI']); }
$user_default = $users->get_one_default();
$id_congty = $users->get_id_congty();
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
	<script src="assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
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
					?>&nbsp;&nbsp;&nbsp; <?php echo $user_default['person']; ?>
					</a>
					<button type="button" class="navbar-toggle" data-click="top-menu-toggled">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<!-- end mobile sidebar expand / collapse button -->
				<!-- begin header navigation right -->
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown navbar-user">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-user"></i>
							<span class="hidden-xs">
								<?php echo $user_default['person']; ?>
							</span> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu animated fadeInLeft">
							<li class="arrow"></li>
							<li><a href="change_password.html"><span class="fa fa-key"></span> Thay đổi mật khẩu</a></li>
                            <?php if($users->is_admin()) : ?>
                            <li class="arrow"></li>
                            <li><a href="users.html"><span class="fa fa-users"></span> Danh sách tài khoản</a></li>
                            <?php endif; ?>
							<li class="divider"></li>
							<li><a href="logout.html"><span class="fa fa-sign-out"></span> Đăng xuất</a></li>
						</ul>
					</li>
				</ul>
				<!-- end header navigation right -->
			</div>
			<!-- end container-fluid -->
		</div>
		<!-- end #header -->
		<!-- begin #top-menu -->
		<div id="top-menu" class="top-menu">
            <!-- begin top-menu nav -->
            <ul class="nav">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                        <span>TRANG CHỦ</span>
                    </a>
                </li>
                <?php if($users->is_admin()) : ?>
            	<li class="has-sub">
	                <a href="#">
	                	<b class="caret pull-right"></b>
	                    <i class="fa fa-refresh"></i>
	                    <span>ĐỒNG BỘ DỮ LIỆU</span>
	                </a>
	                 <ul class="sub-menu">
                        <li class="divider"></li>
                        <li><a href="sync_sanpham.html">Sản phẩm</a></li>
                        <li class="divider"></li>
                        <!--<li><a href="sync_thuoctinhsanpham.html">Thuộc tính sản phẩm</a></li>
                        <li class="divider"></li>-->
                        <li><a href="sync_donvitinh.html">Đơn vị tính</a></li>
                        <li class="divider"></li>
                        <li><a href="sync_khachhang.html">Khách hàng</a></li>
                        <li class="divider"></li>
                        <li><a href="sync_donhang.html">Đơn hàng</a></li>
                        <li class="divider"></li>
                        <li><a href="sync_addressbook.html">Địa chỉ</a></li>
                        <li class="divider"></li>
                    </ul>
                </li>
                <li class="has-sub">
	                <a href="#">
	                	<b class="caret pull-right"></b>
	                    <i class="fa fa-list"></i>
	                    <span>DANH MỤC</span>
	                </a>
	                <ul class="sub-menu">
                        <li class="divider"></li>
                        <li><a href="sanpham.html">Sản phẩm</a></li>
                        <li class="divider"></li>
                        <li><a href="tonkho.html">Cập nhật tồn kho</a></li>
                        <li class="divider"></li>
                        <!--<li><a href="thuoctinhsanpham.html">Thuộc tính sản phẩm</a></li>
                        <li class="divider"></li>-->
                        <li><a href="donvitinh.html">Đơn vị tính</a></li>
                        <li class="divider"></li>
                        <li><a href="khachhang.html">Khách hàng</a></li>
                        <li class="divider"></li>
                        <li><a href="diachi.html">Địa chỉ</a></li>
                        <li class="divider"></li>
                    </ul>
                </li>
                <li>
                    <a href="noigiaohang.html">
                        <i class="fa fa-map-marker"></i>
                        <span>NƠI GIAO HÀNG</span>
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="donhang.html">
                        <i class="ion-clipboard"></i>
                        <span>ĐƠN HÀNG</span>
                    </a>
                </li>

            	<li class="has-sub">
                    <a href="baocao.html">
                        <i class="fa fa-bar-chart-o"></i>
                        <b class="caret pull-right"></b>
                        <span>BÁO CÁO</span>
                    </a>
                    <ul class="sub-menu">
                        <li class="divider"></li>
                        <li><a href="baocao.html">Theo cửa hàng</a></li>
                        <li class="divider"></li>
                        <li><a href="baocaotheoquanhuyen.html">Theo Quận/Huyện</a></li>
                    </ul>
                </li>
                <?php if($users->is_admin()): ?>
                <li>
                    <a href="config.html">
                        <i class="fa fa-cog"></i>
                        <span>CẤU HÌNH</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="menu-control menu-control-left">
                    <a href="#" data-click="prev-menu"><i class="fa fa-angle-left"></i></a>
                </li>
                <li class="menu-control menu-control-right">
                    <a href="#" data-click="next-menu"><i class="fa fa-angle-right"></i></a>
                </li>
            </ul>
            <!-- end top-menu nav -->
		</div>
		<!-- end #top-menu -->
		<!-- begin #content -->
		<div id="content" class="content">
