<?php
if (isset($kadi)) {
    $control = $conn->prepare("SELECT * FROM computers WHERE computer_no = :pc_id");
    $control->bindValue(':pc_id', $pc_id);
    $control->execute();
    $control = $control->fetch(PDO::FETCH_ASSOC);
    if (isset($_POST["bilgi_kaydet"])) {
        $name = clean($_POST["pc_name"]);
        $location = clean($_POST["pc_location"]);
        $ram = clean($_POST["pc_ram"]);
        $harddisk = clean($_POST["pc_harddisk"]);
        $islemci = clean($_POST["pc_islemci"]);
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
        header("refresh: 0");
    }
?>
    <div>
        <form method="post">
            <h4 class="text-warning">Bilgisayar Özellikleri:</h4>
            <div class="mb-1">
                <label>Bilgisayar ID</label>
                <input type="text" disabled class="form-control" value="<?= $pc_id; ?> (Düzenlenemez)">
            </div>
            <div class="mb-1">
                <label>Bilgisayar Adı:</label>
                <input name="pc_name" type="text" class="form-control" value="<?= $control["computer_name"]; ?>">
            </div>
            <div class="mb-1">
                <label>Bilgisayar Konum</label>
                <input name="pc_location" type="text" class="form-control" value="<?= $control["computer_location"]; ?>">
            </div>
            <label>Bilgisayar Ram</label>
            <div class="mb-1 input-group">
                <input name="pc_ram" type="text" class="form-control" value="<?= $control["computer_ram"]; ?>">
                <input type="text" class="form-control" value="GB" disabled>
            </div>
            <label>Bilgisayar Harddisk</label>
            <div class="mb-1 input-group">
                <input name="pc_harddisk" type="text" class="form-control" value="<?= $control["computer_harddisk"]; ?>">
                <input type="text" class="form-control" value="GB" disabled>
            </div>
            <div class="mb-1">
                <label>Bilgisayar İşlemci</label>
                <input name="pc_islemci" type="text" class="form-control" value="<?= $control["computer_islemci"]; ?>">
            </div>
            <div class="text-center">
                <button name="bilgi_kaydet" type="submit" class="btn btn-primary col-sm-3 mt-3">Bilgileri Güncelle</button>
            </div>
            <?php
            $control = $conn->prepare("SELECT * FROM computer_user WHERE computer_user_no = :pc_id");
            $control->bindValue('pc_id', $pc_id);
            $control->execute();
            echo '<h4 class="text-warning mt-4 mb-0">Bilgisayar Kullanıcıları: ' . $control->rowCount() . '</h4>'; ?>
            <button id="kullanici_ekle" name="kullanici_ekle" type="submit" class="btn btn-warning col-sm-3 mt-3"><i class="fi fi-rr-user-add"></i> Kullanıcı Ekle</button>
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
                    <h5 class="text-info mb-0 mt-4">Yeni Kullanıcı</h5>
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
                header("location: bilgisayarlar");
            }
            ?>
            <div class="text-center">

                <button class="btn btn-danger col-sm-3 my-3" name="pc_sil">Bilgisayar Kaydını Sil</button>
            </div>
        </form>
    </div>
<?php
}
?>