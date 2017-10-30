<?php
require_once('header.php');
check_permis($users->is_admin() || $users->is_store());
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$donhang = new DonHang();$danhmuccongty = new DanhMuccongTy();$diachi = new DiaChi();
$danhmuccongty_list = $danhmuccongty->get_all_list();
if($users->is_admin()){
	$donhang_list = $donhang->get_all_list();
} else {
	$donhang->id_congty = $id_congty;
	$donhang_list = $donhang->get_list_by_congty();
}
?>
<link href="assets/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet" />
<link href="assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
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
                <h4 class="panel-title"><a href="donhang_export.html" class="btn btn-xs btn-icon btn-success"><i class="fa fa-file-excel-o"></i></a> Danh sách các đơn hàng </h4>
            </div>
            <div class="panel-body">
            <table id="data-table" class="table table-striped table-bordered table-hovered" style="font-size:11px;">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã đơn hàng</th>
                        <th>Ngày mua</th>
                        <th>Tổng tiền</th>
                        <?php if($users->is_admin() || $users->is_store()) :?>
                            <th class="text-center">Tình trạng</th>
                        <?php endif; ?>
                        <th>Người mua</th>
                        <th>Điện thoại</th>
                        <th>Địa chỉ giao hàng</th>
                        <?php if($users->is_admin()): ?>
                        	<th class="text-center"><i class="fa fa-user-plus"></i></th>
                            <th class="text-center"><i class="fa fa-trash"></i></th>
                    	<?php endif; ?>
                        <th class="text-center"><i class="fa fa-eye"></i></th>
                        <th class="text-center"><i class="fa fa-file-text"></i></th>
                        <th class="text-center"><i class="fa fa-truck"></i></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if($donhang_list){
                	$i = 1;
                	foreach($donhang_list as $dh){
                        $dc1 = $diachi->get_one_by_id($dh['addresslevelone']);
                        if(isset($dc1['name'])) $diachi1 = $dc1['name'];
                        else $diachi1 = '';

                        $dc2 = $diachi->get_one_by_id($dh['addressleveltwo']);
                        if(isset($dc2['name'])) $diachi2 = $dc2['name'];
                        else $diachi2 = '';

                        $dc3 = $diachi->get_one_by_id($dh['addresslevelthree']);
                        if(isset($dc3['name'])) $diachi3 = $dc3['name'];
                        else $diachi3 = '';

                		$tt = isset($dh['tinhtrang'][0]['t']) ? $dh['tinhtrang'][0]['t'] : 0;
                        $ngaymua = isset($dh['ngaymua']) ? date("d/m/Y H:i", $dh['ngaymua']->sec) : '';
                        if(isset($dh['id_congty']) && $dh['id_congty']) $class= 'text-danger';
                        else $class='';
                		echo '<tr>';
                		echo '<td>'.$i.'</td>';
                		echo '<td>'.$dh['id'].'</td>';
                        echo '<td>'.$ngaymua.'</td>';
                        echo '<td class="text-right">'.format_number($dh['total']).'</td>';
                        if($users->is_admin() || $users->is_store()){
                            if(isset($dh['tinhtrang'][0]['t']) && $dh['tinhtrang'][0]['t'] == 1){
                                echo '<td class="text-center"><a href="#modal-tinhtrang" class="tinhtrang text-success" name="'.$dh['_id'].'" data-toggle="modal"><i class="fa fa-check-circle"></i> '.$arr_tinhtrang[$tt].'</a></td>';
                            } else if(isset($dh['tinhtrang'][0]['t']) && $dh['tinhtrang'][0]['t'] == 3){
                                echo '<td class="text-center text-danger"><i class="fa fa-minus-circle"></i> '.$arr_tinhtrang[$tt].'</td>';
                            } else {
                                echo '<td class="text-center"><a href="#modal-tinhtrang" class="tinhtrang" name="'.$dh['_id'].'" data-toggle="modal"><i class="fa fa-gears"></i> '.$arr_tinhtrang[$tt].'</a></td>';
                            }
                        }
                		echo '<td>'.$dh['name'].'</td>';
                		echo '<td>'.$dh['phone'].'</td>';
                		echo '<td>'.$diachi3.', '.$diachi2.', '.$diachi1.'</td>';
                		if($users->is_admin()){
                			echo '<td class="text-center"><a href="#modal-noigiaohang" data-toggle="modal" class="noigiaohang" name="'.$dh['_id'].'"><i class="fa fa-user-plus '.$class.'"></td>';
                            echo '<td class="text-center"><a href="get.delete.html?id='.$dh['_id'].'&collect=donhang&url='.$_SERVER['REQUEST_URI'].'" onclick="return confirm(\'Chắc chắn xóa?\')"><i class="fa fa-trash"></i></a></td>';
                		}
                		echo '<td class="text-center"><a href="get.chitietdonhang.html?id='.$dh['_id'].'#modal-chitietdonhang" data-toggle="modal" class="chitietdonhang"><i class="fa fa-eye"></i></a></td>';
                        echo '<td class="text-center"><a href="inhoadon.html?id='.$dh['_id'].'" class="open_window"><i class="fa fa-file-text"></i></a></td>';
                        echo '<td class="text-center"><a href="inphieugiaohang.html?id='.$dh['_id'].'" class="open_window"><i class="fa fa-truck"></i></a></td>';
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

<!--------------------- moddal chi tiết đơn hàng ---------------->
<div class="modal fade" id="modal-chitietdonhang">
	<div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Chi tiết đơn hàng</h4>
            </div>
            <div class="modal-body" id="content_chitet">

            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-sm btn-white" data-dismiss="modal">Đóng</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-noigiaohang">
<form action="post.donhang.html" method="POST" class="form-horizontal" data-parsley-validate="true" name="congtyform">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Thông tin nơi giao hàng</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-md-12 control-label text-left">Chuyển nơi giao hàng</label>
                    <div class="col-md-9">
                        <input type="hidden" name="id" class="id_donhang"/>
                        <input type="hidden" name="act" value="noigiaohang" />
                        <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                    <select name="id_congty" id="id_congty" class="form-control select2" style="width:100%;">
                    <?php
                    	if($danhmuccongty_list){
                    		foreach ($danhmuccongty_list as $dmct) {
                    			echo '<option value="'.$dmct['_id'].'">'.$dmct['ten'].'</option>';
                    		}
                    	}
                    ?>
                    </select>
                        <!--<input type="text" name="id_" id="diachi" value="" class="form-control" data-parsley-required="true"/>-->
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
</div>
<!--------------------- moddal tinh trang don hang ---------------->
<div class="modal fade" id="modal-tinhtrang">
<form action="post.donhang.html" method="POST" class="form-horizontal" data-parsley-validate="true" name="dinhkemform" id="dinhkemform">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Cập nhật tình trạng đơn hàng</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Tình trạng</label>
                    <div class="col-md-9">
                        <input type="hidden" name="id" class="id_donhang"/>
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
                    <label class="col-md-3 control-label">Thanh toán</label>
                    <div class="col-md-9">
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
                    <label class="col-md-3 control-label">Đính kèm:</label>
                    <div class="col-md-6">
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
                	<label class="col-md-3 control-label">Nội dung</label>
                    <div class="col-md-9">
                    	<textarea name="noidung" id="noidung" rows="5" placeholder="Nội dung tình trạng" class="form-control"></textarea>
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
</div>
<div style="clear:both;"></div>
<?php require_once('footer.php'); ?>
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="assets/plugins/parsley/dist/parsley.js"></script>
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/js/table-manage-default.demo.min.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script type="text/javascript" src="assets/js/dinhkem.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
    $(document).ready(function() {
        upload_files();delete_file();$(".progress").hide();
        $(".open_window").click(function(){
          window.open($(this).attr("href"), '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=0, left=100, width=1024, height=800');
          return false;
        });
    	$(".select2").select2();
    	$(".noigiaohang").click(function(){
    		var id_donhang = $(this).attr("name");
    		$(".id_donhang").val(id_donhang);
    	});
    	$(".tinhtrang").click(function(){
    		var id_donhang = $(this).attr("name");
    		$(".id_donhang").val(id_donhang);
    	});
    	$(".chitietdonhang").click(function(){
    		var _link = $(this).attr("href");
    		$.get(_link, function(data){
    			$("#content_chitet").html(data);
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
        App.init();
    });
</script>
