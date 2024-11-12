<?php
$hata = "";
if (isset($_POST["giris"])) {
    $kullanici_ad = $_POST["kullaniciAdi"];
    $sifre = md5($_POST["sifre"]);

    $control = $conn->prepare("SELECT * FROM users WHERE user_tc = '$kullanici_ad'");
    $control->execute();
    if ($control->rowCount() > 0) {
        $control = $control->fetch(PDO::FETCH_ASSOC);
        if ($control["user_password"] == $sifre) {
            $_SESSION["username"] = $control["user_name"];
            header("refresh: 0");
        } else {
            $hata = "Hata Kodu: Q-78";
        }
    } else {
        $hata = "Hata Kodu: Q-53";
    }
}
?>
<form method="post">
    <div class="form-floating mb-3">
        <input name="kullaniciAdi" type="number" maxlength="11" class="form-control bg-dark text-light" id="kullanici_adi" placeholder="Kullanıcı Adı:" autofocus>
        <label for="kullanici_adi">Kullanıcı Adı:</label>
    </div>
    <div class="form-floating">
        <input name="sifre" type="password" class="form-control bg-dark text-light" id="sifre" placeholder="Şifre">
        <label for="sifre">Şifre:</label>
    </div>
    <p class="text-danger m-0 mt-2"><?= $hata; ?></p>
    <button type="submit" name="giris" class="btn btn-primary mt-3 col-12">Giriş</button>
</form>