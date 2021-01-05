<?php
	function dateFormat($date){
		return date(' Y-m-d ',strtotime($date));
	}
	function dateTimeFormat($date){
		return date(' d MM Y H:i ');
	}
	function timeFormat($date){
		return date(' H:i ');
	}
	function date_indo($tanggal){
		$tanggal = date('Y-m-d',strtotime($tanggal));
		$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $tanggal);
		
		// variabel pecahkan 0 = tahun
		// variabel pecahkan 1 = bulan
		// variabel pecahkan 2 = tanggal
	 
		return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
	}
	//// numbering format

	function zeroFill($number,$jumlah){
		return sprintf("%0".$jumlah."d", $number);
		// return  $number;
	}

	function backofficeDomain(){
		return "https://backoffice.kapcake.com";
	}

	// function distance($lat1, $lon1, $lat2, $lon2) { 
	// 	$pi80 = M_PI / 180; 
	// 	$lat1 *= $pi80; 
	// 	$lon1 *= $pi80; 
	// 	$lat2 *= $pi80; 
	// 	$lon2 *= $pi80; 
	// 	$r = 6372.797; // mean radius of Earth in km 
	// 	$dlat = $lat2 - $lat1; 
	// 	$dlon = $lon2 - $lon1; 
	// 	$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2); 
	// 	$c = 2 * atan2(sqrt($a), sqrt(1 - $a)); 
	// 	$km = $r * $c; 
	// 	//echo ' '.$km; 
	// 	return $km; 
	// }

?>