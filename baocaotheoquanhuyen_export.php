<?php
require_once('header_none.php');
check_permis($users->is_admin());
$donhang = new DonHang();$danhmuccongty = new DanhMuccongTy();$tonkho = new TonKho();
$danhmuccongty_list = $danhmuccongty->get_all_list();
$sanpham = new SanPham();$diachi = new DiaChi();
$diachi_list = $diachi->get_list_condition(array('parentid' => '-1'));
$id_diachi1=0;$id_diachi2=0;
if(isset($_GET['submit'])){
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
    $id_diachi1 = isset($_GET['id_diachi1']) ? $_GET['id_diachi1'] : '';
    $id_diachi2 = isset($_GET['id_diachi1']) ? $_GET['id_diachi2'] : '';
	  $start_date = convert_date_yyyy_mm_dd_3($tungay, 0, 0, 0);
	  $end_date = convert_date_yyyy_mm_dd_3($denngay, 23, 59, 59);
    //$list_congty = $danhmuccongty->get_list_condition(array('addresslevelone' => $id_diachi1, 'addressleveltwo' => $id_diachi2));
    //$arr_congty = array();
    //if($list_congty){
    //    foreach($list_congty as $key => $value){
    //        $arr_congty[] = new MongoId($value['_id']);
    //    }
    //}
	if($start_date > $end_date){
		$msg = 'Chọn ngày sai';
	} else {
        $query = array();
        array_push($query, array('ngaymua' => array('$gte' => new MongoDate($start_date))));
        array_push($query, array('ngaymua' => array('$lte' => new MongoDate($end_date))));
        if($id_diachi1){
            array_push($query, array('addresslevelone' => $id_diachi1));
        }
        if($id_diachi2){
            array_push($query, array('addressleveltwo' => $id_diachi2));
        }
        if(!$users->is_admin()){
            array_push($query, array('id_congty' => $user_default['id_congty']));
        }
        $q = array('$and' => $query);
		$donhang_list = $donhang->get_baocao_condition($q);
	}
}

require_once('cls/PHPExcel.php');
$inputFileName = 'templates/baocao.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
// Set document properties
$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
							 ->setLastModifiedBy("Phan Minh Trung")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Bao cao don hang ANOVA");
$str_a2 = 'Từ ngày: ' . $tungay . '            Đến ngày: ' . $denngay;
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->setActiveSheetIndex()->setCellValue('A2', $str_a2);

if(isset($donhang_list) && $donhang_list){
	$i = 4;
	foreach($donhang_list as $dh){
		if(isset($dh['id_congty']) && $dh['id_congty']){
			$danhmuccongty->id = $dh['id_congty'];$ct = $danhmuccongty->get_one();
			$tencongty = $ct['ten'];$erpid = isset($ct['erp_id']) ? $ct['erp_id'] : '';
		} else {
			$tencongty = '';$erpid = '';
		}
		$tt = isset($dh['tinhtrang'][0]['t']) ? $dh['tinhtrang'][0]['t'] : 0;
		$t = isset($dh['tinhtrang'][0]['tt']) ? $dh['tinhtrang'][0]['tt'] : 0;
		$ngaymua = date("d/m/Y", $dh['ngaymua']->sec);
		$tinhtrang = $arr_tinhtrang[$tt] . '/' . 'Thanh toán: ' .$arr_thanhtoan[$t];
		$quan_huyen = $dh['addressLevelThreeName'] . ', '.$dh['addressLevelTwoName'].', '.$dh['addressLevelOneName'];
		if($dh['orderItems']){
			foreach($dh['orderItems'] as $order){
                if($erpid){
                    $soluongtonkho = $tonkho->sum_soluong_by_id_sanpham_erp($order['itemId'], $erpid);
                    $soluongtonkhotong = $tonkho->sum_soluong_by_id_sanpham($order['itemId']);
                } else {
                    $soluongtonkho = '';
                    $soluongtonkhotong = $tonkho->sum_soluong_by_id_sanpham($order['itemId']);
                }
               if($soluongtonkho) {
                    $sl= $soluongtonkho[0]['sum_soluong'];
                } else { $sl = 0; }
                if($soluongtonkhotong){
                    $slt =$soluongtonkhotong[0]['sum_soluong'];
                } else {$slt = 0;}
				$thanhtien = $order['quantity'] * $order['sellingprice'];
				$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $dh['id']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $ngaymua);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $dh['fullname']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $dh['address'] . ', ' .$quan_huyen);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $dh['phone']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $order['name']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$i, $order['quantity']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $sl);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$i, $slt);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$i, $order['sellingprice']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$i, $thanhtien);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$i, $tencongty);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('M'.$i, $tinhtrang);
				$i++;
			}
		}
	}
}

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="baocao_'.date("YmdHis").'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>

