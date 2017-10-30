<?php
require_once('header_none.php');
$sanpham = new SanPham();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$sanpham->id = $id; $sp = $sanpham->get_one();
?>

<div class="row">
    <div class="col-md-3">
    	<?php if(isset($sp['imageId']) && $sp['imageId']): ?>
    		<img src="<?php echo $sp['imageId']; ?>" />
    	<?php endif; ?>
    </div>
    <div class="col-md-9 pull-right">
    	<div class="row">
    		<label class="col-md-3 control-label text-right">Tên sản phẩm</label>
            <div class="col-md-9"><?php echo $sp['name']; ?></div>
    	</div>
    	<div class="row">
    		<label class="col-md-3 control-label text-right">Giá</label>
            <div class="col-md-9"><?php echo format_number($sp['price']); ?></div>
    	</div>
    	<div class="row">
    		<label class="col-md-3 control-label text-right">Mô tả</label>
            <div class="col-md-9"><?php echo $sp['content']; ?></div>
    	</div>
    </div>
</div>