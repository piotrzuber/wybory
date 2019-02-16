<?php require '../login.php'; ?>
<?php require '../logout.php'; ?>
<?php require '../sec_check.php'; ?>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>

<html>
    <body>
        <center>
            <h1>Dodaj kandydata</h1>
            <form action=<?php echo "db_action.php?user_id=".$_GET['user_id']."&comm={$_GET['comm']}";?> method="post">
                <input type="hidden" name="op" value="add">
                <input type="hidden" name="entity" value="candidate">
                Indeks kandydata: <input type="text" name="index" maxlength="9"> &emsp;
                Wybory: <select name="elec_id">
                    <?php
                        $query = "SELECT nazwa, id FROM wybory WHERE sysdate >= poczatek_zglaszania AND sysdate < koniec_zglaszania ORDER BY nazwa";
                        $r = oci_parse($conn, $query);
                        oci_execute($r);
                        
                        
                        $rowCount = oci_fetch_all($r, $all, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASOC);
                        
                        foreach ($all as $elec) {
                            echo "<option value=".$elec['ID'].">".$elec['NAZWA']."</option>";
                        }
                        
                        oci_free_statement($r);
                        oci_close($conn);
                    ?>
                        </select> <br> <br>
                <input type="submit" name="Dodaj">
            </form> 
        </center>
    </body>
</html>
