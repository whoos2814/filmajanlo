<?php 
session_start();

if(isset($_SESSION["account_loggedin"])){
    header("Location: inmdex.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        .bg-image{
            background-image:url(registerhatter.jpg);
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body>
    <section class="vh-100 bg-image">
        <div class="mask d-flex align-items-center h-100">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Készíts egy fiókot</h2>

                                <form action="register-process.php" method="post" class="form login-form">
                                    <div class="form-outline mb-4">
                                        <input type="text" id="username" name="username" class="form-control form-control-lg" required>
                                        <label for="username" class="form-label">Felhasználónév</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="password" name="password" class="form-control form-control-lg" required>
                                        <label for="password" class="form-label">Jelszó (5 - 20 karakter hosszú)</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="email" id="email" name="email" class="form-control form-control-lg" required>
                                        <label for="email" class="form-label">E-mail cím</label>
                                    </div>
                                    
                                    <div class="f-flex justify-content-center">
                                        <button type="submit" class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Regisztráció</button>
                                    </div>

                                    <p class="text-center text-muted mt-5 mb-0">Van már fiókod? <a href="login.php" class="fw-bold text-body"><u>Jelentkezz be</u></a> </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>