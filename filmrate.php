<?php
session_start();
$con = mysqli_connect("localhost","root","","filmadatbazis");

if (!$con) exit("Hiba az adatbázis kapcsolatban!");

if (!isset($_SESSION['account_id'])) exit("Nincs bejelentkezve!");
if (!isset($_POST['film_id'], $_POST['rating'])) exit("Hiányzó adat!");

$film_id = (int)$_POST['film_id'];
$user_id = (int)$_SESSION['account_id'];
$rating = (int)$_POST['rating'];

if ($rating < 1 || $rating > 10) exit("Érvénytelen értékelés!");

$checkStmt = $con->prepare("SELECT id FROM ratings WHERE film_id = ? AND account_id = ?");
$checkStmt->bind_param('ii', $film_id, $user_id);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    exit("Már értékelted ezt a filmet!");
}
$checkStmt->close();

$stmt = $con->prepare("INSERT INTO ratings (film_id, account_id, rating, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param('iii', $film_id, $user_id, $rating);

if ($stmt->execute()) {
    header("Location: filmadatlap.php?id=$film_id");
    exit();
} else {
    exit("Hiba az értékelés mentése közben!");
}

$stmt->close();
$con->close();
?>
