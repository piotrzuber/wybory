<?php require 'login.php'; ?>
<?php require 'logout.php'; ?>
<?php require 'sec_check.php'; ?>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>

<html>
    <body>
        <center>
            <h1>Wyniki wyborów</h1>
            <?php if (!isset($_GET['view'])) { ?>
            <form action=<?php echo "?user_id={$_GET['user_id']}&comm={$_GET['comm']}&view"; ?> method="post">
                Wybory: <select name="elec_id">
                <?php
                    $query = "SELECT nazwa, id FROM wybory";
                    $r = oci_parse($conn, $query);
                    oci_execute($r);
                    oci_fetch_all($r, $all, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);         
                    foreach ($all as $elec) {
                        echo "<option value=".$elec['ID'].">".$elec['NAZWA'];
                    }
                    
                ?>
                <input type="submit" value="Wybierz">
            </form>
            <?php } else {
                        $id = $_POST['elec_id'];
                        $query = "SELECT nazwa FROM wybory WHERE id = {$id}";
                        $r = oci_parse($conn, $query);
                        oci_execute($r);
                        oci_fetch_all($r, $name, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
                        $name = $name[0]['NAZWA'];
                        
                        $query = "SELECT w.imie, w.nazwisko, k.glosy_otrzymane FROM wyborca w, kandydat k WHERE k.id_wybory = {$id} AND k.id_wyborca = w.id ORDER BY glosy_otrzymane DESC, nazwisko";
                        oci_free_statement($r);
                        $r = oci_parse($conn, $query);
                        oci_execute($r);
                        oci_fetch_all($r, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
                        echo $name." - Wyniki<br><br>";
                        $i = 1;
                        foreach ($res as $cand) {
                            echo $i.". ".$cand['NAZWISKO']." ".$cand['IMIE']." - głosy: ".$cand['GLOSY_OTRZYMANE']."<br>";
                            $i++;
                        }
                  }
                  
                  oci_free_statement($r);
                  oci_close($conn);  
            ?>
            
        </center>
    </body>
</html>
