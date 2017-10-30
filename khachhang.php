<?php
require_once('header.php');
check_permis($users->is_admin());
$khachhang = new KhachHang();
$khachhang_list = $khachhang->get_all_list();
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title"><i class="fa fa-list"></i> Danh mục Khách hàng</h4>
            </div>
            <div class="panel-body">
            <table id="data-table" class="table table-striped table-bordered table-hovered">
                <thead>
                    <tr>
                    	<th>STT</th>
                    	<th>Họ tên</th>
                        <th>Điện thoại</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                        <th class="text-center"><i class="fa fa-trash"></i></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if($khachhang_list){
                	$i = 1;
                	foreach($khachhang_list as $sp){
                		echo '<tr>';
                		echo '<td>'.$i.'</td>';
                		echo '<td>'.$sp['fullName'].'</td>';
                        echo '<td>'.$sp['phone'].'</td>';
                        echo '<td>'.$sp['email'].'</td>';
                        echo '<td>'.$sp['adress'].'</td>';
                        echo '<td class="text-center"><a href="get.delete.html?id='.$sp['_id'].'&collect=khachhang&url='.$_SERVER['REQUEST_URI'].'" onclick="return confirm(\'Chắc chắn xóa?\')"><i class="fa fa-trash"></i></a></td>';
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


<div style="clear:both;"></div>
<?php require_once('footer.php'); ?>
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
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
