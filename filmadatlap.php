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


$ratingsql = "SELECT ROUND(COALESCE(AVG(rating), 0), '0') AS atlag FROM ratings WHERE film_id = $id";
$ratingresult = $con->query($ratingsql);


$ratingrow = $ratingresult->fetch_assoc();

$account_loggedin = isset($_SESSION['account_loggedin']) && $_SESSION['account_loggedin'] === true;

$user_id = $_SESSION['account_id'] ?? 0;
$userRatingSql = "SELECT rating FROM ratings WHERE film_id = $id AND account_id = $user_id";
$userRatingResult = $con->query($userRatingSql);

$userRating = $userRatingResult->num_rows > 0 ? $userRatingResult->fetch_assoc()['rating'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
      body{
        background: #ffffff;
        background: radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(206, 214, 214, 1) 100%);
      }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Filmuniverzum</a>
        <div class="search" id="search">
            <button class="btn" id="btn" onclick="goBack()">
                <ion-icon name="arrow-back-sharp"></ion-icon>Vissza
          </button>
        </div>
  </div>
</nav>



    <section id="filmek" class="filmek">
      <div class="container" style="height:100%;width:100%;">      
 
        <div class="row content align-items-center position-relative" data-aos="fade-up" style="margin: auto;">
          <div class="col-md-4 justify-content-center">
            <div class="movie-detail-img">
              <img src="<?php echo $film['cover_url']; ?>" alt="<?php echo htmlspecialchars($film['cim']); ?>">
            </div>
          </div>
          <div class="col-md-8 ">
            <div class="movie-details">
              <h2><?php echo htmlspecialchars($film['cim']); ?></h2>
              <div class="banner-meta">
                <ul>
                  <li class="filmmufaj">
                    <span>PG <?php echo htmlspecialchars($film['ajanlott_eletkor']); ?></span>
                    <span>HD</span>
                  </li>
                  <li class="category">
                    <div class="filmmufaj">
                      <?php echo htmlspecialchars($film['mufaj']); ?>
                    </div>
                  </li>
                  <li class="filmmufaj">
                    <span><ion-icon name="calendar-outline"></ion-icon> <?php echo htmlspecialchars($film['megjelenes_eve']); ?></span>
                  </li>
                  <li class="filmmufaj">
                    <span><ion-icon name="time-outline"></ion-icon> <?php echo htmlspecialchars($film['idotartam_perc']); ?> min</span>
                  </li>
                </ul>
              </div>
              <p class="film-description"> <?php echo htmlspecialchars($film['rovid_leiras']); ?></p>
              <div class="directors">
                <ul>
                  <li>
                    <hr>
                    <span><strong>Rendező </strong> <?php echo htmlspecialchars($film['rendezo']); ?></span>     
                  </li>
                  <li>
                    <hr>
                    <span><strong>Forgatókönyvíró </strong> <?php echo htmlspecialchars($film['iro']); ?></span>                    
                  </li>
                  <li>
                    <hr>
                    <span><strong>Főszereplők </strong> <?php echo htmlspecialchars($film['foszereplok']); ?></span>      
                    <hr>             
                  </li>
                </ul>
              </div>
              <div class="rating">
                <div class="score">
                  <span><ion-icon name="star-outline"></ion-icon> <?php echo htmlspecialchars($ratingrow['atlag']); ?>/10</span>
                  <?php if($account_loggedin && $userRating === null): ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ratingModal">
                      Értékeld a filmet
                    </button>

                    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="filmrate.php" method="POST">
                            <div class="modal-header">
                              <h5 class="modal-title" id="ratingModalLabel">Értékeld a filmet</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <input type="hidden" name="film_id" value="<?php echo $id; ?>">
                              <label for="rating">Értékelés (1-10):</label>
                              <input type="number" name="rating" id="rating" min="1" max="10" class="form-control" required>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" class="btn btn-success">Elküld</button>
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <?php endif; ?>

                </div>
              </div>
            </div>
          </div>
        </div>
 
      </div>
    </section>



    <script>
    
    function goBack() {
      if(document.referrer){
        history.back();
      } else {
        window.location.href = "index.php";
      }
    }
</script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>
</html>