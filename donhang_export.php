<?php
require_once('header_none.php');
check_permis($users->is_admin() || $users->is_store());
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$donhang = new DonHang();$danhmuccongty = new DanhMuccongTy();
$danhmuccongty_list = $danhmuccongty->get_all_list();
if($users->is_admin()){
  $donhang_list = $donhang->get_all_list();
} else {
  $donhang->id_congty = $id_congty;
  $donhang_list = $donhang->get_list_by_congty();
}


require_once('cls/PHPExcel.php');
$inputFileName = 'templates/donhang.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
// Set document properties
$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
               ->setLastModifiedBy("Phan Minh Trung")
               ->setTitle("Office 2007 XLSX Test Document")
               ->setSubject("Office 2007 XLSX Test Document")
               ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
               ->setKeywords("office 2007 openxml php")
               ->setCategory("Bao cao don hang ANOVA");
$objPHPExcel->setActiveSheetIndex(0);

if(isset($donhang_list) && $donhang_list){
  $i = 4;
  foreach($donhang_list as $dh){
    if(isset($dh['id_congty']) && $dh['id_congty']){
      $danhmuccongty->id = $dh['id_congty'];$ct = $danhmuccongty->get_one();
      $tencongty = $ct['ten'];
    } else {
      $tencongty = '';
    }
    $tt = isset($dh['tinhtrang'][0]['t']) ? $dh['tinhtrang'][0]['t'] : 0;
    $t = isset($dh['tinhtrang'][0]['tt']) ? $dh['tinhtrang'][0]['tt'] : 0;
    $ngaymua = date("d/m/Y", $dh['ngaymua']->sec);
    $tinhtrang = $arr_tinhtrang[$tt] . '/' . 'Thanh toán: ' .$arr_thanhtoan[$t];
    $quan_huyen = $dh['addressLevelThreeName'] . ', '.$dh['addressLevelTwoName'].', '.$dh['addressLevelOneName'];
    if($dh['orderItems']){
      foreach($dh['orderItems'] as $order){
        $thanhtien = $order['quantity'] * $order['sellingprice'];
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $dh['id']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $ngaymua);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $dh['fullname']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $dh['address'] . ', ' .$quan_huyen);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $dh['phone']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $order['name']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$i, $order['quantity']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $order['sellingprice']);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$i, $thanhtien);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$i, $tencongty);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$i, $tinhtrang);
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

