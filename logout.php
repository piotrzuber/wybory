<html>
    <?php
        session_start();
    
        function logout() {
            $sec_check_path = "/~pz395077/Wybory/sec_check.php";
            header("Location: ".$sec_check_path."?logout");
        }
    ?>
    <body>
        <div style="text-align:right">
            <a href="?logout">Wyloguj się</a>
            <?php
                if (isset($_GET['logout'])) logout();
            ?>
        </div>
    <body>
</html>
