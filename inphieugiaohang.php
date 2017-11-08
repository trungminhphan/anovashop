<?php
require_once('inc/barcode/BarcodeGenerator.php');
require_once('inc/barcode/BarcodeGeneratorPNG.php');
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
require_once('header_none.php');
$id = isset($_GET['id']) ? $_GET['id'] : '';
$sanpham = new SanPham();$donvitinh = new DonViTinh();$diachi = new DiaChi(); $danhmuccongty = new DanhMucCongTy();
$donhang = new DonHang(); $donhang->id = $id; $dh = $donhang->get_one();
$user_default = $users->get_one_default();
$danhmuccongty->id = $dh['id_congty'];
$ct = $danhmuccongty->get_one();
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
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/phieugiaohang.css" rel="stylesheet" />
</head>
<body>
<table>
	<tr height="50">
		<td width="215" rowspan="2" align="center">
		<?php
			if(isset($user_default['hinhanh']) && $user_default['hinhanh']){
				echo '<img src="image.html?id='.$user_default['hinhanh'].'" height="70">';
			} else {
				echo '<img src="images/default_logo.png" alt="" height="70"/>';
			}
		?>
		</td>
		<td width="215" rowspan="2">
			Mã số dịch vụ: <?php echo $dh['id']; ?><br />
			Ngày giao hàng: <?php echo date("d/m/Y"); ?>
		</td>
		<td style="font-size: 13px;padding-left:20px;"><i class="fa fa-square-o"></i> STANDARD</td>
	</tr>
	<tr height="50">
		<td style="font-size: 13px;padding-left:20px;"><i class="fa fa-square-o"></i> EXPRESS</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<?php echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($dh['id'], $generator::TYPE_CODE_128)) . '">'; ?>
			<br />
			Mã đơn hàng: <?php echo $dh['id']; ?>
		</td>
	</tr>
	<tr>
		<td align="left" rowspan="2">
		<b>Người gửi:</b> <input type="text" value="<?php echo $ct['ten']; ?>" class="title_1">
		<textarea rows="2" class="title_2">
Đường: <?php if(isset($ct['tenduong'])) echo $ct['tenduong'] ."\n"; ?>
<?php
if($ct['addresslevelthree']){
	$a3 = $diachi->get_one_by_id($ct['addresslevelthree']); echo $a3['name'] . ', ';
}
if($ct['addressleveltwo']){
	$a2 = $diachi->get_one_by_id($ct['addressleveltwo']); echo $a2['name'] . ', ';
}
if($ct['addresslevelone']){
	$a1 = $diachi->get_one_by_id($ct['addresslevelone']); echo $a1['name'];
}
?>
		</textarea>
		<b>
			<u>Hotline nhà bán hàng:<br /><?php echo $ct['diachi'];?></u>
		</b>
		</td>
		<td colspan="2" align="left">
		<b>
			Người nhận:</br>
			<?php echo $dh['name']; ?><br />
			<textarea rows="2" class="title_2" style="width:400px;font-weight:bold;">
<?php if($dh['address']){ echo $dh['address'] . "\n"; } ?>
<?php echo  $dh['addressLevelThreeName'] . ', ' . $dh['addressLevelTwoName'] .', ' . $dh['addressLevelOneName']; ?>
			</textarea>
		</b>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left">Điện thoại người nhận: <?php echo $dh['phone']; ?> </td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<?php echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($dh['id'], $generator::TYPE_CODE_128)) . '">'; ?>
			<br />
			Mã đơn hàng: <?php echo $dh['id']; ?>
		</td>
	</tr>
	<tr>
		<td>Số đơn hàng: <?php echo $dh['id']; ?></td>
		<td colspan="2">
			Số tiền thu hộ: <b><?php echo format_number($dh['total']); ?> VNĐ</b>
			<br />Bằng chữ:
			<?php
				$docso = new DocSo();
				$str_sotien = $docso->doc(abs(round($dh['total'],0)));
				echo ucfirst(trim($str_sotien)).' đồng (đã bao gồm thuế GTGT)';
			?>
		</td>
	</tr>
	<td colspan="3">
		<table class="products">
			<tr>
				<td align="center"><b>Stt</b></td>
				<td align="center"><b>Mã sản phẩm</b></td>
				<td align="center"><b>Tên sản phẩm</b></td>
				<td align="center"><b>Đơn vị tính</b></td>
				<td align="center"><b>Số lượng</b></td>
				<td align="center"><b>Đơn giá (VNĐ)</b></td>
				<td align="center"><b>Thành tiền (VNĐ)</b></td>
			</tr>
			<?php
			if($dh['orderItems']){
				$i = 1;
				foreach ($dh['orderItems'] as $item) {
					$sp = $sanpham->get_one_by_id($item['itemId']);
					$dvt = $donvitinh->get_one_by_id($sp['unitId']);
					echo '<tr>
							<td align="center">'.$i.'</td>
							<td align="center">'.$item['itemId'].'</td>
							<td>'.$item['name'].'</td>
							<td align="center">'.$dvt['name'].'</td>
							<td align="center">'.$item['quantity'].'</td>
							<td align="right">'.format_number($item['sellingprice']).'</td>
							<td align="right">'.format_number($item['quantity']*$item['sellingprice']).'</td>
						</tr>';$i++;
				}
			}
			?>
			<!--<tr>
				<td align="center">1</td>
				<td>Chân giò</td>
				<td align="center">123456</td>
				<td></td>
			</tr>
			<tr>
				<td align="center">2</td>
				<td>Chân giò</td>
				<td align="center">123456</td>
				<td></td>
			</tr>-->
		</table>
	</td>
</table>
</body>
</html>
