<?php
require_once('inc/barcode/BarcodeGenerator.php');
require_once('inc/barcode/BarcodeGeneratorPNG.php');
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
$id = isset($_GET['id']) ? $_GET['id'] : '';
$sanpham = new SanPham();$donvitinh = new DonViTinh();$danhmuccongty = new DanhMucCongTy();
$donhang = new DonHang(); $donhang->id = $id; $dh = $donhang->get_one();
$gridfs = new GridFS();$config = new Config();$diachi = new DiaChi();
$ht = $config->get_one();
//$user_default = $users->get_one_default();
$danhmuccongty->id = $dh['id_congty'];
$ct = $danhmuccongty->get_one();
$person = isset($_GET['user']) ? $_GET['user'] : 'ANVOA';
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
    <style type="text/css">
    	body { font-family: Arial; }
    	table{
			font-family: Arial;
			width:650px;
			border-collapse: collapse;
			margin: 0px auto;
			padding: 0px;
		}

		table td {
			padding:5px;
			border: 1px solid #000;
		}

		table td.title{
			text-align: center;
			font-weight: bold;
			font-size: 14px;
		}

		table td.title_1{
			font-size: 11px;
		}

		input.title{
			text-align: center;
			font-weight: bold;
			font-size: 14px;border:0px;
		}

		input.title_1{
			font-size: 11px;
			width:110px;
			font-weight:bold;
			border:0px;
		}

		textarea.title_1{
			width: 200px;
			padding:0px !important; margin: 0px;
			overflow: hidden;
			font-size: 11px;
			font-family: tahoma;
			border:0px;
			resize: none;
			vertical-align: top;
		}
    </style>
</head>
<body>
<h3 style="text-align:center;">THÔNG TIN CẬP NHẬT ĐƠN HÀNG</h3>
<table>
	<tr>
		<td class="title_1">Ngày thực hiện</td>
		<td class="title_1">Tình trạng</td>
		<td class="title_1">Nội dung</td>
		<td class="title_1">Hình thức thanh toán</td>
	</tr>
<?php
if(isset($dh['tinhtrang']) && $dh['tinhtrang']):
	$t = isset($dh['tinhtrang'][0]['t']) ? $dh['tinhtrang'][0]['t'] : 0;
	$tt = isset($dh['tinhtrang'][0]['tt']) ? $dh['tinhtrang'][0]['tt'] : 0;
?>
	<tr>
		<td class="title_1"><b><?php echo date("d/m/Y", $dh['tinhtrang'][0]['date_post']->sec); ?></b></td>
		<td class="title_1"><b><?php echo $arr_tinhtrang[$t]; ?></b></td>
		<td class="title_1"><b><?php echo $dh['tinhtrang'][0]['noidung']; ?></b></td>
		<td class="title_1"><b><?php echo $arr_thanhtoan[$tt]; ?></b></td>
	</tr>
<?php endif; ?>
</table>
<h3 style="text-align:center;">THÔNG TIN ĐƠN HÀNG</h3>
<table style="border:1px solid;">
	<tr>
		<td rowspan="3" class="title">
			<input type="text" value="<?php echo $person; ?>" class="title" />
		</td>
		<td rowspan="3" class="title">HÓA ĐƠN BÁN LẺ</td>
		<td class="title_1">Đơn hàng</td>
		<td class="title_1"><?php echo $dh['id']; ?></td>
	</tr>
	<tr>
		<td class="title_1">Ngày</td>
		<td class="title_1"><?php echo date("d/m/Y", $dh['ngaymua']->sec); ?></td>
	</tr>
	<tr>
		<td class="title_1">Số hóa đơn</td>
		<td class="title_1"><?php echo $dh['id']; ?></td>
	</tr>
	<tr>
		<td colspan="4" style="padding:0px;">
			<table>
				<tr>
					<td width="100" rowspan="4" colspan="2" align="center" style="border:0px;">
						<?php if(isset($ht['logo_left']) && $ht['logo_left']): ?>
                        	<img src="http://traceweb.org/giaohang/image.php?id=<?php echo $ht['logo_left']; ?>" width="100"/>
	                    <?php else: ?>
	                        <img src="http://traceweb.org/giaohang/images/hoadon1.png" width="100"/>
	                    <?php endif; ?>
					</td>
					<td style="border-top:0px;" class="title_1" colspan="2">Thông tin nhà bán hàng:</td>
					<td style="border-top: 0px;" class="title_1" colspan="2">Thông tin hóa đơn</td>
					<td width="106" rowspan="4" style="border:0px !important;">
						<?php if(isset($ht['logo_right']) && $ht['logo_right']): ?>
                        	<img src="http://traceweb.org/giaohang/image.php?id=<?php echo $ht['logo_right']; ?>" width="100"/>
	                    <?php else: ?>
	                        <img src="http://traceweb.org/giaohang/images/hoadon2.png" width="100"/>
	                    <?php endif; ?>
					</td>
				</tr>
				<tr>
					<td style="border-left:0px;" class="title_1" colspan="2">Tên nhà bán hàng: <input type="text" class="title_1" value="<?php echo $ct['ten']; ?>"></td>
					<td style="border-right:0px;" rowspan="2" colspan="2" class="title_1">
						Tên khách hàng:<br />
						<b><?php echo $dh['name']; ?></b><br />
						Địa chỉ xuất hóa đơn:
						<textarea class="title_1" rows="3">
<?php if($dh['address']) echo $dh['address'] . "\n"; ?>
<?php echo $dh['addressLevelThreeName'] . ', ' . $dh['addressLevelTwoName'] .', ' . $dh['addressLevelOneName'] ?>
						</textarea>
					</td>
				</tr>
				<tr>
					<td style="border-left:0px;" class="title_1"  colspan="2">
					Địa chỉ Nhà bán hàng:<br />
					<?php
					if(isset($ct['tenduong']) && $ct['tenduong']) echo $ct['tenduong'] . ', ';

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
					</td>
				</tr>
				<tr>
					<td class="title_1" style="border-left:0px;" colspan="2">Điện thoại: <?php echo $ct['diachi'];?></td>
					<td class="title_1" style="border-right:0px;"  colspan="2">Điện thoại: <?php echo $dh['phone']; ?></td>
				</tr>
				<tr>
					<td class="title_1" align="center" style="border-left:0px;"><b>STT</b></td>
					<td class="title_1" colspan="2" align="center" width="120"><b>Tên sản phẩm</b></td>
					<td class="title_1" align="center" width="100"><b>Đơn vị tính</b></td>
					<td class="title_1" align="center" width="100"><b>Số lượng</b></td>
					<td class="title_1" align="center" width="100"><b>Đơn giá (VNĐ)</b></td>
					<td class="title_1" align="center" style="border-right:0px;"><b>Thành tiền (VNĐ)</b></td>
				</tr>
				<!--<tr>
					<td class="title_1" align="center" style="border-left:0px;">1</td>
					<td class="title_1">Chân giò x 1</td>
					<td class="title_1" align="right">1.900.000</td>
					<td class="title_1" align="right" style="border-right:0px;"><b>1.900.000</b></td>
				</tr>
				<tr>
					<td class="title_1" align="center" style="border-left:0px;">2</td>
					<td class="title_1">Đầu mình x 1</td>
					<td class="title_1" align="right">1.900.000</td>
					<td class="title_1" align="right" style="border-right:0px;"><b>1.900.000</b></td>
				</tr>-->
				<?php
				if($dh['orderItems']){
					$i = 1;
					foreach ($dh['orderItems'] as $item) {
						$sp = $sanpham->get_one_by_id($item['itemId']);
						$dvt = $donvitinh->get_one_by_id($sp['unitId']);
						echo '<tr>
								<td class="title_1" align="center" style="border-left:0px;">'.$i.'</td>
								<td class="title_1" colspan="2">'.$item['name'].'</td>
								<td class="title_1" align="center">'.$dvt['name'].'</td>
								<td class="title_1" align="center">'.$item['quantity'].'</td>
								<td class="title_1" align="right">'.format_number($item['sellingprice']).'</td>
								<td class="title_1" align="right" style="border-right:0px;"><b>'.format_number($item['quantity']*$item['sellingprice']).'</b></td>
							</tr>';$i++;
					}
				}
				?>
				<tr>
					<td class="title_1" align="center" style="border-left:0px;" colspan="3"></td>
					<td class="title_1" colspan="2">Cộng:</td>
					<td class="title_1" colspan="2" align="right" style="border-right:0px;"><b><?php echo format_number($dh['total']); ?></b></td>
				</tr>
				<tr>
					<td class="title_1" align="center" style="border-left:0px;" colspan="3"></td>
					<td class="title_1" colspan="2">Voucher:</td>
					<td class="title_1" colspan="2" align="right" style="border-right:0px;"><b>0</b></td>
				</tr>
				<tr>
					<td class="title_1" align="center" style="border-left:0px;" colspan="3"></td>
					<td class="title_1" colspan="2">Phí giao hàng:</td>
					<td class="title_1" colspan="2" align="right" style="border-right:0px;"><b><?php echo $dh['shippingfee'] ? $dh['shippingfee'] : 0; ?></b></td>
				</tr>
				<tr>
					<td class="title_1" align="center" style="border-left:0px;" colspan="3"></td>
					<td class="title_1" colspan="2">Tổng cộng:</td>
					<td class="title_1" colspan="2" align="right" style="border-right:0px;"><b><?php echo format_number($dh['total']); ?> VNĐ</b></td>
				</tr>
				<tr>
					<td class="title_1" align="center" style="border-left:0px;" colspan="3"></td>
					<td class="title_1" style="border-right:0px;" colspan="4">(*) Giá trên đã bao gồm thuế GTGT</td>
				</tr>
				<tr>
					<td class="title_1" align="center" style="border-left:0px;" colspan="3"></td>
					<td class="title_1" style="border-right:0px;" colspan="4">Bằng chữ:
						<?php
							$docso = new DocSo();
							$str_sotien = $docso->doc(abs(round($dh['total'],0)));
							echo ucfirst(trim($str_sotien)).' đồng (đã bao gồm thuế GTGT)';
						?>
					</td>
				</tr>
				<tr>
					<td colspan="7" style="border-left: 0px;border-right:0px;" class="title_1">
						Cam kết của Nhà bán hàng đối với giao dịch được thực hiện thông qua sàn giao dịch thương mại điện tử.<br />
						Bằng văn bản này, nhà bán hàng cam kết phát hành hóa đơn bán hàng, theo thông tin xuất hóa đơn nêu trên khi giao dịch với khách hàng này được hoàn thành. Tên nhà bán hàng: ANOVA SHOP.

					</td>
				</tr>
				<tr>
					<td colspan="7" align="center" style="border-left: 0px;border-right:0px;border-bottom: 0px;" class="title_1">
						<?php echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($dh['id'], $generator::TYPE_CODE_128)) . '">'; ?> <br />
						Mã đơn hàng: <?php echo $dh['id']; ?><br /> <br />
						<b>CÁM ƠN ĐÃ MUA SẮM TẠI <?php echo $user_default['person']; ?> </b>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
