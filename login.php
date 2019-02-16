<?php
	$conn = oci_connect("pz395077", "dupa123", null, 'AL32UTF8');
	
	if (!$conn) {
		$e = oci_error();
		
		echo "Błąd połączenia z bazą danych ({$e['message']})";
		exit;
	}
?>
