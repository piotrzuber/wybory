<?php require 'login.php'; ?>

<?php
	
	session_start();
	if (!isset($_SESSION['init'])) {
    	session_regenerate_id();
    	$_SESSION['init'] = true;
    }
	
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	$login = $_POST['login'];
	$pwd = $_POST['pwd'];
	
	$query = "SELECT * FROM wyborca w WHERE w.nr_indeksu = :login AND w.password = :pwd";
	
	$r = oci_parse($conn, $query);
    oci_bind_by_name($r, ":login", $login);
    oci_bind_by_name($r, ":pwd", $pwd);
	oci_execute($r);
	
	$rowCount = oci_fetch_all($r, $all, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
	
    if ($rowCount == 0) {
       
        header('Location: auth_failed.php');
	session_destroy();
    } else {
        $goto_url = ".";
        if ($all[0]["CZLONEK_KOMISJI"] == 'T') {
            $goto_url .= "/komisja";
        } else {
            $goto_url .= "/wyborca";
        }
        
        $goto_url .= "/index.php?user_id={$all[0]["ID"]}&comm={$all[0]["CZLONEK_KOMISJI"]}";
        
        $_SESSION['user'] = array($all[0]["IMIE"], $all[0]["NAZWISKO"]);
        $_SESSION['last_activity'] = time();
	header("Location: ".$goto_url);
    }
    
    oci_free_statement($r);
    oci_close($conn);
?>
