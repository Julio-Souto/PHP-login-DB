<?php

define('DB_NAME', 'prueba');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

$connect = new mysqli('localhost', DB_USER, DB_PASSWORD, DB_NAME);

if($connect->connect_errno){
  die('Error');
  exit;
}

$sql = 'SELECT * FROM usuario';

$result = $connect->query($sql);

if(!$result)
  echo "Error";

if($result->num_rows==0){
  echo "No hay resultados";
}
else{
  while($valor = $result->fetch_assoc())
   print_r($valor);

}

$result->free();
$connect->close();

echo "<br>";

$dsn = "mysql:host=localhost;dbname=wordpress;charset=utf8mb4;port=3306";
try{
  $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
}
catch(PDOException $e){
  die($e->getMessage());
}

$query = $pdo->query('SELECT * FROM wp_users');

if(!$query)
  echo "Error";

if($query->rowCount()==0){
  echo "No hay resultados";
}
else{
  while($valor = $query->fetch(PDO::FETCH_ASSOC)){
   print_r($valor);
   echo "<br>",$valor['user_nicename'];
   echo "<br>",$valor['user_email'];
  }

}