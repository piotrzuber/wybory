<?php

    $user_id = $_GET['user_id'];
    
    $url_vote = "../vote.php?user_id=".$user_id."&comm=".$_GET['comm'];
    $url_pass = "../change_password.php?user_id=".$user_id."&comm=".$_GET['comm'];
    $url_results = "../results.php?user_id=".$user_id."&comm=".$_GET['comm'];

    echo "<br><br>";
    echo "<a href={$url_vote}>Oddaj głos</a> &emsp;";
    echo "<a href={$url_results}>Zobacz wyniki</a> &emsp;";
    echo "<a href={$url_pass}>Zmień hasło</a> &emsp;";
?>
