<?php 
session_start();

$con = mysqli_connect("localhost","root","","filmadatbazis");

if (mysqli_connect_errno()){
    exit('Failed to connect to MySQL!');
}
if(!isset($_GET['id'])){
    exit('Nincs megadva film azonosító!');
}

$id = (int)$_GET['id'];

$sql = "SELECT * FROM filmek WHERE id = $id";
$result = $con->query($sql);

if($result -> num_rows == 0){
    exit("Nincs ilyen film az adatbázisban!");
}

$film = $result -> fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>