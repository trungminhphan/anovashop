<?php
require_once('header.php');
check_permis($users->is_admin());
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$danhmuccongty = new DanhMucCongTy();
$danhmuccongty_list = $danhmuccongty->get_all_list();
$diachi = new DiaChi();
$diachi_list = $diachi->get_list_condition(array('parentid' => '-1'));
?>
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
                <h4 class="panel-title"><i class="fa fa-list"></i> Danh mục nơi giao hàng</h4>
            </div>
            <div class="panel-body">
                <a href="#modal-dmcongty" data-toggle="modal" class="btn btn-primary m-10 themdmcongty"><i class="fa fa-plus"></i> Thêm mới</a>
                <table id="data-table" class="table table-striped table-bordered table-hovered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th width="100">ERP ID</th>
                            <th>Tên nơi giao hàng</th>
                            <th>Địa chỉ 1</th>
                            <th>Địa chỉ 2</th>
                            <th>Địa chỉ 3</th>
                            <th>Điện thoại</th>
                            <th>Tên đường</th>
                            <th class="text-center"><i class="fa fa-trash"></i></th>
                            <th class="text-center"><i class="fa fa-pencil"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($danhmuccongty_list){
                        $i = 1;
                        foreach ($danhmuccongty_list as $dm) {
                            if(isset($dm['addresslevelone'])){
                                $dc1 = $diachi->get_one_by_id($dm['addresslevelone']);
                                $diachi1 = $dc1['name'];
                            } else { $diachi1 = ''; }
                            if(isset($dm['addressleveltwo'])){
                                $dc2 = $diachi->get_one_by_id($dm['addressleveltwo']);
                                $diachi2 = $dc2['name'];
                            } else {
                                $diachi2 = '';
                            }
                            if(isset($dm['addresslevelthree'])){
                                $dc3 = $diachi->get_one_by_id($dm['addresslevelthree']);
                                $diachi3 = $dc3['name'];
                            } else {
                                $diachi3 = '';
                            }
                            echo '<tr>
                                <td>'.$i.'</td>
                                <td>'.(isset($dm['erp_id']) ? $dm['erp_id'] : '').'</td>
                                <td>'.$dm['ten'].'</td>
                                <td style="font-size:11px;" width="120">'.$diachi1.'</td>
                                <td style="font-size:11px;" width="120">'.$diachi2.'</td>
                                <td style="font-size:11px;" width="140">'.$diachi3.'</td>
                                <td style="font-size:11px;">'.$dm['diachi'].'</td>
                                <td style="font-size:11px;">'.(isset($dm['tenduong']) ? $dm['tenduong'] : '').'</td>
                                <td class="text-center"><a href="get.danhmuccongty.html?id='.$dm['_id'].'&act=del" onclick="return confirm(\'Chắc chắn muốn xoá?\');"><i class="fa fa-trash"></i></a></td>
                                <td class="text-center"><a href="get.danhmuccongty.html?id='.$dm['_id'].'&act=edit#modal-dmcongty" data-toggle="modal" class="suadmcongty"><i class="fa fa-pencil"></i></a></td>
                            </tr>';$i++;
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-dmcongty">
<form action="post.danhmuccongty.html" method="POST" class="form-horizontal" data-parsley-validate="true" name="congtyform">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Thông tin nơi giao hàng</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">ERP ID</label>
                    <div class="col-md-9">
                        <input type="hidden" name="id" id="id" />
                        <input type="hidden" name="act" id="act" />
                        <input type="hidden" name="url" id="url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                        <input type="text" name="erp_id" id="erp_id" value="" class="form-control" data-parsley-required="true"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Tên nơi giao hàng</label>
                    <div class="col-md-9">
                        <input type="text" name="ten" id="ten" value="" class="form-control" data-parsley-required="true"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Địa chỉ cấp 1</label>
                    <div class="col-md-9">
                        <select name="addresslevelone" id="addresslevelone" class="form-control select2" style="width:100%;">
                            <option value="">Chọn địa chỉ</option>
                        <?php
                        if($diachi_list){
                            foreach ($diachi_list as $dc) {
                                echo '<option value="'.$dc['id'].'">'.$dc['name'].'</option>';
                            }
                        }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Địa chỉ cấp 2</label>
                    <div class="col-md-9">
                        <select name="addressleveltwo" id="addressleveltwo" class="form-control select2" style="width:100%;">
                            <option value="">Chọn địa chỉ</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Địa chỉ cấp 3</label>
                    <div class="col-md-9">
                        <select name="addresslevelthree" id="addresslevelthree" class="form-control select2" style="width:100%;">
                            <option value="">Chọn địa chỉ</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Điện thoại</label>
                    <div class="col-md-9">
                        <input type="text" name="diachi" id="diachi" value="" class="form-control" data-parsley-required="true"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Tên đường:</label>
                    <div class="col-md-9">
                        <input type="text" name="tenduong" id="tenduong" value="" class="form-control" placeholder="Tên đường, số nhà,..." />
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
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
    $(document).ready(function() {
        $("#addresslevelone").change(function(){
            var _this = $(this);
            var _id = $(this).val();
            $.get("get.diachi.php?id=" + _id, function(data){
                $("#addressleveltwo").html(data);
            });
        });
        $("#addressleveltwo").change(function(){
            var _this = $(this);
            var _id = $(this).val();
            $.get("get.diachi.php?id=" + _id, function(data){
                $("#addresslevelthree").html(data);
            });
        });

        $("#themdmcongty").click(function(){
            $("#id").val();$("#act").val();
        });
        $(".select2").select2();
        $(".suadmcongty").click(function(){
            var _link = $(this).attr("href");
            $.getJSON(_link, function(data){
                $("#id").val(data.id); $("#act").val(data.act);
                $("#erp_id").val(data.erp_id);$("#ten").val(data.ten); $("#diachi").val(data.diachi);
                $("#tenduong").val(data.tenduong);
                $("#addresslevelone").val(data.diachi1);$("#addresslevelone").select2();
                if(data.diachi1){
                    $.get("get.diachi.php?id=" + data.diachi1, function(data1){
                        $("#addressleveltwo").html(data1);
                        $("#addressleveltwo").val(data.diachi2);$("#addressleveltwo").select2();
                    });
                }
                if(data.diachi2){
                    $.get("get.diachi.php?id=" + data.diachi2, function(data2){
                        $("#addresslevelthree").html(data2);
                        $("#addresslevelthree").val(data.diachi3);$("#addresslevelthree").select2();
                    });
                }
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
