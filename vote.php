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
            <h1>Oddaj głos</h1>
            <?php if(!isset($_GET['elec']) && !isset($_GET['candidate'])) { ?>
            <form action=<?php echo "?user_id=".$_GET['user_id']."&comm={$_GET['comm']}&elec";?> method="post" name="theForm" id="theForm">
                Wybory: <select form="theForm" name="elec">
                <?php
                    $query = "SELECT nazwa, id FROM wybory WHERE sysdate > poczatek AND sysdate < koniec ORDER BY nazwa";
                    $r = oci_parse($conn, $query);
                    oci_execute($r);
                        
                    $rowCount = oci_fetch_all($r, $all, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
                        
                    foreach ($all as $elec) {
                        $value = str_ireplace(" ", "+", $elec['NAZWA']);
                        $value = $elec['ID']."=".$value;
                        echo "<option value=".$value.">".$elec['NAZWA']."</option>";
                    }
                ?>
                <input type="submit" value="Zatwierdź">
            </form>
            <?php } ?>
            <?php if (isset($_GET['elec'])) { 
                $data = explode("=", $_POST['elec']);
                $id = $data[0];
                $auxname = $data[1];
                $name = str_ireplace("+", " ", $data[1]);
                echo "Wybory: {$name}"; ?>
                <form action=<?php echo "?user_id=".$_GET['user_id']."&comm={$_GET['comm']}&candidate";?> method="post">
                    <input type="hidden" name="name" value=<?php echo $auxname; ?>>
                    Kandydat: <select name="can_id">
                    <?php
                        $query = "SELECT w.imie, w.nazwisko, k.id FROM wyborca w, kandydat k WHERE w.id = k.id_wyborca AND k.id_wybory = :id order by w.nazwisko";
                        $r = oci_parse($conn, $query);
                        oci_bind_by_name($r, ":id", $id);
                        
                        oci_execute($r);
                        $rowCount = oci_fetch_all($r, $all, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
                        
                        foreach ($all as $cand) {
                            echo "<option value=".$cand['ID'].">".$cand['NAZWISKO']." ".$cand['IMIE']."</option>";
                        }
                    ?>
                    <input type="submit" value="Zatwierdź">
                </form>
            <?php } ?>
            <?php if (isset($_GET['candidate'])) {
                $query = "BEGIN oddaj_glos(:user_id, :can_id); END;";
                $r = oci_parse($conn, $query);
                oci_bind_by_name($r, ":user_id", $_GET['user_id']);
                oci_bind_by_name($r, ":can_id", $_POST['can_id']);
                
                if (oci_execute($r)) {
                    $name = str_ireplace("+", " ", $_POST['name']);
                    echo "Dziękujemy za oddanie głosu w ".$name."<br><br>";
                } else {
                    echo "Oddanie głosu nie powiodło się <br><br>";
                }
                if ($_GET['comm'] == 'T') {
                        $url = "komisja/";
                    } else {
                        $url = "wyborca/";
                    }
                    echo "<a href='{$url}index.php?user_id={$_GET['user_id']}&comm={$_GET['comm']}'>Powrót</a><br></center>";
                    
                }
                
                oci_free_statement($r);
                oci_close($conn);
            ?>
        </center>
    </body>
</html>
