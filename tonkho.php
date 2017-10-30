<?php
require_once('header.php');
check_permis($users->is_admin());
$file_path = '';
$tonkho = new TonKho(); $danhmuccongty = new DanhMucCongTy();
if(isset($_POST['submit'])){
    $filename = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : '';
    $file_tmp = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : '';
    $file_path = 'uploads/' . date("YmdHi") . '_' . $filename;
    if(move_uploaded_file($file_tmp, $file_path)){
        $msg = 'Cập nhật thành công!';
    } else {
        $msg = 'Không thể cập nhật';
    }
}
$id_user = $users->get_userid();
$tonkho->id_user = $id_user;
?>
<link href="assets/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet" />
<link href="assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />
<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" class="form-horizontal" data-parsley-validate="true" enctype="multipart/form-data">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title"><i class="fa fa-search"></i> Chọn tập tin CSV để cập nhật tồn kho</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Chọn tập tin:</label>
                    <div class="col-md-3">
                        <span class="btn btn-success fileinput-button">
                            <i class="fa fa-plus"></i>
                            <span>Chọn tập tin CSV...</span>
                            <input type="file" name="file" id="file" accept=".csv">
                        </span>
                    </div>
                    <div class="col-md-3 text-left">
                        <button name="submit" id="submit" value="OK" class="btn btn-primary"><i class="fa fa-check-circle-o"></i> Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<?php if(isset($_POST['submit'])): ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-body">
            <?php
            if(file_exists($file_path)){
                $arr_file = explode('.', $file_path);
                $ext = end($arr_file);
                if(strtolower($ext) == 'csv'){
                    $row = 0;
                    if (($handle = fopen($file_path, "r")) !== FALSE) {
                    echo '<b>Cập nhật tồn kho tài khoản: ' . $user_default['person'] . ' --- thời gian: '. date("d/m/Y H:i") . '</b><hr />';
                        while (($data = fgetcsv($handle, '', ",")) !== FALSE) {
                            if($row > 0){
                                $tonkho->id_sanpham = trim($data[0]);
                                $tonkho->tensanpham = $data[1];
                                $tonkho->donvitinh = $data[2];
                                $tonkho->soluong = $data[3];
                                $tonkho->erp_id = trim($data[4]);
                                if($tonkho->check_exists()){
                                    $tonkho->update_tonkho();
                                } else if($danhmuccongty->check_exist_by_erpid(trim($data[4]))){
                                    $tonkho->insert();
                                }
                                echo 'ID: '  . $data[0] . '<br />';
                                echo 'Name: '  . $data[1] . '<br />';
                                echo 'ĐVT: '  . $data[2] . '<br />';
                                echo 'So lượng: '  . $data[3] . '<br />';
                                echo 'ID cửa hàng: '  . $data[4] . '<br />';
                                echo '<hr/>';
                            }
                            $row++;
                        }
                    }
                } else {
                    echo 'Tập tin không đúng CSV';
                }
            } else {
                echo 'Không thấy tập tin';
            }
            ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php require_once('footer.php'); ?>
<div style="clear:both;"></div>
<?php require_once('footer.php'); ?>
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
    $(document).ready(function() {
        App.init();
    });
</script>
