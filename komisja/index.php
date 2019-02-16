<?php require '../logout.php'; ?>
<?php require '../sec_check.php'; ?>

<html>
<center>
<?php
    echo "Witaj {$_SESSION['user'][0]} {$_SESSION['user'][1]}! <br><br>";

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $user_id = $_GET['user_id'];
    $url_add_user = "add_user.php?user_id=".$user_id."&comm={$_GET['comm']}";
    $url_add_elections = "add_elections.php?user_id=".$user_id."&comm={$_GET['comm']}";
    $url_add_candidate = "add_candidate.php?user_id=".$user_id."&comm={$_GET['comm']}";
    
    require '../commons.php';    
    
    echo "<a href={$url_add_user}>Dodaj nowego użytkownika</a>"."&emsp;";
    echo "<a href={$url_add_elections}>Zarządź nowe wybory</a>"."&emsp;";
    echo "<a href={$url_add_candidate}>Dodaj kandydata</a>"."&emsp;";

?>
</center>


</html>
