<?php
if (isset($kadi) && $user["yetki_id"] > 1) {
    $control = $conn->prepare("SELECT * FROM computers WHERE computer_no = :pc_id");
    $control->bindValue(':pc_id', $pc_id);
    $control->execute();
    if ($control->rowCount() > 0) {
        $control = $control->fetch(PDO::FETCH_ASSOC);
        $pc_detay = $control;
        if (isset($_POST["bilgi_kaydet"])) {
            $name = clean($_POST["pc_name"]);
            $location = clean($_POST["pc_location"]);
            $ram = clean($_POST["pc_ram"]);
            $harddisk = clean($_POST["pc_harddisk"]);
            $islemci = clean($_POST["pc_islemci"]);
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
            if ($pc_detay["computer_name"] != $name) {
                $d0 = "<ol>Bilgisayar Adı: $name</ol>";
            }
            if ($pc_detay["computer_location"] != $location) {
                $d0 = $d0 . "<ol>Bilgisayar Konum: $location</ol>";
            }
            if ($pc_detay["computer_ram"] != $ram) {
                $d0 = $d0 . "<ol>Bilgisayar Ram: $ram GB</ol>";
            }
            if ($pc_detay["computer_harddisk"] != $harddisk) {
                $d0 = $d0 . "<ol>Bilgisayar Harddisk: $harddisk GB</ol>";
            }
            if ($pc_detay["computer_islemci"] != $islemci) {
                $d0 = $d0 . "<ol>Bilgisayar İşlemci: $islemci</ol>";
            }
            if ($d0 == "") {
                $d0 = "Herhangi bir işlem yapmamıştır!";
            }
            $bakim_text = "ID'si '<a href='bilgisayarlar?pc=$pc_id'>$pc_id</a>' olan bilgisayarın üzerinde güncelleme yaptı.
            <div>
                <p class='m-0'>Detaylar:</p>
                $d0
            </div>";
            $control->bindParam(":bakim", $bakim_text);
            $control->bindParam(":bakim_zaman", $zaman);
            $control->execute();
            //GÜNCELLEME İŞLEMİ
            $control = $conn->prepare("UPDATE computers SET
            computer_name = :username,
            computer_location = :pc_location,
            computer_ram = :ram,
            computer_harddisk = :harddisk,
            computer_islemci = :islemci WHERE computer_no = :pc_id
            ");
            $control->bindParam(":pc_id", $pc_id);
            $control->bindParam(":username", $name);
            $control->bindParam(":pc_location", $location);
            $control->bindParam(":ram", $ram);
            $control->bindParam(":harddisk", $harddisk);
            $control->bindParam(":islemci", $islemci);
            $control->execute();
        }
        if (!file_exists("./img/$pc_id.png")) {
            include "barcodeQr.php";
            $qrcode = new BarcodeQR();
            $qrcode->url("$link" . "$pc_id");
            $qrcode->draw(250, "./img/$pc_id.png");
            header("refresh:0");
        }
?>

        <div>
            <a href="./img/<?= $pc_id; ?>.png" class="btn btn-success col-sm-3 float-end" target="_blank" download="<?= $pc_id; ?>">
                QR İndir
            </a>
            <form method="post">
                <h4 class="text-warning">Bilgisayar Özellikleri:</h4>
                <div class="mb-1">
                    <label>Bilgisayar ID</label>
                    <input type="text" disabled class="form-control" value="<?= $pc_id; ?> (Düzenlenemez)">
                </div>
                <div class="mb-1">
                    <label>Bilgisayar Adı:</label>
                    <input required name="pc_name" type="text" class="form-control" value="<?= $pc_detay["computer_name"]; ?>">
                </div>
                <div class="mb-1">
                    <label>Bilgisayar Konum</label>
                    <input required name="pc_location" type="text" class="form-control" value="<?= $pc_detay["computer_location"]; ?>">
                </div>
                <label>Bilgisayar Ram</label>
                <div class="mb-1 input-group">
                    <input name="pc_ram" type="text" class="form-control" value="<?= $pc_detay["computer_ram"]; ?>">
                    <input type="text" class="form-control" value="GB" disabled>
                </div>
                <label>Bilgisayar Harddisk</label>
                <div class="mb-1 input-group">
                    <input name="pc_harddisk" type="text" class="form-control" value="<?= $pc_detay["computer_harddisk"]; ?>">
                    <input type="text" class="form-control" value="GB" disabled>
                </div>
                <div class="mb-1">
                    <label>Bilgisayar İşlemci</label>
                    <input name="pc_islemci" type="text" class="form-control" value="<?= $pc_detay["computer_islemci"]; ?>">
                </div>
                <div class="text-center">
                    <button name="bilgi_kaydet" type="submit" class="btn btn-primary col-sm-3 mt-3">Bilgileri Güncelle</button>
                </div>
                <?php
                $control = $conn->prepare("SELECT * FROM computer_user WHERE computer_user_no = :pc_id");
                $control->bindValue('pc_id', $pc_id);
                $control->execute();
                echo '<h4 class="text-warning mt-4 mb-0">Kullanıcılar: ' . $control->rowCount() . '</h4>'; ?>
                <button id="kullanici_ekle" name="kullanici_ekle" type="submit" class="btn btn-warning col-sm-3 col-6 mt-3"><i class="fi fi-rr-user-add"></i> Kullanıcı Ekle</button>
                <?php
                if (isset($_POST["kullanici_kaydet"])) {
                    $name = clean($_POST["new_user_name"]);
                    if ($name != "") {
                        $numara = clean($_POST["new_user_numara"]);
                        $sinif = clean($_POST["new_user_sinif"]);
                        $bolum = clean($_POST["new_user_bolum"]);
                        $control = $conn->prepare("INSERT INTO computer_user (
                        computer_user_no, 
                        computer_user_name, 
                        computer_user_numara,
                        computer_user_sinif,
                        computer_user_bolum) VALUES(
                        :pc_id,
                        :username,
                        :numara,
                        :sinif,
                        :bolum
                        )");
                        $control->bindParam(":pc_id", $pc_id);
                        $control->bindParam(":username", $name);
                        $control->bindParam(":numara", $numara);
                        $control->bindParam(":sinif", $sinif);
                        $control->bindParam(":bolum", $bolum);
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
                        $bakim_text = "ID'si '<a href='bilgisayarlar?pc=$pc_id'>$pc_id</a>' olan bilgisayara kullanıcı ekledi.
                        <div>
                        <p class='m-0'>Detaylar:</p>
                        <ol>Kullanıcı Adı: $name</ol>
                        <ol>Kullanıcı Numara: $numara</ol>
                        </div>";
                        $control->bindParam(":bakim", $bakim_text);
                        $control->bindParam(":bakim_zaman", $zaman);
                        $control->execute();
                        header("refresh: 0");
                    } else {
                        echo '<p class="text-danger">Hata Kodu: B-14</p>';
                    }
                }
                if (isset($_POST['kullanici_ekle'])) {
                    if ($control->rowCount() >= 10) {
                        echo '<p class="text-danger mt-3">Hata Kodu: B-03</p>';
                    } else {
                ?>
                        <h5 class="text-info mb-0 mt-4" id="newUserAdd">Yeni Kullanıcı</h5>
                        <div class="mb-1">
                            <label>Ad Soyad:</label>
                            <input type="text" name="new_user_name" class="form-control">
                        </div>
                        <div class="mb-1">
                            <label>Numara:</label>
                            <input type="text" name="new_user_numara" class="form-control">
                        </div>
                        <div class="row">
                            <div class="mb-1 col-sm-6">
                                <label>Sınıf:</label>
                                <input type="text" name="new_user_sinif" class="form-control">
                            </div>
                            <div class="mb-1 col-sm-6">
                                <label>Bölüm:</label>
                                <select name="new_user_bolum<?= $uid; ?>" class="form-control">
                                    <option value="0">Seçiniz</option>
                                    <?php
                                    $controlBolum = $conn->prepare("SELECT * FROM bolumler ORDER BY bolum_text ASC");
                                    $controlBolum->execute();
                                    $controlBolum = $controlBolum->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($controlBolum as $key => $deger) {
                                    ?>
                                        <option value="<?= $deger["bolum_id"]; ?>">- <?= $deger["bolum_text"]; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button name="kullanici_kaydet" type="submit" class="btn btn-warning col-sm-3 mt-3">
                                Kullanıcıyı Kaydet
                            </button>
                        </div>
                        <script>
                            document.getElementById("kullanici_ekle").disabled = "on";
                        </script>
                    <?php
                    }
                }
                $control = $control->fetchAll(PDO::FETCH_ASSOC);
                foreach ($control as $key => $value) {
                    $uid = $value["computer_user_id"];
                    if (isset($_POST["kullanici_guncelle_$uid"])) {
                        $username = clean($_POST["pc_user_username_$uid"]);
                        if ($username != "") {
                            $numara = clean($_POST["pc_user_numara_$uid"]);
                            $sinif = clean($_POST["pc_user_sinif_$uid"]);
                            $bolum = clean($_POST["pc_user_bolum_$uid"]);
                            //LOG
                            $control_log = $conn->prepare("INSERT INTO logs (
                            user_id, 
                            bakim, 
                            bakim_zaman) VALUES(
                            :user_id,
                            :bakim,
                            :bakim_zaman
                            )");
                            $control_log->bindParam(":user_id", $user["user_id"]);
                            $d0 = "";
                            if ($value["computer_user_name"] != $username) {
                                $d0 = "<ol>Kullanıcı Adı: $username</ol>";
                            }
                            if ($value["computer_user_numara"] != $numara) {
                                $d0 = $d0 . "<ol>Kullanıcı Numara: $numara</ol>";
                            }
                            if ($value["computer_user_sinif"] != $sinif) {
                                $d0 = $d0 . "<ol>Kullanıcı Sınıf: $sinif</ol>";
                            }
                            if ($value["computer_user_bolum"] != $bolum) {
                                $d0 = $d0 . "<ol>Kullanıcı Bölüm: $bolum</ol>";
                            }
                            if ($d0 == "") {
                                $d0 = "Herhangi bir işlem yapmamıştır!";
                            }
                            $bakim_text = "ID'si '<a href='bilgisayarlar?pc=$pc_id'>$pc_id</a>' olan bilgisayara bağlı '$value[computer_user_name]' kullanıcıda güncellemeler yaptı.
                            <div>
                                <p class='m-0'>Detaylar:</p>
                                $d0
                            </div>";
                            $control_log->bindParam(":bakim", $bakim_text);
                            $control_log->bindParam(":bakim_zaman", $zaman);
                            $control_log->execute();
                            //KULLANICI GÜNCELLEME
                            $control = $conn->prepare("UPDATE computer_user SET
                            computer_user_name = :username,
                            computer_user_numara = :numara,
                            computer_user_sinif = :sinif,
                            computer_user_bolum = :bolum WHERE computer_user_id = :user_id
                            ");
                            $control->bindParam(":username", $username);
                            $control->bindParam(":numara", $numara);
                            $control->bindParam(":sinif", $sinif);
                            $control->bindParam(":bolum", $bolum);
                            $control->bindParam(":user_id", $uid);
                            $control->execute();
                            header("refresh: 0");
                        } else {
                            echo '<p class="text-danger">Hata Kodu: B-14</p>';
                        }
                    }
                    if (isset($_POST["kullanici_sil_$uid"])) {
                        $uname = clean($_POST["pc_user_username_$uid"]);
                        $unumara = clean($_POST["pc_user_numara_$uid"]);
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
                        $bakim_text = "ID'si '<a href='bilgisayarlar?pc=$pc_id'>$pc_id</a>' olan bilgisayardaki kullanıcıyı sildi.
                        <div>
                        <p class='m-0'>Detaylar:</p>
                        <ol>Kullanıcı Adı: $uname</ol>
                        <ol>Kullanıcı Numara: $unumara</ol>
                        </div>";
                        $control->bindParam(":bakim", $bakim_text);
                        $control->bindParam(":bakim_zaman", $zaman);
                        $control->execute();
                        //SİLME İŞLEMİ
                        $control = $conn->prepare("DELETE FROM computer_user WHERE computer_user_id = :user_id");
                        $control->bindParam(":user_id", $uid);
                        $control->execute();
                        header("refresh: 0");
                    }
                    ?>
                    <h5 class="text-info mb-0 mt-4">Kullanıcı <?= $key + 1; ?></h5>
                    <div class="mb-1">
                        <label>Ad Soyad:</label>
                        <input name="pc_user_username_<?= $uid; ?>" type="text" class="form-control" value="<?= $value["computer_user_name"]; ?>">
                    </div>
                    <div class="mb-1">
                        <label>Numara:</label>
                        <input name="pc_user_numara_<?= $uid; ?>" type="text" class="form-control" value="<?= $value["computer_user_numara"]; ?>">
                    </div>
                    <div class="row">
                        <div class="mb-1 col-sm-6">
                            <label>Sınıf:</label>
                            <input name="pc_user_sinif_<?= $uid; ?>" type="text" class="form-control" value="<?= $value["computer_user_sinif"]; ?>">
                        </div>
                        <div class="mb-1 col-sm-6">
                            <label>Bölüm:</label>
                            <select name="pc_user_bolum_<?= $uid; ?>" class="form-control">
                                <option value="0">Seçiniz</option>
                                <?php
                                $control = $conn->prepare("SELECT * FROM bolumler ORDER BY bolum_text ASC");
                                $control->execute();
                                $control = $control->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($control as $key => $deger) {
                                ?>
                                    <option <?php if ($deger["bolum_id"] == $value["computer_user_bolum"]) {
                                                echo "selected";
                                            } ?> value="<?= $deger["bolum_id"]; ?>">- <?= $deger["bolum_text"]; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="text-center">
                        <button name="kullanici_guncelle_<?= $uid; ?>" type="submit" class="btn btn-primary col-sm-3 mt-3">Kullanıcıyı Güncelle</button>
                        <button name="kullanici_sil_<?= $uid; ?>" class="btn btn-danger col-sm-3 mt-3">Kullanıcıyı Sil</button>
                    </div>
                    <hr class="border-white border-2">
                <?php }
                if (isset($_POST["pc_sil"])) {
                    $controls = [
                        "DELETE FROM computer_user WHERE computer_user_no = :pc_id",
                        "DELETE FROM computers WHERE computer_no = :pc_id",
                        "DELETE FROM maintenance WHERE maintenance_no = :pc_id"
                    ];
                    foreach ($controls as $query) {
                        $control = $conn->prepare($query);
                        $control->bindParam(":pc_id", $pc_id);
                        $control->execute();
                    }
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
                    $bakim_text = "ID'si '<a href='bilgisayarlar?pc=$pc_id'>$pc_id</a>' olan bilgisayarı sildi.";
                    $control->bindParam(":bakim", $bakim_text);
                    $control->bindParam(":bakim_zaman", $zaman);
                    $control->execute();
                    unlink("./img/$pc_id.png");
                    header("location: bilgisayarlar");
                }
                $control = $conn->prepare("SELECT * FROM maintenance
            INNER JOIN users ON maintenance.maintenance_user = users.user_id
            WHERE maintenance_no = :pc_id");
                $control->bindValue('pc_id', $pc_id);
                $control->execute();
                echo '<h4 class="text-warning mt-4 mb-0">Bakımlar: ' . $control->rowCount() . '</h4>'; ?>
                <button type="button" class="btn btn-warning col-sm-3 col-6" data-bs-toggle="modal" data-bs-target="#bakimEkleAdmin">
                    Bakım Ekle
                </button>
                <div class="modal fade" id="bakimEkleAdmin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-dark">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Bakım Ekle</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-light">
                                <?php
                                if (isset($_POST["bakim_kaydet"])) {
                                    $bakim = clean($_POST["bakim_text"]);
                                    if ($bakim != "") {
                                        $control = $conn->prepare("INSERT INTO maintenance (
                                                maintenance_no,
                                                maintenance_text,
                                                maintenance_user,
                                                maintenance_date) VALUES(
                                                :pc_id,
                                                :bakim,
                                                :user,
                                                :bakim_date)");
                                        $control->bindParam(":pc_id", $pc_id);
                                        $control->bindParam(":bakim", $bakim);
                                        $control->bindParam(":user", $user["user_id"]);
                                        $control->bindParam(":bakim_date", $zaman);
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
                                        $bakim_text = "ID'si '<a href='bilgisayarlar?pc=$pc_id'>$pc_id</a>' olan bilgisayara bakım ekledi.
                                        <div>
                                            <p class='m-0'>Detaylar:</p>
                                            <ol>Bakım: $bakim</ol>
                                        </div>";
                                        $control->bindParam(":bakim", $bakim_text);
                                        $control->bindParam(":bakim_zaman", $zaman);
                                        $control->execute();
                                        header("refresh: 0");
                                    } else {
                                        echo "<p class='m-0 text-danger'>Hata Kodu: R-04</p>";
                                    }
                                }
                                ?>
                                <p class="text-start m-0">Bakım:</p>
                                <textarea name="bakim_text" rows="5" class="form-control"></textarea>
                                <div class="row">
                                    <p class="m-0 mt-2 text-start col">
                                        <?= date("d.m.Y - H:i"); ?>
                                    </p>
                                    <p class="m-0 mt-2 text-end col">
                                        <?= $kadi; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary col-sm-3 col-4" data-bs-dismiss="modal">Vazgeç</button>
                                <button type="submit" name="bakim_kaydet" class="btn btn-primary col-sm-3 col-4">Kaydet</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $control = $control->fetchAll(PDO::FETCH_ASSOC);
                foreach ($control as $key => $value) {
                    $bakim_id = $value["maintenance_id"];
                    if (isset($_POST["bakim_sil_$bakim_id"])) {
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
                        $bakim = clean($_POST["bakim_text_$bakim_id"]);
                        $bakim_text = "ID'si '<a href='bilgisayarlar?pc=$pc_id'>$pc_id</a>' olan bilgisayara ait bakımı sildi.
                            <div>
                                <p class='m-0'>Detaylar:</p>
                                <ol>Bakım: $bakim</ol>
                            </div>";
                        $control->bindParam(":bakim", $bakim_text);
                        $control->bindParam(":bakim_zaman", $zaman);
                        $control->execute();
                        $control = $conn->prepare("DELETE FROM maintenance WHERE maintenance_id = :id");
                        $control->bindParam(":id", $bakim_id);
                        $control->execute();
                        header("refresh: 0");
                    }
                ?>
                    <h5 class="text-info mb-0 mt-4">Bakım <?= $key + 1; ?></h5>
                    <textarea name="bakim_text_<?= $bakim_id; ?>" rows="5" class="form-control" disabled><?= $value["maintenance_text"]; ?></textarea>
                    <div class="row">
                        <p class="m-0 mt-2 text-start col">
                            <?= $value["maintenance_date"]; ?>
                        </p>
                        <p class="m-0 mt-2 text-end col">
                            <?= $value["user_name"]; ?>
                        </p>
                    </div>
                    <div class="text-center">
                        <button name="bakim_sil_<?= $bakim_id; ?>" class="btn btn-danger col-sm-3 mt-3">Bakımı Sil</button>
                    </div>
                    <hr class="border-white border-2">
                <?php } ?>
                <div class="text-center">
                    <button class="btn btn-danger col-sm-3 my-3" name="pc_sil">Bilgisayar Kaydını Sil</button>
                </div>
            </form>
        </div>
<?php
    } else {
        echo "<h3 class='text-danger text-center'>Hata Kodu: I-05</h3>";
    }
} else {
    echo '<h3 class="text-danger text-center">Hata Kodu: S-01</h3>';
}
?>