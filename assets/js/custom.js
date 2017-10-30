function convert_str2number(number){
	var str_number = '';
	var strlen = number.length;
	for(i=0; i<strlen; i++){
		if(number.charAt(i) == ','){
			str_number += '.';
		} else if(number.charAt(i) == '.') {
		} else {
			str_number += number.charAt(i);
		}
	}
	return parseFloat(str_number);
}

function format_number(number){
	var str_number = '';
	var strlen = number.length;
	for(i=0; i<strlen; i++){
		if(number.charAt(i) == '.'){
			str_number += ',';
		} else if(number.charAt(i) == ','){
			str_number += '.';
		} else {
			str_number += number.charAt(i);
		}
	}
	return str_number;
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

/*function set_hienthi(){
	$(".sethienthi").click(function(){
		var _this = $(this); var _link = $(this).attr("href");
		$.get(_link, function(data){
			_this.parent(".link_hienthi").html(data);
			set_hienthi();
		});
	});
}*/