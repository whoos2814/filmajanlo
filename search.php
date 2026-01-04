<?php 
    session_start();

    $con = mysqli_connect("localhost","root","","filmadatbazis");

    if (mysqli_connect_errno()){
        exit('Failed to connect to MySQL!');
    }

    $q = $_GET['q'] ?? '';
    $q = mysqli_real_escape_string($con, $q);

    $sql = "SELECT * FROM filmek WHERE cim LIKE '%$q%' LIMIT 10";
    $result = $con->query($sql);

    if ($result->num_rows == 0){
        echo "<div class='list-group-item'>Nincs találat</div>";
        exit;
    }

    while ($row = $result->fetch_assoc()){
       echo "<a href='filmadatlap.php?id={$row['id']}' class='list-group-item list-group-item-action'>{$row['cim']}</a>";
    }
?>

