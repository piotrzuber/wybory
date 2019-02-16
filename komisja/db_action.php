<?php require '../login.php'; ?>
<?php require '../logout.php'; ?>
<?php require '../sec_check.php'; ?>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    
    $my_id = $_GET['user_id'];
    
    if ($_POST['op'] == "add") {
        if ($_POST['entity'] == "user") {
            $firstname = (string)$_POST['first_name'];
            $lastname = (string)$_POST['last_name'];
            $year = (int)$_POST['year'];
            $index = (string)$_POST['index_nr'];
            $comm = (string)$_POST['commission'];
        
            $query = "INSERT INTO wyborca VALUES (id_wyborca_seq.NEXTVAL, :index_v, :firstname_v, :lastname_v, :year_v, :comm_v, {$my_id}, 'haslo')";
            $r = oci_parse($conn, $query);
            oci_bind_by_name($r, ":index_v", $index);
            oci_bind_by_name($r, ":firstname_v", $firstname);
            oci_bind_by_name($r, ":lastname_v", $lastname);
            oci_bind_by_name($r, ":year_v", $year);
            oci_bind_by_name($r, ":comm_v", $comm);
            
            if (oci_execute($r)) {
                echo "<center>Dodano użytkownika ".$firstname." ".$lastname."<br> <a href='index.php?user_id={$my_id}&comm={$_GET['comm']}'>Powrót</a></center>";
            } else {
                echo "<center>Dodanie użytkownika nie powiodło się <br> <a href='index.php?user_id={$my_id}&comm={$_GET['comm']}'>Powrót</a></center>";
            }
            
            
        } elseif ($_POST['entity'] == "elections") {
            $name = (string)$_POST['name'];
            $subbeg = $_POST['subbeg'];
            $subend = $_POST['subend'];
            $elbeg = $_POST['elbeg'];
            $elend = $_POST['elend'];
            
            $query = "INSERT INTO wybory VALUES (id_wybory_seq.NEXTVAL, :name_v, to_date(:subbeg_v, 'YYYY-MM-DD'), to_date(:subend_v, 'YYYY-MM-DD'), to_date(:elbeg_v, 'YYYY-MM-DD'), to_date(:elend_v, 'YYYY-MM-DD'))";
            
            $r = oci_parse($conn, $query);
            oci_bind_by_name($r, ":name_v", $name);
            oci_bind_by_name($r, ":subbeg_v", $subbeg);
            oci_bind_by_name($r, ":subend_v", $subend);
            oci_bind_by_name($r, ":elbeg_v", $elbeg);
            oci_bind_by_name($r, ":elend_v", $elend);
            
            if (oci_execute($r)) {
                echo "<center>Utworzono ".$name."<br> <a href='index.php?user_id={$my_id}&comm={$_GET['comm']}'>Powrót</a></center>";
            } else {
                echo "<center>Utworzenie wyborów nie powiodło się <br> <a href='index.php?user_id={$my_id}&comm={$_GET['comm']}'>Powrót</a></center>";
            }
        } elseif ($_POST['entity'] == "candidate") {
            $index = $_POST['index'];
            $elec_id = $_POST['elec_id'];
            
            $query = "SELECT id FROM wyborca WHERE nr_indeksu = :index_v";
            $r = oci_parse($conn, $query);
            oci_bind_by_name($r, ":index_v", $index);
            
            if (!oci_execute($r)) {
                echo "<center>Dodawanie kandydata nie powiodło się<br> <a href='index.php?user_id={$my_id}&comm={$_GET['comm']}'>Powrót</a></center>";
            } else {
                if (!oci_fetch_all($r, $id, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM)) {
                    echo "<center>Podano nieprawidłowy indeks <br> <a href='index.php?user_id={$my_id}&comm={$_GET['comm']}'>Powrót</a></center>";
                } else {
                    $id = $id[0][0];
                    
                    $query = "INSERT INTO kandydat VALUES (id_kandydat_seq.NEXTVAL, :id_v, :elec_id_v, 0, {$my_id})";
                    oci_free_statement($r);
                    $r = oci_parse($conn, $query);
                    oci_bind_by_name($r, ":id_v", $id);
                    oci_bind_by_name($r, ":elec_id_v", $elec_id);
                    
                    if (oci_execute($r)) {
                        echo "<center>Dodano kandydata ".$index."<br> <a href='index.php?user_id={$my_id}&comm={$_GET['comm']}'>Powrót</a></center>";
                    } else {
                        echo "<center>Dodawanie kandydata nie powiodło się<br> <a href='index.php?user_id={$my_id}&comm={$_GET['comm']}'>Powrót</a></center>";
                    }
                }
            }
        }
    }
    
    oci_free_statement($r);
    oci_close($conn);
?>
