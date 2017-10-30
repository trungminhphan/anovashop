<?php
require_once('header.php');
check_permis($users->is_admin());
$sanpham = new SanPham();$donvitinh = new DonViTinh();
$tonkho = new TonKho();$danhmuccongty = new DanhMucCongTy();
$danhmuccongty_list = $danhmuccongty->get_all_list();
$sanpham_list = $sanpham->get_all_list();
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
//$sl  = $tonkho->sum_soluong_by_id_sanpham("395185");
//var_dump($sl[0]['sum_soluong']);
$erpid = isset($_GET['erp_id']) ? $_GET['erp_id'] : '';
?>
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET" class="form-horizontal" data-parsley-validate="true" enctype="multipart/form-data">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title"><i class="fa fa-search"></i> Lọc theo cửa hàng</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-10">
                        <select name="erp_id" class="select2 form-control">
                            <option value="">Tất cả cửa hàng</option>
                        <?php
                        if($danhmuccongty_list){
                            foreach($danhmuccongty_list as $ct){
                                $erp_id = isset($ct['erp_id']) ? $ct['erp_id'] : '';
                                echo '<option value="'.$erp_id.'"'.($erpid == $erp_id ? ' selected' : '').'>['.$erp_id .'] '. $ct['ten'].'</option>';
                            }
                        }
                        ?>
                        </select>
                    </div>
                    <div class="col-md-2 text-left">
                        <button name="submit" id="submit" value="OK" class="btn btn-primary"><i class="fa fa-search"></i> Xem</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title"><i class="fa fa-list"></i> Danh mục Sản phẩm</h4>
            </div>
            <div class="panel-body">
            <table id="data-table" class="table table-striped table-bordered table-hovered">
                <thead>
                    <tr>
                    	<th>STT</th>
                        <th>ID</th>
                    	<th>Tên sản phẩm</th>
                    	<th class="text-center">Giá</th>
                        <th class="text-center">Đơn vị tính</th>
                    	<th class="text-center">Số lượng tồn kho</th>
                    	<!--<th class="text-center">Thêm số lượng</th>-->
                    	<th class="text-center"><i class="fa fa-eye"></i></th>
                        <th class="text-center"><i class="fa fa-trash"></i></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if($sanpham_list){
                	$i = 1;
                	foreach($sanpham_list as $sp){
                        if($erpid){
                            $soluongtonkho = $tonkho->sum_soluong_by_id_sanpham_erp($sp['id'], $erpid);
                        } else {
                            $soluongtonkho = $tonkho->sum_soluong_by_id_sanpham($sp['id']);
                        }
                        if($soluongtonkho) {
                            $sl= $soluongtonkho[0]['sum_soluong'];
                        } else {
                            $sl = 0;
                        }
                        $dvt = $donvitinh->get_one_by_id($sp['unitId']);
                		echo '<tr>';
                		echo '<td>'.$i.'</td>';
                        echo '<td>'.$sp['id'].'</td>';
                		echo '<td>'.$sp['name'].'</td>';
                		echo '<td class="text-right">'.format_number($sp['price']).'</td>';
                        echo '<td class="text-right">'.$dvt['name'].'</td>';
                		echo '<td class="text-right">'.format_number($sl).'</td>';
                		//echo '<td class="text-center"><a href="#modal-soluong" data-toggle="modal" name="'.$sp['_id'].'" class="soluong"><i class="fa fa-arrow-circle-up"></i></a></td>';
                		echo '<td class="text-center"><a href="get.chitietsanpham.html?id='.$sp['_id'].'#modal-chitietsanpham" class="chitietsanpham" data-toggle="modal"><i class="fa fa-eye"></i></a></td>';
                        echo '<td class="text-center"><a href="get.delete.html?id='.$sp['_id'].'&collect=sanpham&url='.$_SERVER['REQUEST_URI'].'" onclick="return confirm(\'Chắc chắn xóa?\')"><i class="fa fa-trash"></i></a></td>';
                		echo '</tr>';$i++;
                	}
                }
                ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-chitietsanpham">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Xem chi tiết sản phẩm</h4>
            </div>
            <div class="modal-body" id="content-chitetsanpham">
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-sm btn-white" data-dismiss="modal">Đóng</a>
            </div>
        </div>
    </div>
</div>

<!--<div class="modal fade" id="modal-soluong">
<form action="post.sanpham.html" method="POST" class="form-horizontal" data-parsley-validate="true" name="congtyform">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Thêm số lượng</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Số lượng nhập kho</label>
                    <div class="col-md-9">
                        <input type="hidden" name="id" class="id_sanpham"/>
                        <input type="hidden" name="act" value="soluong" />
                        <input type="hidden" name="url" value="<?php //echo $_SERVER['REQUEST_URI']; ?>">
                        <input type="number" name="soluong" id="soluong" placeholder="Số lượng" class="form-control"  data-parsley-required="true"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-sm btn-white" data-dismiss="modal">Đóng</a>
                <button type="submit" name="submit" id="submit" class="btn btn-sm btn-success">Lưu</button>
            </div>
        </div>
    </div>
</form>
</div>-->

<div style="clear:both;"></div>
<?php require_once('footer.php'); ?>
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="assets/plugins/parsley/dist/parsley.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/pdfmake.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/vfs_fonts.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/DataTables/extensions/AutoFill/js/dataTables.autoFill.min.js"></script>
<script src="assets/plugins/DataTables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
<script src="assets/plugins/DataTables/extensions/KeyTable/js/dataTables.keyTable.min.js"></script>
<script src="assets/plugins/DataTables/extensions/RowReorder/js/dataTables.rowReorder.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Select/js/dataTables.select.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
    $(document).ready(function() {
    	$(".select2").select2();
    	$(".soluong").click(function(){
    		var id_sanpham = $(this).attr("name");
    		$(".id_sanpham").val(id_sanpham);
    	});
        $(".chitietsanpham").click(function(){
            var _link = $(this).attr("href");
            $.get(_link, function(data){
                $("#content-chitetsanpham").html(data);
            });
        });

        <?php if(isset($msg) && $msg): ?>
        $.gritter.add({
            title:"Thông báo !",
            text:"<?php echo $msg; ?>",
            image:"assets/img/login.png",
            sticky:false,
            time:""
        });
        <?php endif; ?>
        $("#data-table").DataTable({dom:"lBfrtip",buttons:[{extend:"excel",className:"btn-sm btn-success"}],responsive:!0,autoFill:!0,colReorder:!0,keys:!0,rowReorder:!0,select:!0});

        App.init();

    });
</script>
