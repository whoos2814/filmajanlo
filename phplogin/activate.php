<?php 
session_start();

$con = mysqli_connect("localhost","root","","filmadatbazis");

if(isset($_GET['email'],$_GET['code']) && !empty($_GET['email']) && !empty($_GET['code'])){
    if($stmt = $con->prepare('SELECT * FROM accounts WHERE email = ? AND activation_code = ?')){
        $stmt->bind_param('ss', $_GET['email'],$_GET['code']);
        $stmt-> execute();

        $stmt->store_result();
        if($stmt->num_rows > 0){
            if($stmt = $con->prepare('UPDATE accounts SEt activation_code =? WHERE email =? AND activation_code =?')){
                $newcode = 'activated';
                $stmt->bind_param('sss',$newcode,$_GET['email'],$_GET['code']);
                $stmt->execute();
                echo'A fiókodat aktiváltuk.';
            }
        }else{
            echo 'A fiókod már aktiválva van / nem létezik.';
        }
    }
} else{
    echo 'Invalid request!';
}
?>