<html>
  <head>
    <title>Wybory do samorządu studenckiego</title>
  </head>
  <center>
  <?php
    echo '<span style="color:Crimson;text-align:center;"> Logowanie nie powiodło się.</span>';
  ?>
  <body>
    <form action="auth.php" method="post">
      <br/>
      Login: <br /> <input type="text" name="login" maxlength="9"/> <br /> <br />
      Hasło: <br /> <input type="password" name="pwd" maxlength="50"/> <br /><br />
      <input type="submit" value="Zaloguj się" />
    </form>
  </body>
  </center>
</html>
