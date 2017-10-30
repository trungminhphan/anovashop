<?php
require_once('header_none.php');
$donhang = new DonHang();$danhmuccongty = new DanhMucCongTy();$sanpham = new SanPham();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$donhang->id = $id; $dh = $donhang->get_one();
$t = isset($dh['tinhtrang'][0]['t']) ? $dh['tinhtrang'][0]['t'] : 0;
$tt = isset($dh['tinhtrang'][0]['tt']) ? $dh['tinhtrang'][0]['tt'] : 0;
$dinhkem = isset($dh['tinhtrang'][0]['dinhkem'][0]['aliasname']) ? $dh['tinhtrang'][0]['dinhkem'][0]['aliasname'] : '';
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
                <h4 class="panel-title"><i class="fa fa-list"></i> Thông tin đơn hàng</h4>
            </div>
            <div class="panel-body" style="border:1px blue solid;">
            	<div class="row">
            		<label class="col-md-3 control-label">Tên khách hàng</label>
            		<div class="col-md-3"><?php echo $dh['fullname']; ?></div>
                    <label class="col-md-3 control-label">Tình trạng đơn hàng: </label>
                    <div class="col-md-3"><b><?php echo $arr_tinhtrang[$t]; ?></b></div>
            	</div>
            	<div class="row">
            		<label class="col-md-3 control-label">Địa chỉ</label>
            		<div class="col-md-3"><?php echo $dh['address']; ?></div>
                    <label class="col-md-3 control-label">Thanh toán: </label>
                    <div class="col-md-3"><b><?php echo $arr_thanhtoan[$tt]; ?></b></div>
            	</div>
            	<div class="row">
            		<label class="col-md-3 control-label">Điện thoại</label>
            		<div class="col-md-3"><?php echo $dh['phone']; ?></div>
                    <?php if($dinhkem) : ?>
                        <label class="col-md-3 control-label">Xem đơn hàng: </label>
                        <div class="col-md-3"><b><a href="<?php echo $uploads_folder. $dinhkem; ?>" target="_blank"><i class="fa fa-file-image-o"></i></a></b></div>
                    <?php endif; ?>
            	</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
                <h4 class="panel-title"><i class="fa fa-list"></i> Sản phẩm đơn hàng</h4>
            </div>
            <div class="panel-body" style="border:1px blue solid;">
            <?php if($dh['orderItems']) : ?>
            	<table id="data-table" class="table table-striped table-bordered table-hovered">
            		<thead>
                    <tr>
                        <th class="text-center">STT</th>
                        <th class="text-center">Sản phẩm</th>
                        <th class="text-center">số lượng</th>
                        <th class="text-center">Tồn kho</th>
                        <th class="text-center">Đơn giá</th>
                        <th class="text-center">Thành tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    foreach($dh['orderItems'] as $item){
                    	$thanhtien = $item['quantity'] * $item['price'];
                        $tonkho = $sanpham->get_one_by_id($item['itemId']);
                    	echo '<tr>';
                    	echo '<td class="text-right">'.$i.'</td>';
                    	echo '<td>'.$item['name'].'</td>';
                    	echo '<td class="text-right">'.$item['quantity'].'</td>';
                        echo '<td class="text-right">'.$tonkho['soluong'].'</td>';
                    	echo '<td class="text-right">'.format_number($item['price']).'</td>';
                    	echo '<td class="text-right">'.format_number($thanhtien).'</td>';
                    	echo '</tr>';$i++;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    	<tr>
                    		<th colspan="4" class="text-center">TỔNG CỘNG</th>
                    		<th class="text-right"><?php echo format_number($dh['totalorigin']);?></th>
                    	</tr>
                    </tfoot>
            	</table>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                </div>
                <h4 class="panel-title"><i class="fa fa-list"></i> Tình trạng đơn hàng</h4>
            </div>
            <div class="panel-body" style="border:1px blue solid;">
            	<?php
            		if(isset($dh['tinhtrang']) && $dh['tinhtrang']){
            			echo '<div class="row">
			            		<label class="col-md-3 control-label">Ngày thực hiện</label>
			            		<label class="col-md-3 control-label">Tình trạng</label>
			            		<label class="col-md-3 control-label">Nội dung</label>
			            		<label class="col-md-3 control-label">Người thực hiện</label>
			            	</div>';
            			foreach($dh['tinhtrang'] as $tt){
            				$users->id = $tt['id_user']; $us = $users->get_one();
            				echo '<div class="row">
				            		<div class="col-md-3">#'.date("d/m/Y", $tt['date_post']->sec).'</div>
				            		<div class="col-md-3">'.$arr_tinhtrang[$tt['t']].'</div>
				            		<div class="col-md-3">'.$tt['noidung'].'</div>
				            		<div class="col-md-3">'.$us['person'].'</div>
				            	</div>';
            			}
            		} else {
            			echo 'Chưa xử lý';
            		}
            	?>
            </div>
        </div>
    </div>
</div> 
<?php
if(isset($dh['id_congty']) && $dh['id_congty']){
    $danhmuccongty->id = $dh['id_congty']; $ct = $danhmuccongty->get_one();
    echo '<b>#Nơi giao hàng: ' . $ct['ten'] . ', ' . $ct['diachi'] . '</b>';
}
?>