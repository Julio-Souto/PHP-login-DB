<?php
  session_start();
  if(!isset($_SESSION['id'])){
    header("Location: ./login.php");
  }
  function connectDB(string $host = 'localhost', string $user = 'root', string $pass = '', string $dbname = 'wordpress'):mysqli{ 
    try {
      return new mysqli($host, $user, $pass, $dbname);
    } catch (mysqli_sql_exception $e) {
      die($e->getMessage());
    }
  }
  function connectDBPDO(string $host = 'localhost', string $user = 'root', string $pass = '', string $dbname = 'wordpress'){
    global $con; 
    try {
      $con = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }
  $sql = 'SELECT * FROM wp_users WHERE ID="'.$_SESSION['id'].'"';
  connectDBPDO();
  $result = $con->query($sql);
  $usuario = "Hola ".$result->fetchColumn(1).", Tu ID es ".$_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <title>Document</title>
</head>
<body>
  <h1>Acceso Prohibido</h1>
  <p><?=$usuario?></p>
  <form method="post">
    <button name="volver">Volver</button>
    <?php
      extract($_POST);
      if(isset($volver)){
        session_destroy();
        header("Location: ./login.php");
      }
    ?>
  </form>
</body>
</html>