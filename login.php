<?php
// define('DB_NAME', 'wordpress');
// define('DB_USER', 'root');
// define('DB_PASSWORD', '');

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
$errores="";
extract($_POST);
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <title>Document</title>
  <style>
    fieldset{
      display: flex;
      flex-direction: column;
    }
    fieldset *{
      margin-top: .5em;
    }
    #errores{
      color: red;
      font-style: normal;
      background-color: #161f27;
    }
  </style>
</head>
<body>
  <?php
    if(isset($_SESSION['id'])){
      header("Location: ./private-access.php");
    }
    else{
      if($_SERVER["REQUEST_METHOD"] == "POST" && isset($submit)):
        if(!empty(trim($usuario)) && !empty(trim($pass))){
          $usuario = htmlspecialchars($usuario);
          $_SESSION['login']=$usuario;
          $sql = 'SELECT * FROM wp_users WHERE user_login="'.$usuario.'" and user_pass="'.md5($pass).'"';
          // require_once('../wp-config.php');
          // require_once('../wp-includes/user.php');
          // $user = wp_authenticate_username_password(null, 'usuario', '12345');
          // // print_r($userData->user_pass);
          // if($user->exists())
          //   header("Location: ../wp-admin");
          // print_r($user);
          connectDBPDO();
          $result = $con->query($sql);

          if(!$result)
            $errores.="Error de conexion";

          if($result->rowCount()==0){
            $errores.="Usuario o contraseña no válidos";
          }
          else{
            $_SESSION['id'] = $result->fetch()['ID'];
            $errores="";
            header("Location: ./private-access.php");
          }
          $result->closeCursor();
        }
        else
          $errores .= "Los campos no pueden estar vacios";
        ?>
        <blockquote id="errores"><?=$errores?></blockquote>
        <?php
        endif;
    }
    ?>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <fieldset>
      <h3>Login</h3>
      <label for="usuario">Usuario</label>
      <input type="text" id="usuario" name="usuario"  value="<?= $_SESSION['login']??"";?>">
      <label for="pass">Password</label>
      <input type="password" name="pass" id="pass" >
      <button name="submit" id="submit">Submit</button>
    </fieldset>
  </form>
</body>
</html>