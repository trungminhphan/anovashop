<?php
//require_once('inc/functions.inc.php');
$url = 'http://103.27.236.20/anovashop/get.chitietdonhang_email.html?id=596c9879c125831d733fbaf6';
//echo  get_remote_data($url);
//http://traceweb.org/giaohang/get.chitietdonhang_email.html?id=596c9879c125831d733fbaf6

include('inc/Requests/library/Requests.php');
Requests::register_autoloader();
$response = Requests::get($url);

echo $response->body;

/*require_once('inc/barcode/BarcodeGenerator.php');
require_once('inc/barcode/BarcodeGeneratorHTML.php');

$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);*/
?>