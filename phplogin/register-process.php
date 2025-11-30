<?php 
$con = mysqli_connect("localhost","root","","filmadatbazis");

if(mysqli_connect_errno()){
    exit("Failed to connert to MySql". mysqli_connect_error());
}

if(!isset($_POST['username'] , $_POST['password'] , $_POST['email'])){
    exit('Töltsd ki a regisztrációs adatlapot!');
}
if(empty($_POST['username']) || !isset($_POST['password']) || !isset($_POST['email'])){
    exit('Töltsd ki a regisztrációs adatlapot!');
}

if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    exit('Email cím nem valós');
}

if(preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0){
    exit('A felhasználónév nem valós');
}

if(strlen($_POST['password']) > 20 || strlen($_POST['password'])<5){
    exit('A jelszónak legalább 5 legfeljebb 20 karakter hosszúnak kell lennie');
}

if($stmt = $con->prepare('Select id,password FROM accounts WHERE username =?')){
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
        echo'A felhasználónév már létezik. Kérem válasszon másikat!';
    }
    else{
        $registered = date("Y-m-d H:i:s");

        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        if($stmt = $con->prepare("Insert into accounts (username,password,email,registered, activation_code) VALUES (?,?,?,?,?)") ){
            $uniqid =sha1($_POST["username"].uniqid());
            $stmt->bind_param("sssss", $_POST["username"],$password,$_POST['email'],$registered,$uniqid);
            $stmt->execute();

            $from = 'noreply@example.com';

            $subject = 'Aktiválókulcs';

            $headers = 'From:' .$from . "\r\n" . "Reply-To:" . $from . "\r\n" . "X-Mailer: PHP/" . phpversion() . "\r\n" . "MIME-Version:1.0" . "\r\n" . "Content-Type: text/html; charset = UTF-8" . "\r\n";

            $activate_link = "https://example.com/phplogin/activate.php?email=" . $_POST["email"] . "&code=" . $uniqid;

            $message = '<p>Kérket kattints a következő linkre, hogy aktiváld a fiókod: <a href="' .$activate_link . '">' . $activate_link . '</a></p>';

            mail($_POST['email'], $subject, $message, $headers);

            echo'Nézd meg az emailed hogy aktiváld a';
        }else{
            echo'Could not preapare statement';
        }
    }
    $stmt->close();
} else{
    echo 'Could not prepare statement!';
}

$con->close();

?>