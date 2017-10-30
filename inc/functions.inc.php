<?php
function s2n($string){
	$len_of_string = strlen($string);
	$i = 0;
	$number = '';
	for($i=0; $i<$len_of_string; $i++){
		if($string[$i] != ".") $number .= $string[$i];
	}
	$number = str_replace(",","",$number);
	//doubleval($number);
	return $number;
}

function convert_string_number($string){
	$len_of_string = strlen($string);
	$i = 0;
	$number = '';
	for($i=0; $i<$len_of_string; $i++){
		if($string[$i] != ".") $number .= $string[$i];
	}
	$number = str_replace(",",".",$number);
	doubleval($number);
	return $number;
}

function transfers_to($url){ 	header('Location: ' . $url); }

function format_number($number){
	return number_format($number, 0, ",", ".");
}

function format_decimal($number, $dec){
	return number_format($number, $dec, ",", ".");
}
function format_date($date){
	return date("d/m/Y",strtotime($date));
}
function show_gioitinh($gioitinh){
	if($gioitinh == 1) return 'Nam';
	else return 'Nữ';
}
function quote_smart($value){
    if(get_magic_quotes_gpc()){
		$value=stripcslashes($value);    
    }
	$value=addslashes($value);
	return $value;    
}
function convert_date_yyyy_mm_dd($str_date){
	$s = explode ("/", $str_date);
	return strtotime($s[2] . '-'. $s[1] . '-' . $s[0] . ' 00:00:00');
}

function convert_date_yyyy_mm_dd_1($str_date, $gio, $phut){
	$s = explode ("/", $str_date);
	return strtotime($s[2] . '-'. $s[1] . '-' . $s[0] . ' '.$gio.':'.$phut.':00');
}

function convert_date_yyyy_mm_dd_2($str_date, $gio, $phut){
	$s = explode ("/", $str_date);
	return strtotime($s[0] . '-'. $s[1] . '-' . $s[2] . ' '.$gio.':'.$phut.':00');
}

function convert_date_yyyy_mm_dd_3($str_date, $gio, $phut, $giay){
	$s = explode ("/", $str_date);
	return strtotime($s[2] . '-'. $s[1] . '-' . $s[0] . ' '.$gio.':'.$phut.':'.$giay);
}


function convert_date_mm_yyyy($string_date){
	$s = explode ("/", $string_date);
	return strtotime($s[1] . '-'. $s[0] . '-01 00:00:00');
}

function convert_date_dd_mm_yyyy($string_date){
	if(strlen($string_date) >= 8){
		$s = explode ("/", $string_date);
		if($s[2] && $s[1] && $s[0]){
			return strtotime($s[2].'-'.$s[1].'-'.$s[0] . ' 00:00:00');
		} else {
			return '';
		}
	} else {
		return strtotime($string_date . '-01-01 00:00:00');
	}
}

function checkDateTime($data) {
    if (date('Y-m-d H:i:s', strtotime($data)) == $data) {
        return true;
    } else {
        return false;
    }
}

function show_icon($icon){
	$str_icon = '';
	switch(strtolower($icon)){
		case 'pdf': $str_icon = 'mif-file-pdf'; break;
		case 'doc': $str_icon = 'mif-file-word'; break;
		case 'docx': $str_icon = 'mif-file-word'; break;
		case 'ppt': $str_icon = 'mif-file-powerpoint'; break;
		case 'pptx': $str_icon = 'mif-file-powerpoint'; break;
		case 'xls': $str_icon = 'mif-file-excel'; break;
		case 'xlsx': $str_icon = 'mif-file-excel'; break;
		case 'zip': $str_icon = 'mif-file-zip'; break;
		case 'rar': $str_icon = 'mif-file-zip'; break;
		case '7z': $str_icon = 'mif-file-zip'; break;
		case 'jpg': $str_icon = 'mif-images'; break;
		case 'png': $str_icon = 'mif-images'; break;
		case 'jpeg': $str_icon = 'mif-images'; break;
		case 'gif': $str_icon = 'mif-images'; break;
		default: 
			$str_icon = 'mif-attachment';
	}

	return '<span class="'.$str_icon.'"></span>';
}

//tinh so ngay tru ngay thu 7 & CN
function tinhngay($today, $songay){
	$ngaynghi = 0;$day = '';
	for($i=0; $i<$songay; $i++){
		$day = strtotime(date("Y-m-d", strtotime($today)) . " +".$i." day");
		if(date("D", $day) == 'Sat' || date("D", $day)=='Sun'){
			$ngaynghi++;
		}
	}
	$songay = $songay + $ngaynghi;
	$day = strtotime(date("Y-m-d", strtotime($today)) . " +".$songay." day");
	//return date("Y-m-d", $day);
	return $day();
}

function dksort($array, $case){
    if(array_key_exists($case,$array)){
        $a[$case] = $array[$case];
        foreach($array as $key=>$val){
            if($case==$key){

            }else{
                $a[$key] = $array[$key];
            }
        }
    }
    return $a;
}

function sort_array($arrays, $orderby, $sortby){
	foreach ($arrays as $id => $array) {
		$array_sort[$id]   = $array[$orderby];
	}
	// Sort the data with weight descending, specialties ascending
	// Add $data as the last parameter, to sort by the common key
	$keys = array_keys($arrays);
	array_multisort(
		$array_sort, $sortby, SORT_STRING,
		$arrays, $keys
	);
	$arrays = array_combine($keys, $arrays);
	return $arrays;
}
function br2nl( $input ) {
 return preg_replace('/<br(\s+)?\/?>/i', "\n", $input);
}

function check_permis($roles){
	if(!$roles){
		echo '
			<div class="row">
		    <div class="col-md-12">
		        <div class="panel panel-danger">
		            <div class="panel-heading">
		                <h4 class="panel-title">Bạn không có quyền</h4>
		            </div>
		            <div class="panel-body">
		                <p>Bạn không được cấp quyền truy cập vào chức năng này. <a href="index.html">Trở về</a></p>
		            </div>
		        </div>
		    </div>
		</div>
		';
		require_once('footer.php');

	echo '<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<script>
	    $(document).ready(function() {
	        App.init();
	    });
	</script>';
		exit();
	}
}

function get_char($str, $from, $to){
	$t = '';
	for($i = $from; $i <= $to; $i++ ){
		$t .= $str[$i];
	}
	return $t;
}

function get_remote_data($url, $post_paramtrs = false) {
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    if ($post_paramtrs) {
        curl_setopt($c, CURLOPT_POST, TRUE);
        curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&" . $post_paramtrs);
    } curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
    curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
    curl_setopt($c, CURLOPT_MAXREDIRS, 10);
    $follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
    if ($follow_allowed) {
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
    }curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_TIMEOUT, 60);
    curl_setopt($c, CURLOPT_AUTOREFERER, true);
    curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
    $data = curl_exec($c);
    $status = curl_getinfo($c);
    curl_close($c);
    preg_match('/(http(|s)):\/\/(.*?)\/(.*\/|)/si', $status['url'], $link);
    $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si', '$1=$2' . $link[0] . '$3$4$5', $data);
    $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si', '$1=$2' . $link[1] . '://' . $link[3] . '$3$4$5', $data);
    if ($status['http_code'] == 200) {
        return $data;
    } elseif ($status['http_code'] == 301 || $status['http_code'] == 302) {
        if (!$follow_allowed) {
            if (empty($redirURL)) {
                if (!empty($status['redirect_url'])) {
                    $redirURL = $status['redirect_url'];
                }
            } if (empty($redirURL)) {
                preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);
                if (!empty($m[2])) {
                    $redirURL = $m[2];
                }
            } if (empty($redirURL)) {
                preg_match('/href\=\"(.*?)\"(.*?)here\<\/a\>/si', $data, $m);
                if (!empty($m[1])) {
                    $redirURL = $m[1];
                }
            } if (!empty($redirURL)) {
                $t = debug_backtrace();
                return call_user_func($t[0]["function"], trim($redirURL), $post_paramtrs);
            }
        }
    } return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:" . json_encode($status) . "<br/><br/>Last data got<br/>:$data";
}
?>