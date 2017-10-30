<?php
require_once('header.php');
check_permis($users->is_admin());
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$gridfs = new GridFS();$danhmuccongty = new DanhMucCongTy();
$danhmuccongty_list = $danhmuccongty->get_all_list();
$sum_roles = 0; $roles = 0; $password = '';$hinhanh = '';
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
    $act = isset($_POST['act']) ? $_POST['act'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $roles = isset($_POST['roles']) ? $_POST['roles'] : '';
    $id_congty = isset($_POST['id_congty']) ? $_POST['id_congty'] : '';
    $person = isset($_POST['person']) ? $_POST['person'] : '';
    if($roles){
        foreach($roles as $key=>$value){
            $sum_roles += intval($value);
        }
    }
    $hinhanh_file = $_FILES["hinhanh"]["name"];
    $hinhanh_size = $_FILES["hinhanh"]["size"];
    $hinhanh_type = $_FILES["hinhanh"]["type"];
    $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
    $old_hinhanh = isset($_POST['old_hinhanh']) ? $_POST['old_hinhanh'] : '';
    $temp = explode(".", $hinhanh_file);
    if($hinhanh_file){
        $ext = end($temp);
        if($hinhanh_size < $max_file_size && in_array($ext, $images_extension)){
            $gridfs->filename = $hinhanh_file;
            $gridfs->filetype = $hinhanh_type;
            $gridfs->tmpfilepath = $hinhanh_tmp;
            $gridfs->caption = $hinhanh_file;

        } else {
            $msg = 'Dung lượng hình ảnh quá lớn hoặc không đúng định dạng';
        }
    } else {
        $hinhanh = $old_hinhanh;
    }
    $users->id = $id;
    $users->username = $username;
    $users->email = $email;
    $users->password = $password;
    $users->roles = $sum_roles;
    $users->person = $person;
    $users->id_congty = $id_congty;

    if($id && $act == 'edit'){
        if($hinhanh_file) $hinhanh = $gridfs->insert_files();
        if($old_hinhanh && $hinhanh_file){
            $gridfs->id = $old_hinhanh; $gridfs->delete();
       }
        $users->hinhanh = $hinhanh;
        if($users->edit()) transfers_to('users.html?msg=Chỉnh sửa thành công');
    } else {
        if($users->check_exist_username())        {
            transfers_to('users.html?msg=Tài khoản người dùng đã tồn tại');
        } else {
            if($hinhanh_file) $hinhanh = $gridfs->insert_files();
            $users->hinhanh = $hinhanh;
            if($users->insert()) transfers_to('users.html?msg=Thêm thành công');
            else echo transfers_to('users.html?msg=Không thể thành công');
        }
    }

}

if($id){
	$users->id = $id;
	$edit_user = $users->get_one();
	$id = $edit_user['_id'];
	$username = $edit_user['username'];
    $email = isset($edit_user['email']) ? $edit_user['email'] : '';
	$password = '';
    $hinhanh = isset($edit_user['hinhanh']) ? $edit_user['hinhanh'] : '';
    $id_congty = isset($edit_user['id_congty']) ? $edit_user['id_congty'] : '';
	$roles = $edit_user['roles'];
	$person = $edit_user['person'];
}
?>
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
<link href="assets/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet" />
<link href="assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />
<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="adduserform" class="form-horizontal" data-parsley-validate="true" enctype="multipart/form-data">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title"><i class="fa fa-search"></i> Thông tin tài khoản người dùng</h4>
            </div>
            <div class="panel-body">
            	<div class="form-group">
            		<label class="col-md-3 control-label">Tài khoản:</label>
            		<div class="col-md-6">
            			<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>" />
                        <input type="hidden" name="act" id="act" value="<?php echo isset($act) ? $act : ''; ?>" />
						<input type="text" name="username" id="username" value="<?php echo isset($username) ? $username : ''; ?>" placeholder="Nhập tài khoản" class="form-control" <?php echo $id ? 'readonly' : ''; ?> data-parsley-required="true"/>
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="col-md-3 control-label">Mật khẩu:</label>
            		<div class="col-md-6">
						<input type="password" name="password" id="password" value="" placeholder="Nhập mật khẩu" class="form-control" data-parsley-required="true" />
            		</div>
            	</div>
            	<div class="form-group">
            		<label class="col-md-3 control-label">Họ tên người được cấp tài khoản:</label>
            		<div class="col-md-6">
						<input type="text" name="person" id="person" value="<?php echo isset($person) ? $person : ''; ?>" placeholder="Nhập họ tên người được cấp" class="form-control" data-parsley-required="true">
            		</div>
            	</div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Email:</label>
                    <div class="col-md-6">
                        <input type="email" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>" placeholder="Địa chỉ email" class="form-control" data-parsley-required="true">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Hình ảnh:</label>
                    <div class="col-md-6">
                        <span class="btn btn-success fileinput-button">
                            <i class="fa fa-plus"></i>
                            <span>Chọn hình ảnh...</span>
                            <input type="file" name="hinhanh" id="hinhanh" accept="*/image">
                        </span>
                    </div>
                    <div class="col-md-3">
                        <input type="hidden" name="old_hinhanh" id="old_hinhanh" value="<?php echo isset($hinhanh) ? $hinhanh : ''; ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Trực thuộc nơi giao hàng:</label>
                    <div class="col-md-6">
                    <select name="id_congty" id="id_congty" class="select2" style="width:100%">
                    <?php
                    if($danhmuccongty_list){
                        foreach($danhmuccongty_list as $ct){
                            echo '<option value="'.$ct['_id'].'">'.$ct['ten'].'</option>';
                        }
                    }
                    ?>
                    </select>
                    </div>
                </div>
            	<div class="form-group">
            		<label class="col-md-3 control-label">Phân quyền:</label>
            		<div class="col-md-2">
						<input type="checkbox" name="roles[]" data-render="switchery" data-theme="default"  value="1" <?php echo  ($roles & ADMIN) ? ' checked': ''; ?>>  ADMIN
            		</div>
            		<div class="col-md-2">
						<input type="checkbox" name="roles[]" value="2" data-render="switchery" data-theme="default" <?php echo  ($roles & STORE) ? ' checked': ''; ?>>  STORE
            		</div>
            	</div>
            </div>
            <div class="panel-footer text-center">
            	<button name="submit" id="submit" value="OK" class="btn btn-primary"><i class="fa fa-check-circle-o"></i> Cập nhật</button>
				<a href="users.html" class="btn btn-success"><i class="fa fa-mail-reply-all"></i> Trở về danh mục Tài khoản</a>
            </div>
        </div>
    </div>
</div>
</form>
<?php require_once('footer.php'); ?>
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="assets/plugins/switchery/switchery.min.js"></script>
<script src="assets/plugins/parsley/dist/parsley.js"></script>
<script src="assets/js/form-slider-switcher.demo.min.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
    $(document).ready(function() {
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
        App.init();FormSliderSwitcher.init();
    });
</script>
