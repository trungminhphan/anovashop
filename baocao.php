<?php
require_once('header.php');
//check_permis($users->is_admin());
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$donhang = new DonHang();$danhmuccongty = new DanhMuccongTy();$tonkho = new TonKho();
$danhmuccongty_list = $danhmuccongty->get_all_list();
$sanpham = new SanPham();
$id_congty='';
if(isset($_GET['submit'])){
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
    $id_congty = isset($_GET['id_congty']) ? $_GET['id_congty'] : '';
	$start_date = convert_date_yyyy_mm_dd_3($tungay, 0, 0, 0);
	$end_date = convert_date_yyyy_mm_dd_3($denngay, 23, 59, 59);

	if($start_date > $end_date){
		$msg = 'Chọn ngày sai';
	} else {
        $query = array();
        array_push($query, array('ngaymua' => array('$gte' => new MongoDate($start_date))));
        array_push($query, array('ngaymua' => array('$lte' => new MongoDate($end_date))));

        if($id_congty && $users->is_admin()){
            array_push($query, array('id_congty' => new MongoId($id_congty)));
        } else if($id_congty){
            array_push($query, array('id_congty' => new MongoId($user_default['id_congty'])));
        }

        $q = array('$and' => $query);
		$donhang_list = $donhang->get_baocao_condition($q);
	}
}
?>
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
<!--<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />-->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title"><i class="fa fa-list"></i> BÁO CÁO THEO NƠI GIAO HÀNG</h4>
            </div>
            <div class="panel-body">
            	<form class="form-horizontal" data-parsley-validate="true" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET">
                    <div class="form-group">
                        <div class="col-md-2">
                            <input type="text" name="tungay" id="tungay" value="<?php echo isset($tungay) ? $tungay: ''; ?>" class="form-control ngaythangnam" placeholder="Từ ngày" data-date-format="dd/mm/yyyy" data-inputmask="'alias': 'date'" data-parsley-required="true" />
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="denngay" id="denngay" value="<?php echo isset($denngay) ? $denngay: ''; ?>"" class="form-control ngaythangnam" placeholder="Đến ngày" data-date-format="dd/mm/yyyy" data-inputmask="'alias': 'date'" data-parsley-required="true" />
                        </div>
                        <?php if($users->is_admin()): ?>
                        <div class="col-md-5">
                        <select name="id_congty" id="id_congty" class="form-control select2">
                            <option value="">Chọn cửa hàng</option>
                            <?php
                            if($danhmuccongty_list){
                                foreach($danhmuccongty_list as $ct){
                                    echo '<option value="'.$ct['_id'].'"'.($ct['_id'] == $id_congty ? ' selected' : '').'>'.$ct['ten'].'</option>';
                                }
                            }
                            ?>
                        </select>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <button type="submit" name="submit" value="OK" class="btn btn-primary"><i class="fa fa-search"></i> Báo cáo</button>
                                <?php if(isset($_GET['submit'])): ?>
                                <a href="baocao_export.html?<?php echo $_SERVER['QUERY_STRING']; ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Xuất Excel</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if(isset($donhang_list) && $donhang_list): ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title"><i class="fa fa-list"></i> KẾT QUẢ BÁO CÁO</h4>
            </div>
            <div class="panel-body">
            <table class="table table-striped table-bordered table-hovered" style="font-size:11px;">
            	<thead>
            		<tr>
            			<th>Số đơn hàng</th>
            			<th>Ngày mua</th>
            			<th width="130">Người mua</th>
            			<th>Địa chỉ</th>
            			<th>Số điện thoại</th>
            			<th>Tên sản phẩm</th>
            			<th>Số lượng</th>
                        <th>Tồn kho</th>
            			<th>Đơn giá</th>
            			<th>Thành tiền</th>
            			<th width="100">Nơi giao hàng</th>
            			<th width="150">Thanh toán</th>
            		</tr>
            	</thead>
            	<tbody>
            	<?php
            	foreach($donhang_list as $dh){
            		if(isset($dh['id_congty']) && $dh['id_congty']){
            			$danhmuccongty->id = $dh['id_congty'];$ct = $danhmuccongty->get_one();
            			$tencongty = $ct['ten'];
                        $erpid = isset($ct['erp_id']) ? $ct['erp_id'] : '';
            		} else {
            			$tencongty = ''; $erpid = '';
            		}
            		$tt = isset($dh['tinhtrang'][0]['t']) ? $dh['tinhtrang'][0]['t'] : 0;
                    $t = isset($dh['tinhtrang'][0]['tt']) ? $dh['tinhtrang'][0]['tt'] : 0;
                    $quan_huyen = $dh['addressLevelThreeName'] . ', '.$dh['addressLevelTwoName'].', '.$dh['addressLevelOneName'];
            		if($dh['orderItems']){
            			foreach($dh['orderItems'] as $order){
            				$thanhtien = $order['quantity'] * $order['sellingprice'];
                            if($erpid){
                                $soluongtonkho = $tonkho->sum_soluong_by_id_sanpham_erp($order['itemId'], $erpid);
                            } else {
                                $soluongtonkho = $tonkho->sum_soluong_by_id_sanpham($order['itemId']);
                            }
                            if($soluongtonkho) {
                                $sl= $soluongtonkho[0]['sum_soluong'];
                            } else {
                                $sl = 0;
                            }
            				echo '<tr>';
		            		echo '<td>'.$dh['id'].'</td>';
		            		echo '<td>'.date("d/m/Y", $dh['ngaymua']->sec).'</td>';
		            		echo '<td>'.$dh['fullname'].'</td>';
		            		//echo '<td>'.$dh['address'].'</td>';
		            		echo '<td>'.$quan_huyen.'</td>';
		            		echo '<td>'.$dh['phone'].'</td>';
		            		echo '<td>'.$order['name'].'</td>';
		            		echo '<td>'.$order['quantity'].'</td>';
                            echo '<td>'.$sl.'</td>';
		            		echo '<td>'.format_number($order['sellingprice']).'</td>';
		            		echo '<td>'.format_number($thanhtien).'</td>';
		            		echo '<td>'.$tencongty.'</td>';
		            		echo '<td>'.$arr_tinhtrang[$tt].'<br />Thanh toán: ' .$arr_thanhtoan[$t].'</td>';
		            		echo '</tr>';
            			}
            		}
            	}
            	?>
            	</tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<div style="clear:both;"></div>
<?php require_once('footer.php'); ?>
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="assets/plugins/parsley/dist/parsley.js"></script>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<!--<script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>-->
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="assets/js/table-manage-default.demo.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
    $(document).ready(function() {
    	$(".ngaythangnam").datepicker({todayHighlight:!0});
    	$(".ngaythangnam").inputmask();
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
        App.init();//TableManageDefault.init();
    });
</script>
