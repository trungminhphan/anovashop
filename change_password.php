<?php
require_once('header.php');
$act = isset($_GET['act']) ? $_GET['act'] : '';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$password = '';
$id = $users->get_userid();
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
    $act = isset($_POST['act']) ? $_POST['act'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $re_password = isset($_POST['re_password']) ? $_POST['re_password'] : '';
    $person = isset($_POST['person']) ? $_POST['person'] : '';
    $users->id = $id;
    $users->username = $username;$users->password = $password;
    $users->email = $email; $users->person = $person;
    if($password === $re_password){
        if($users->change_password()) transfers_to('change_password.html?msg=Chỉnh sửa thành công');
    } else {
        $msg = 'Mật khẩu không trùng khớp';
    }
}
if($id){
	$users->id = $id;
	$edit_user = $users->get_one();
	$id = $edit_user['_id'];
	$username = $edit_user['username'];
    $email = isset($edit_user['email']) ? $edit_user['email'] : '';
	$password = '';
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
                <h4 class="panel-title"><i class="fa fa-search"></i> Thay đổi mật khẩu người dùng</h4>
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
            		<label class="col-md-3 control-label">Mật khẩu mới:</label>
            		<div class="col-md-6">
						<input type="password" name="password" id="password" value="<?php echo isset($password) ? $password : ''; ?>" placeholder="Nhập mật khẩu" class="form-control" data-parsley-required="true" />
            		</div>
            	</div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Nhập lại mật khẩu mới:</label>
                    <div class="col-md-6">
                        <input type="password" name="re_password" id="re_password" value="<?php echo isset($re_password) ? $re_password : ''; ?>" placeholder="Nhập lại mật khẩu" class="form-control" data-parsley-required="true" />
                    </div>
                </div>
            	<div class="form-group">
            		<label class="col-md-3 control-label">Họ tên người được cấp tài khoản:</label>
            		<div class="col-md-6">
						<input type="text" name="person" id="person" value="<?php echo isset($person) ? $person : ''; ?>" placeholder="Nhập họ tên người được cấp" class="form-control" data-parsley-required="true">
            		</div>
            	</div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Địa chỉ Email:</label>
                    <div class="col-md-6">
                        <input type="email" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>" placeholder="Địa chỉ email" class="form-control" data-parsley-required="true">
                    </div>
                </div>
            </div>
            <div class="panel-footer text-center">
            	<button name="submit" id="submit" value="OK" class="btn btn-primary"><i class="fa fa-check-circle-o"></i> Cập nhật</button>
				<a href="index.html" class="btn btn-success"><i class="fa fa-mail-reply-all"></i> Trở về</a>
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
