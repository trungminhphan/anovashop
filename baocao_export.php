<?php
require_once('header_none.php');
//check_permis($users->is_admin());
$user_default = $users->get_one_default();
$donhang = new DonHang();$danhmuccongty = new DanhMuccongTy();$tonkho = new TonKho();
if(isset($_GET['submit'])){
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$id_congty = isset($_GET['id_congty']) ? $_GET['id_congty'] : '';
	$start_date = convert_date_yyyy_mm_dd_3($tungay, 0, 0, 0);
	$end_date = convert_date_yyyy_mm_dd_3($denngay, 23, 59, 59);
	if($start_date > $end_date){
		$msg = 'Chọn ngày sai';
	} else {
		$query = array();
        array_push($query, array('ngaymua' => array('$gte' => new MongoDate($start_date))));
        array_push($query, array('ngaymua' => array('$lte' => new MongoDate($end_date))));
        if($id_congty && $users->is_admin()){
            array_push($query, array('id_congty' => new MongoId($id_congty)));
        } else if($id_congty) {
            array_push($query, array('id_congty' => new MongoId($user_default['id_congty'])));
        }
        $q = array('$and' => $query);
		$donhang_list = $donhang->get_baocao_condition($q);
		//$donhang_list = $donhang->get_baocao($start_date, $end_date);
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
			$tencongty = $ct['ten'];
			$erpid = isset($ct['erp_id']) ? $ct['erp_id'] : '';
		} else {
			$tencongty = '';$erpid='';
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
                } else {
                    $soluongtonkho = $tonkho->sum_soluong_by_id_sanpham($order['itemId']);
                }
                if($soluongtonkho) {
                    $sl= $soluongtonkho[0]['sum_soluong'];
                } else {
                    $sl = 0;
                }
				$thanhtien = $order['quantity'] * $order['sellingprice'];
				$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $dh['id']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $ngaymua);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $dh['fullname']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $dh['address'] . ', ' .$quan_huyen);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $dh['phone']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $order['name']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$i, $order['quantity']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $sl);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$i, $order['sellingprice']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$i, $thanhtien);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$i, $tencongty);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$i, $tinhtrang);
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

