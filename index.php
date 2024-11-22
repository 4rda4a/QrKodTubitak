<!doctype html>
<html lang="tr">
<?php
include_once "conn.php";
$zaman = date("d.m.Y - H:i");
if (isset($_SESSION["username"])) {
    $kadi = $_SESSION["username"];

    $control = $conn->prepare("SELECT * FROM users 
    INNER JOIN yetkiler ON users.yetki_id = yetkiler.yetki_id 
    WHERE users.user_name = :kadi");
    $control->bindParam(':kadi', $kadi, PDO::PARAM_STR);
    $control->execute();
    $user = $control->fetch(PDO::FETCH_ASSOC);
}
$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$link = "http://localhost/pc?";
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta property="og:description" content="">
    <meta name="google-site-verification" content="2vkyccGnBudlhdUpI4vqNqXEvA6b-aSQszgdL-7QSpA" />
    <title>
        Qr Kod Destekli Bilgisayar Yönetim Ve Kullanıcı Takip Sistemi
    </title>
    <!-- FAVICON -->
    <link rel="icon" href="img/feature-2.svg">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/style.css?61845">
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- ICONS -->
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css'>
    <!-- DATATABLE -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css"> -->
</head>

<body class="bg-dark text-light">
    <div class="container">
        <code>
            <?php
            if (isset($_GET["seo"])) {
                $k = explode("/", rtrim($_GET["seo"], "/"));
                $_SESSION["k"] = $dosya_kategori = $k[0];
                include "navbar.php";
                if (file_exists($dosya_kategori . ".php")) {
                    if (isset($_GET["success"]) && $_GET["success"] == "true") {
                        alert("✅ İşleminiz başarılı bir şekilde gerçekleşmiştir!", "success");
                    }
                    if (isset($_GET["success"]) && $_GET["success"] == "false") {
                        alert("❌ İşleminiz gerçekleştirilirken bir sorun oluştu!", "danger");
                    }
                    include $dosya_kategori . ".php";
                } else {
                    if (isset($k[1])) {
                        include "detay.php";
                    } else {
                        include "liste.php";
                    }
                }
                include "footer.php";
            } else {
                include "anasayfa.php";
            }
            ?>
        </code>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N"
        crossorigin="anonymous"></script>
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <!-- HARİCİ JS -->
    <script src="assets/script.js"></script>
    <!-- DATATABLE -->
    <!-- <script src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="http://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="http://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script> -->
</body>

</html>