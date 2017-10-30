<?php
require_once('header.php');
$gridfs = new GridFS();
$config = new Config(); $ht = $config->get_one();
if(isset($_POST['submit'])){
	$title = isset($_POST['title']) ? $_POST['title'] : '';
	$text = isset($_POST['text']) ? $_POST['text'] : '';
	$hinhanh_file = isset($_FILES["hinhanh"]["name"]) ? strtolower($_FILES["hinhanh"]["name"]) : '';
    $hinhanh_size = isset($_FILES["hinhanh"]["size"]) ? $_FILES["hinhanh"]["size"] : '';
    $hinhanh_type = isset($_FILES["hinhanh"]["type"]) ? $_FILES["hinhanh"]["type"] : '';
    $hinhanh_tmp = isset($_FILES['hinhanh']['tmp_name']) ? $_FILES['hinhanh']['tmp_name'] : '';
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

    $hinhanh_1_file = isset($_FILES["hinhanh_1"]["name"]) ? strtolower($_FILES["hinhanh_1"]["name"]) : '';
    $hinhanh_1_size = isset($_FILES["hinhanh_1"]["size"]) ? $_FILES["hinhanh_1"]["size"] : '';
    $hinhanh_1_type = isset($_FILES["hinhanh_1"]["type"]) ? $_FILES["hinhanh_1"]["type"] : '';
    $hinhanh_1_tmp = isset($_FILES['hinhanh_1']['tmp_name']) ? $_FILES['hinhanh_1']['tmp_name'] : '';
    $old_hinhanh_1 = isset($_POST['old_hinhanh_1']) ? $_POST['old_hinhanh_1'] : '';
    $temp_1 = explode(".", $hinhanh_1_file);
    if($hinhanh_1_file){
        $ext_1 = end($temp_1);
        if($hinhanh_1_size < $max_file_size && in_array($ext_1, $images_extension)){
            $gridfs->filename = $hinhanh_1_file;
            $gridfs->filetype = $hinhanh_1_type;
            $gridfs->tmpfilepath = $hinhanh_1_tmp;
            $gridfs->caption = $hinhanh_1_file;

        } else {
            $msg = 'Dung lượng hình ảnh quá lớn hoặc không đúng định dạng';
        }
    } else {
        $hinhanh_1 = $old_hinhanh_1;
    }

    $logo_left_file = isset($_FILES["logo_left"]["name"]) ? strtolower($_FILES["logo_left"]["name"]) : '';
    $logo_left_size = isset($_FILES["logo_left"]["size"]) ? $_FILES["logo_left"]["size"] : '';
    $logo_left_type = isset($_FILES["logo_left"]["type"]) ? $_FILES["logo_left"]["type"] : '';
    $logo_left_tmp = isset($_FILES['logo_left']['tmp_name']) ? $_FILES['logo_left']['tmp_name'] : '';
    $old_logo_left = isset($_POST['old_logo_left']) ? $_POST['old_logo_left'] : '';
    $temp_1 = explode(".", $logo_left_file);
    if($logo_left_file){
        $ext_1 = end($temp_1);
        if($logo_left_size < $max_file_size && in_array($ext_1, $images_extension)){
            $gridfs->filename = $logo_left_file;
            $gridfs->filetype = $logo_left_type;
            $gridfs->tmpfilepath = $logo_left_tmp;
            $gridfs->caption = $logo_left_file;

        } else {
            $msg = 'Dung lượng hình ảnh quá lớn hoặc không đúng định dạng';
        }
    } else {
        $logo_left = $old_logo_left;
    }

    $logo_right_file = isset($_FILES["logo_right"]["name"]) ? strtolower($_FILES["logo_right"]["name"]) : '';
    $logo_right_size = isset($_FILES["logo_right"]["size"]) ? $_FILES["logo_right"]["size"] : '';
    $logo_right_type = isset($_FILES["logo_right"]["type"]) ? $_FILES["logo_right"]["type"] : '';
    $logo_right_tmp = isset($_FILES['logo_right']['tmp_name']) ? $_FILES['logo_right']['tmp_name'] : '';
    $old_logo_right = isset($_POST['old_logo_right']) ? $_POST['old_logo_right'] : '';
    $temp_1 = explode(".", $logo_right_file);
    if($logo_right_file){
        $ext_1 = end($temp_1);
        if($logo_right_size < $max_file_size && in_array($ext_1, $images_extension)){
            $gridfs->filename = $logo_right_file;
            $gridfs->filetype = $logo_right_type;
            $gridfs->tmpfilepath = $logo_right_tmp;
            $gridfs->caption = $logo_right_file;

        } else {
            $msg = 'Dung lượng hình ảnh quá lớn hoặc không đúng định dạng';
        }
    } else {
        $logo_right = $old_logo_right;
    }

    $config->title = $title;
    $config->text = $text;
    if($hinhanh_file) $hinhanh = $gridfs->insert_files();
    if($hinhanh_1_file) $hinhanh_1 = $gridfs->insert_files();
    if($logo_left_file) $logo_left = $gridfs->insert_files();
    if($logo_right_file) $logo_right = $gridfs->insert_files();

    if($old_hinhanh && $hinhanh_file){
        $gridfs->id = $old_hinhanh; $gridfs->delete();
    }
    if($old_hinhanh_1 && $hinhanh_1_file){
        $gridfs->id = $old_hinhanh_1; $gridfs->delete();
    }
    if($old_logo_left && $logo_left_file){
        $gridfs->id = $old_logo_left; $gridfs->delete();
    }
    if($old_logo_right && $logo_right_file){
        $gridfs->id = $old_logo_right; $gridfs->delete();
    }
    $config->image = $hinhanh;
    $config->image_1 = $hinhanh_1;
    $config->logo_left = $logo_left;
    $config->logo_right = $logo_right;
    if($config->edit()) transfers_to('config.html?msg=Chỉnh sửa thành công');
}
?>
<link href="assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" class="form-horizontal" data-parsley-validate="true" name="hienthiform" enctype="multipart/form-data">
<h1 class="page-header"><i class="fa fa-cog"></i> CẤU HÌNH GIAO DIỆN ĐĂNG NHẬP</h1>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title"><i class="fa fa-list"></i> GIAO DIỆN ĐĂNG NHẬP</h4>
            </div>
            <div class="panel-body">
            	<div class="form-group">
            		<div class="col-md-4">
            			<input type="text" name="title" id="title" value="<?php echo $ht['title']; ?>" class="form-control" data-parsley-required="true" placeholder="ANOVA FARM" />            			
            		</div>
            		<div class="col-md-3">
            			<input type="text" name="text" id="text" value="<?php echo $ht['text']; ?>" class="form-control" data-parsley-required="true" placeholder="ANOVA FARM" />
            		</div>
            		<div class="col-md-3">
                        <span class="btn btn-success fileinput-button">
                            <i class="fa fa-plus"></i>
                            <span>Chọn hình ảnh (1920px x 1280px)</span>
                            <input type="file" name="hinhanh" id="hinhanh" accept="*/image">
                        </span>
                    </div>
                    <div class="col-md-2">
                    	<input type="hidden" name="old_hinhanh" id="old_hinhanh" value="<?php echo isset($ht['image']) ? $ht['image'] : ''; ?>"/>
                    	<?php
                    	if(isset($ht['image']) && $ht['image']){
                    		//$gridfs->id = $ht['hinhanh']; $object = $gridfs->get_one_file();
                    		echo '<img src="image.html?id='.$ht['image'].'" height="30"/>';
                    	}
                    	?>
                    </div>
            	</div>
            </div>
            <div class="form-group">
                <label class="col-md-7 control-label">Info Logo (*****)</label>
                <div class="col-md-3">
                    <span class="btn btn-success fileinput-button">
                        <i class="fa fa-plus"></i>
                        <span>Chọn hình ảnh (166px x 28px)......</span>
                        <input type="file" name="hinhanh_1" id="hinhanh_1" accept="*/image">
                    </span>
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="old_hinhanh_1" id="old_hinhanh_1" value="<?php echo isset($ht['image_1']) ? $ht['image_1'] : ''; ?>"/>
                    <?php
                    if(isset($ht['image_1']) && $ht['image_1']){
                        //$gridfs->id = $ht['hinhanh']; $object = $gridfs->get_one_file();
                        echo '<img src="image.html?id='.$ht['image_1'].'" width="50"/>';
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-7 control-label">Logo giao hàng (Trái)</label>
                <div class="col-md-3">
                    <span class="btn btn-success fileinput-button">
                        <i class="fa fa-plus"></i>
                        <span>Chọn hình ảnh (200px x 200px)</span>
                        <input type="file" name="logo_left" id="logo_left" accept="*/image">
                    </span>
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="old_logo_left" id="old_logo_left" value="<?php echo isset($ht['logo_left']) ? $ht['logo_left'] : ''; ?>"/>
                    <?php
                    if(isset($ht['logo_left']) && $ht['logo_left']){
                        //$gridfs->id = $ht['hinhanh']; $object = $gridfs->get_one_file();
                        echo '<img src="image.html?id='.$ht['logo_left'].'" width="50"/>';
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-7 control-label">Logo giao hàng (Phải)</label>
                <div class="col-md-3">
                    <span class="btn btn-success fileinput-button">
                        <i class="fa fa-plus"></i>
                        <span>Chọn hình ảnh (200px x 200px)</span>
                        <input type="file" name="logo_right" id="logo_right" accept="*/image">
                    </span>
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="old_logo_right" id="old_logo_right" value="<?php echo isset($ht['logo_right']) ? $ht['logo_right'] : ''; ?>"/>
                    <?php
                    if(isset($ht['logo_right']) && $ht['logo_right']){
                        //$gridfs->id = $ht['hinhanh']; $object = $gridfs->get_one_file();
                        echo '<img src="image.html?id='.$ht['logo_right'].'" width="50"/>';
                    }
                    ?>
                </div>
            </div>
            <div class="panel-footer text-center">
            	<button name="submit" id="submit" value="OK" class="btn btn-primary"><i class="fa fa-check-circle-o"></i> Cập nhật</button>
            </div>
        </div>
    </div>
</div>
</form>
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