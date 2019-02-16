<?php require '../logout.php'; ?>
<?php require '../sec_check.php'; ?>

<html>
    <body>
        <center>
            <h1>Dodaj wybory</h1>
            <form action=<?php echo "db_action.php?user_id=".$_GET['user_id']."&comm={$_GET['comm']}";?> method="post">
                <input type="hidden" name="op" value="add">
                <input type="hidden" name="entity" value="elections">
                Nazwa: <input type="text" name="name" maxlength="50"> <br><br>
                Data rozpoczęcia zgłaszania kandydatur: <input type="date" name="subbeg"> <br><br>
                Data zakończenia zgłaszania kandydatur: <input type="date" name="subend"> <br><br>
                Data rozpoczęcia wyborów: <input type="date" name="elbeg"> <br><br>
                Data zakończenia wyborów: <input type="date" name="elend"> <br><br>
                <input type="submit" value="Dodaj">
            </form>
        </center>
    </body>
</html>
