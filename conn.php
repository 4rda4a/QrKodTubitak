<?php
// Hata raporlamasını etkinleştir ve hataları ekranda göster
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Çıktı tamponlamasını başlat
@ob_start();

// Zaman dilimini Avrupa/İstanbul olarak ayarla
date_default_timezone_set('Europe/Istanbul');

// Eğer dosyanın adı, sunucunun adı ile aynıysa, erişimi engelle ve bir mesaj göster
if (basename(path: $_SERVER['PHP_SELF']) == basename(__FILE__)) {
	exit(' Erişim Engellendi.');
}

// Veritabanı bağlantısı için gerekli bilgiler
$db_user = "root"; // Veritabanı kullanıcı adı
$db_pass = ""; // Veritabanı şifresi
$db_name = "qrkod"; // Veritabanı adı
$host_name = "localhost"; // Veritabanı sunucusu

try {
	$conn = new PDO("mysql:host=$host_name;dbname=$db_name", $db_user, $db_pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	try {
		// Veritabanı oluşturma sorgusu
		$conn = new PDO("mysql:host=$host_name", $db_user, $db_pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Veritabanı oluşturma sorgusu
		$conn->exec("CREATE DATABASE $db_name");

		// Yeniden bağlanma
		$conn = new PDO("mysql:host=$host_name;dbname=$db_name", $db_user, $db_pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Gerekli tabloları oluşturma veya diğer işlemler burada yapılabilir

	} catch (PDOException $ex) {
		// İkinci bir hata durumunda
		echo 'Connection failed: ' . $ex->getMessage();
	}
}
// Oturumu başlat
session_start();
function clean($x)
{
	// HTML özel karakterleri için dönüşüm yap
	$x = htmlspecialchars($x);
	// Tek tırnakları iki tırnak yaparak SQL enjeksiyonunu engelle
	$x = str_replace("'", "''", $x);
	return $x;
}
function alert($mesaj, $renk)
{
	echo '
    <div id="alertBtnId" class="p-0 mt-3 position-fixed col-sm-8 col-12 m-auto start-50 translate-middle alert alert-' . $renk . '" role="alert">
        <div class="p-4 pb-2">' . $mesaj . '</div>
		<i class="fi fi-rr-cross-circle" id="alertRemoveBtn" onclick="alertRem()"></i>
		<div class="progress " role="progressbar" aria-valuemin="0" aria-valuemax="100">
			<div id="alertBtnTimeStick" class="progress-bar progress-bar-striped progress-bar-animated bg-' . $renk . '"></div>
		</div>
    </div>';
}
// Veritabanı bağlantısını UTF-8 olarak ayarla
$conn->query("SET NAMES utf8");
$conn->query("SET CHARACTER SET utf8");
$conn->query("SET COLLATION_CONNECTION = 'utf8mb4_general_ci'");
