<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}


$con = mysqli_connect("localhost","root","","filmadatbazis");

if(mysqli_connect_errno()){
    exit('Failed to connect to MySQL!');
}

if(!isset($_POST['username'] , $_POST['password'])){
    exit('Hibás űrlapadatok!');
}

if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        if (password_verify($_POST['password'], $password)) {
            session_regenerate_id();
            $_SESSION['account_loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['account_id'] = $id;
            header('Location: ../index.php');
            exit;

        } else {
            echo 'Hibás jelszó!';
        }
    } else {
        echo 'Nincs ilyen felhasználónév!';
    }

    $stmt->close();
}
?>