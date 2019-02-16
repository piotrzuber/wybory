<?php require '../logout.php'; ?>
<?php require '../sec_check.php'; ?>

<html>
    <body>
    <center>
         <h1> Dodaj użytkownika </h1>
         <form action=<?php echo "db_action.php?user_id=".$_GET['user_id']."&comm={$_GET['comm']}";?> method="post">
            <input type="hidden" name="op" value="add">
            <input type="hidden" name="entity" value="user">
            Imię: <input type="text" name="first_name" maxlength="20"> <br><br>
            Nazwisko: <input type="text" name="last_name" maxlength="20"> <br><br>
            Numer indeksu: <input type="text" name="index_nr" maxlength="9"> <br><br>
            
            Rok studiów: <select name="year">
                            <option value=1>I</option>
                            <option value=2>II</option>
                            <option value=3>III</option>
                            <option value=4>IV</option>
                            <option value=5>V</option>
                         </select>
            Członek komisji: <input type="radio" name="commission" value="T"> Tak
                             <input type="radio" name="commission" value="N" checked> Nie <br><br>
            <input type="submit" value="Dodaj">
         </form>
    </center>
    </body>
</html>
