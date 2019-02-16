<?php require 'logout.php'; ?>
<?php require 'sec_check.php'; ?>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>

<html>
    <?php
        function change($id, $oldpwd, $newpwd1, $newpwd2) {
        
            require 'login.php';
        
            if ($newpwd1 != $newpwd2) {
		    echo "<center>Podane nowe hasła nie sa identyczne<br><br></center>";
		    oci_close($conn);
                return;
            }
            
            $query = "SELECT password FROM wyborca WHERE id = :id";
            $r = oci_parse($conn, $query);
            oci_bind_by_name($r, "id", $id);
            
            if (oci_execute($r)) {
                oci_fetch_all($r, $all, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);
                if ($oldpwd != $all[0][0]) {
			echo "<center>Nieprawidłowe hasło<br><br></center>";
			oci_free_statement($r);
			oci_close($conn);
                    return;
                }
            } else {
		    echo "<center>Zmiana hasła zakończona niepowodzeniem<br><br></center>";
		    oci_free_statement($r);
		    oci_close($conn);
                return;
            }
            
            $query = "UPDATE wyborca SET password = :pwd WHERE id = :id";
            oci_free_statement($r);
            $r = oci_parse($conn, $query);
            oci_bind_by_name($r, ":pwd", $newpwd1);
            oci_bind_by_name($r, ":id", $id);
            
            if (oci_execute($r)) {
                echo "<center>Hasło zostało zmienione<br><br></center>";
            } else {
                echo "<center>Zmiana hasła zakończona niepowodzeniem<br><br></center>";
	    }
	    oci_free_sttement($r);
	    oci_close($conn);
	}
    	
    ?>
    <body>
        <center>
            <h1>Zmiana hasła</h1>
            <?php
                if (isset($_GET['change'])) {
                    change($_GET['user_id'], $_POST['oldpwd'], $_POST['newpwd1'], $_POST['newpwd2']);
                }
            ?>
            <form action=<?php echo "?user_id={$_GET['user_id']}&comm={$_GET['comm']}&change"; ?> method="post">
                Obecne hasło: <input type="password" name="oldpwd" maxlength="50"> <br> <br>
                Nowe hasło: <input type="password" name="newpwd1" maxlength="50"> <br> <br>
                Powtórz hasło: <input type="password" name="newpwd2" maxlength="50"> <br> <br>
                <input type="submit" value="Zmień hasło">
            </form>
            <?php
                if (isset($_GET['change'])) {
                    if ($_GET['comm'] == 'T') {
                        $url = "komisja/";
                    } else {
                        $url = "wyborca/";
                    }
                    echo "<a href='{$url}index.php?user_id={$_GET['user_id']}&comm={$_GET['comm']}'>Powrót</a><br></center>";
                }
            ?>
        </center>    
    </body>
</html>
