<?php

    $timeout = 10 * 60; // sesja trwa 10 minut 
    
    session_start();
    if ((isset($_SESSION['last_activity']) && $_SESSION['last_activity'] < (time() - $timeout)) ||
        isset($_GET['logout']) ||
        !isset($_SESSION['user'])) {
        
        $path = "/~pz395077/Wybory/session_expired.php";
        header("Location: {$path}");
        session_destroy();
    }
    
    session_regenerate_id();
    $_SESSION['last_activity'] = time();
    
?>

<html>
<body>
    <div style="text-align:right">  
    <?php 
        $end = date('Y-m-d H:i:s', $_SESSION['last_activity'] + $timeout);
        echo "Koniec bieżącej sesji: {$end}"."<br />";
    ?>
    </div>
</body>
</html>
