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

?>