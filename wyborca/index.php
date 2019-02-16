<?php require '../logout.php'; ?>
<?php require '../sec_check.php'; ?>

<html>

<center>
<?php
    echo "Witaj {$_SESSION['user'][0]} {$_SESSION['user'][1]}! ";
    
    require '../commons.php';
?>
</center>

</html>
