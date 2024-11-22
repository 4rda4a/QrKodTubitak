<?php
if (isset($kadi) && $user["yetki_id"] > 1) {
    $harfler = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $rastgeleHarfler = '';
    for ($i = 0; $i < 4; $i++) {
        $rastgeleHarfler .= $harfler[rand(0, strlen($harfler) - 1)];
    }
    $sonuc = $rastgeleHarfler . "-" . time();
?>
    <div>
        <?php
        if (isset($_POST["bilgi_kaydet"])) {
            $pc_id = $sonuc;
            $name = clean($_POST["pc_name"]);
            $location = clean($_POST["pc_location"]);
            $ram = clean($_POST["pc_ram"]);
            $harddisk = clean($_POST["pc_harddisk"]);
            $islemci = clean($_POST["pc_islemci"]);
            $control = $conn->prepare("INSERT INTO computers(
            computer_no,
            computer_name,
            computer_location,
            computer_ram,
            computer_harddisk,
            computer_islemci) VALUES(
            :pc_id,
            :pc_name,
            :pc_location,
            :ram,
            :harddisk,
            :islemci)
            ");
            $control->bindParam(":pc_id", $sonuc);
            $control->bindParam(":pc_name", $name);
            $control->bindParam(":pc_location", $location);
            $control->bindParam(":ram", $ram);
            $control->bindParam(":harddisk", $harddisk);
            $control->bindParam(":islemci", $islemci);
            $control->execute();
            //LOG 
            $control = $conn->prepare("INSERT INTO logs (
                user_id, 
                bakim, 
                bakim_zaman) VALUES(
                :user_id,
                :bakim,
                :bakim_zaman
                )");
            $control->bindParam(":user_id", $user["user_id"]);
            $d0 = "";
            if ($name != "") {
                $d0 = "<ol>Bilgisayar Adı: $name</ol>";
            }
            if ($location != "") {
                $d0 = $d0 . "<ol>Bilgisayar Konum: $location</ol>";
            }
            if ($ram != "") {
                $d0 = $d0 . "<ol>Bilgisayar Ram: $ram GB</ol>";
            }
            if ($harddisk != "") {
                $d0 = $d0 . "<ol>Bilgisayar Harddisk: $harddisk GB</ol>";
            }
            if ($islemci != "") {
                $d0 = $d0 . "<ol>Bilgisayar İşlemci: $islemci</ol>";
            }
            $bakim_text = "ID'si '<a href='bilgisayarlar?pc=$sonuc'>$sonuc</a>' olan bir bilgisayar oluşturdu.
                <div>
                    <p class='m-0'>Detaylar:</p>
                    $d0
                </div>";
            $control->bindParam(":bakim", $bakim_text);
            $control->bindParam(":bakim_zaman", $zaman);
            $control->execute();
            if (!file_exists("./img/$pc_id.png")) {
                include "barcodeQr.php";
                $qrcode = new BarcodeQR();
                $qrcode->url("$link" . "$pc_id");
                $qrcode->draw(250, "./img/$pc_id.png");
                header("location:?pc=$pc_id");
            }
        }
        ?>
        <form method="post">
            <h4 class="text-warning">Bilgisayar Özellikleri:</h4>
            <div class="mb-1">
                <label>Bilgisayar ID</label>
                <input type="text" disabled class="form-control" value="<?= $sonuc; ?> (Düzenlenemez)">
            </div>
            <div class="mb-1">
                <label>Bilgisayar Adı:</label>
                <input name="pc_name" type="text" class="form-control" required>
            </div>
            <div class="mb-1">
                <label>Bilgisayar Konum</label>
                <input name="pc_location" type="text" class="form-control" required>
            </div>
            <label>Bilgisayar Ram</label>
            <div class=" mb-1 input-group">
                <input name="pc_ram" type="text" class="form-control">
                <input type=" text" class="form-control" value="GB" disabled>
            </div>
            <label>Bilgisayar Harddisk</label>
            <div class="mb-1 input-group">
                <input name="pc_harddisk" type="text" class="form-control">
                <input type="text" class="form-control" value="GB" disabled>
            </div>
            <div class="mb-1">
                <label>Bilgisayar İşlemci</label>
                <input name="pc_islemci" type="text" class="form-control">
            </div>
            <div class="text-center">
                <button name="bilgi_kaydet" type="submit" class="btn btn-primary col-sm-3 mt-3">Bilgileri Kaydet</button>
            </div>
        </form>
    </div>
<?php
} else {
    echo '<h3 class="text-danger text-center">Hata Kodu: S-01</h3>';
}
?>