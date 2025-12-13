<?php
session_start();

$con = mysqli_connect("localhost","root","","filmadatbazis");

    if (mysqli_connect_errno()){
        exit('Failed to connect to MySQL!');
    }
$sqlslider = "SELECT * from filmek";
$resultslider = $con ->query($sqlslider);


$counter = 0;
$sectionszam = 1;

$limit = 12;

if(isset($_GET["page"])){
    $pn = $_GET["page"];
}
else{
    $pn = 1;
}

$start_from = ($pn-1) * $limit;



$mufaj_filter =[];
if(!empty($_GET['mufaj'])){
    $mufaj = mysqli_real_escape_string($con, $_GET['mufaj']);
    $mufaj_filter[] = " mufaj LIKE '%$mufaj%' ";
}

$order_by = "";
if(!empty($_GET['rendezes'])){
    if($_GET['rendezes'] == "csokkeno"){
        $order_by = " ORDER BY osszmegtekintes DESC ";
    }
    else if($_GET['rendezes'] == "novekvo"){
        $order_by = " ORDER BY osszmegtekintes ASC ";
    }
}

$eredmenysql ="";
if(count($mufaj_filter)>0){
    $eredmenysql = " WHERE " . implode(" AND ", $mufaj_filter);
}

$sql = "SELECT * from filmek $eredmenysql $order_by LIMIT $start_from, $limit";
$result = $con ->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmkatalógus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=1.0">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand">Asd</a>
        <div class="search" id="search">
            <input type="text" class="input" id="input" placeholder="Search...">
            <button class="btn" id="btn">
                <ion-icon name="search-outline"></ion-icon>
          </button>
        </div>
  </div>
</nav>



<div class="wrapper">
<?php
echo '<section id="section'.$sectionszam.'">';
echo '<a href="#section'.$sectionszam.'" class="arrow__btn left-arrow">‹</a>';

while($row = $resultslider->fetch_assoc()) {
    if ($counter >= 15) break;
    if($counter > 0 && $counter % 5 == 0){
        $elozo = $sectionszam;
        $sectionszam++;

        echo '<a href="#section'.$sectionszam.'" class="arrow__btn right-arrow">›</a>';
        echo '</section>';

        echo '<section id="section'.$sectionszam.'">';
        echo '<a href="#section'.$elozo.'" class="arrow__btn left-arrow">‹</a>';
    }
?>
    <div class="item">
        <a href="filmadatlap.php?id=<?php echo $row['id']; ?>">
            <img src="<?php echo $row['cover_url']; ?>" alt="">
            <h1 class="heading"><?php echo htmlspecialchars($row['cim']); ?></h1>
            <p class="duration">Hossz: <?php echo htmlspecialchars($row['idotartam_perc']); ?> Perc</p>
        </a>
    </div>
<?php
    $counter++;
}

$kovetkezo = 1;
echo '<a href="#section'.$kovetkezo.'" class="arrow__btn right-arrow">›</a>';
echo '</section>';
?>

</div>

<div class="container mt-4">
    <div class="row">
        <div class="mufaj-dropdown col-sm-6 ">
            <form action="" method="GET">
                <select name="mufaj" onchange="this.form.submit()">
                    <option value="">Műfajok</option>
                    <option value="akcio" <?php if(isset($_GET['mufaj'])&& $_GET['mufaj'] == 'akcio') echo 'selected'?>>Akció</option>
                    <option value="vigjatek" <?php if(isset($_GET['mufaj'])&& $_GET['mufaj'] == 'vigjatek') echo 'selected'?>>Vígjáték</option>
                    <option value="drama" <?php if(isset($_GET['mufaj'])&& $_GET['mufaj'] == 'drama') echo 'selected'?>>Dráma</option>
                    <option value="horror" <?php if(isset($_GET['mufaj'])&& $_GET['mufaj'] == 'horror') echo 'selected'?>>Horror</option>
                    <option value="sci-fi" <?php if(isset($_GET['mufaj'])&& $_GET['mufaj'] == 'sci-fi') echo 'selected'?>>Sci-Fi</option>
                    <option value="krimi" <?php if(isset($_GET['mufaj'])&& $_GET['mufaj'] == 'krimi') echo 'selected'?>>Krimi</option>
                    <option value="thriller" <?php if(isset($_GET['mufaj'])&& $_GET['mufaj'] == 'thriller') echo 'selected'?>>Thriller</option>
                    <option value="fantasy" <?php if(isset($_GET['mufaj'])&& $_GET['mufaj'] == 'fantasy') echo 'selected'?>>Fantasy</option>
                    <option value="western" <?php if(isset($_GET['mufaj'])&& $_GET['mufaj'] == 'western') echo 'selected'?>>Western</option>
                    <option value="háborús" <?php if(isset($_GET['mufaj'])&& $_GET['mufaj'] == 'háborús') echo 'selected'?>>Háborús</option>
                    <option value="romantikus" <?php if(isset($_GET['mufaj'])&& $_GET['mufaj'] == 'romantikus') echo 'selected'?>>Romantikus</option>
                </select>
            </form>
        </div>
        <div class="mufaj-dropdown col-sm-6">
            <form action="" method="GET">
                <select name="rendezes" onchange="this.form.submit()">
                    <option value="">Rendezés</option>
                    <option value="csokkeno" <?php if(isset($_GET['rendezes']) && $_GET['rendezes'] == "csokkeno") echo 'selected' ?>>Nézettség alapján csökkenő</option>
                    <option value="novekvo" <?php if(isset($_GET['rendezes']) && $_GET['rendezes'] == "novekvo") echo 'selected' ?>>Nézettség alapján növekvő</option>
                </select>
            </form>
        </div>
    </div>  
</div>


<div class="container mt-4">
    <div class="row">
        <?php
        while($row = $result->fetch_assoc()) {
        ?>
        <div class="col-md-3 mb-4 d-flex justify-content-center">
            <div class="card " style="width: 18rem;">
                <img src="<?php echo $row['cover_url']; ?>" class="card-img-top" alt="<?php echo $row['cim']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo ($row['cim'])?></h5>
                </div>
            </div>
        </div>
        <?php
        }
        
        ?>
    </div>
    
    <?php 
        $params = $_GET;

        $sql = "SELECT COUNT(*) FROM filmek $eredmenysql";
        $result = $con->query($sql);
        $row = $result -> fetch_assoc();
        $total_records = $row['COUNT(*)'];

        $total_pages = ceil($total_records / $limit);
        ?>
        <div class="pagination d-flex justify-content-center">
            <?php  
                $params['page'] = 1;
                echo"<a class='lap' href='index.php?".http_build_query($params)."'>".'Első'."</a>";

                $paglink = "";

                for($i=1;$i<=$total_pages;$i++){
                    $params['page'] = $i;
                    $link = "index.php?".http_build_query($params);
                   if($i==$pn){
                        $paglink .= "<div class='active lap'><a class='page-link' href='$link'>$i</a></div>";
                   }
                   else{
                       $paglink .= "<div><a class='lap' href='$link'>$i</a></div>";
                   }
                }
                echo $paglink;
                $params['page'] = $total_pages;
                echo "<a class='lap' href='index.php?".http_build_query($params)."'>Utolsó</a>";
            ?>
        </div>
        
        

    </div>
</div>





<script>
    const btn = document.getElementById('btn');
    const search = document.getElementById('search');
    const input = document.getElementById('input');

    btn.addEventListener('click',()=>{
    search.classList.toggle('activate')
    input.focus()
})
</script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>
</html>
