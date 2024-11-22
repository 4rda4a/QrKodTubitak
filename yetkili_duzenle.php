<div class="col-sm-9 m-auto mt-5">
    <?php
    if (isset($kadi) && $user["yetki_id"] > 2) {
        $hata = "";
        $control = $conn->prepare("SELECT * FROM users");
        $control->execute();
        $control = $control->fetchAll(PDO::FETCH_ASSOC);
        foreach ($control as $key => $value) {
            if (md5($value["user_id"]) == $user_id) {
                $user_id = $value["user_id"];
                break;
            }
        }
        $control = $conn->prepare("SELECT * FROM users WHERE user_id= :user_id");
        $control->bindParam(":user_id", $user_id);
        $control->execute();
        $user = $control->fetch(PDO::FETCH_ASSOC);
    ?>
        <div>
            <?php
            if (isset($_POST["yetkili_kaydet"])) {
                $user_name = clean($_POST["adi"]);
                $yetki = clean($_POST["yetki"]);
                $tc = clean($_POST["tc"]);
                if ($user_name != "" && $yetki != 0 && $tc != "" && strlen($tc) == 11) {
                    //LOG
                    $control = $conn->prepare("INSERT INTO logs (
                    user_id, 
                    bakim, 
                    bakim_zaman) VALUES(
                    :user_id,
                    :bakim,
                    :bakim_zaman
                    )");
                    $control->bindParam(":user_id", $HomeUser["user_id"]);
                    $d0 = "";
                    if ($user["computer_name"] != $user_name) {
                        $d0 = "<ol>Yetkili Adı: $user_name</ol>";
                    }
                    if ($user["computer_location"] != $tc) {
                        $d0 = $d0 . "<ol>Yetkili Tc: $tc</ol>";
                    }
                    if ($user["computer_ram"] != $yetki) {
                        $yetkiControl = $conn->prepare("SELECT * FROM yetkiler WHERE yetki_id = $yetki");
                        $yetkiControl->execute();
                        $yetkiControl = $yetkiControl->fetch(PDO::FETCH_ASSOC);
                        $yetkiControlText = $yetkiControl["yetki"];
                        $d0 = $d0 . "<ol>Yetki: $yetkiControlText</ol>";
                    }
                    $eskiUserName = $user["user_name"];
                    $bakim_text = "'$eskiUserName' adlı kişinin üzerinde değişiklikler yaptı.
                    <div>
                        <p class='m-0'>Detaylar:</p>
                        $d0
                    </div>";
                    $control->bindParam(":bakim", $bakim_text);
                    $control->bindParam(":bakim_zaman", $zaman);
                    $control->execute();
                    $control = $conn->prepare("UPDATE users SET
                        user_name = :user_name,
                        yetki_id = :yetki,
                        user_tc = :tc WHERE user_id = :user_id
                        ");
                    $control->bindParam(":user_name", $user_name);
                    $control->bindParam(":yetki", $yetki);
                    $control->bindParam(":tc", $tc);
                    $control->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                    if ($control->execute()) {
                        header("location: " . $_SERVER['REQUEST_URI'] . "&success=true");
                    } else {
                        header("location: " . $_SERVER['REQUEST_URI'] . "&success=false");
                    }
                } else {
                    $hata = "Hata Kodu: Y-86";
                }
            }
            if (isset($_POST["yetkili_sil"])) {
                $user_name_adi = clean($_POST["adi"]);
                //LOG
                $LogControl = $conn->prepare("INSERT INTO logs (
                    user_id, 
                    bakim, 
                    bakim_zaman) VALUES(
                    :user_id,
                    :bakim,
                    :bakim_zaman
                    )");
                $LogControl->bindParam(":user_id", $HomeUser["user_id"]);
                $bakim_text = "Bir yetkiliyi sildi.
                <div>
                    <p class='m-0'></p>
                    <ol>Yetkili Adı: $user_name_adi</ol>
                </div>";
                $LogControl->bindParam(":bakim", $bakim_text);
                $LogControl->bindParam(":bakim_zaman", $zaman);
                $LogControl->execute();
                $control = $conn->prepare("DELETE FROM users WHERE user_id = :user_id");
                $control->bindParam(":user_id", $user_id);
                $control->execute();
                header("location: yetkililer?success=true");
            }
            ?>
            <form method="post">
                <h4 class="text-warning">Yetkili Düzenle:</h4>
                <div class="mb-1">
                    <label>Yetkili TC*</label>
                    <input name="tc" minlength="11" type="number" maxlength="11" class="form-control" value="<?= $user["user_tc"]; ?>">
                </div>
                <div class="mb-1">
                    <label>Yetkili Adı*</label>
                    <input name="adi" type="text" class="form-control" value="<?= $user["user_name"]; ?>">
                </div>
                <div class="mb-1">
                    <label>Yetki*</label>
                    <select name="yetki" class="form-control" value="">
                        <?php
                        $control = $conn->prepare("SELECT * FROM yetkiler");
                        $control->execute();
                        $control = $control->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($control as $key => $value) { ?>
                            <option value="<?= $key + 1; ?>" <?php if ($key + 1 == $user["yetki_id"]) {
                                                                    echo "selected";
                                                                } ?>>
                                <?= $value["yetki"]; ?>
                            </option>
                        <?php }
                        ?>
                    </select>
                </div>
                <button type="button" class="btn btn-warning mt-2 col-sm-3 col-6" data-bs-toggle="modal" data-bs-target="#sifreUpdateModal">
                    Şifre Güncelle
                </button>
                <div class="modal fade" id="sifreUpdateModal" tabindex="-1" aria-labelledby="sifreUpdateModalLabel" aria-hidden="true">
                    <?php
                    if (isset($_POST["sifreKaydet"])) {
                        $sifre = clean($_POST["sifre"]);
                        $NoSecretSifre = clean($_POST["sifre"]);
                        if ($sifre != "") {
                            $sifre = md5($sifre);
                            $control = $conn->prepare("UPDATE users SET
                            user_password = :user_password WHERE user_id = :user_id
                            ");
                            $control->bindParam(":user_password", $sifre);
                            $control->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                            $control->execute();
                            //LOG
                            $LogControl = $conn->prepare("INSERT INTO logs (
                            user_id, 
                            bakim, 
                            bakim_zaman) VALUES(
                            :user_id,
                            :bakim,
                            :bakim_zaman
                            )");
                            $LogControl->bindParam(":user_id", $HomeUser["user_id"]);
                            $userName = clean($_POST["adi"]);
                            $bakim_text = "'$userName' adlı kullanıcının şifresini değiştirildi.
                            <div>
                                <p class='m-0'></p>
                                <ol>Yeni Şifre: $NoSecretSifre</ol>
                            </div>";
                            $LogControl->bindParam(":bakim", $bakim_text);
                            $LogControl->bindParam(":bakim_zaman", $zaman);
                            $LogControl->execute();
                            header("location: " . $_SERVER['REQUEST_URI'] . "&success=true");
                        } else {
                            $hata = "Hata Kodu: Y-13";
                        }
                    }
                    ?>
                    <div method="post" class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-dark">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="sifreUpdateModalLabel">Şifre Güncelle</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label>Yetkili Şifre*</label>
                                <div class="mb-1 input-group">
                                    <input id="sifreInput" name="sifre" type="password" onkeyup="passwordShow()" value="" class="form-control">
                                    <input id="sifreDisabled" type="text" class="form-control" value="" disabled>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary col-sm-3 col-3" data-bs-dismiss="modal">Vazgeç</button>
                                <button class="btn btn-primary col-sm-3 col-3" name="sifreKaydet">Kaydet</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-danger m-0 mt-2"><?= $hata; ?></p>
                </div>
                <div class="text-center">
                    <button name="yetkili_kaydet" type="submit" class="btn btn-primary col-sm-3 col-6 mt-3">Yetkili Kaydet</button>
                </div>
                <div class="text-center">
                    <button name="yetkili_sil" class="btn btn-danger col-sm-2 col-4 mt-3">Yetkili Sil</button>
                </div>
            </form>
        </div>
        <script>
            function passwordShow() {
                document.getElementById("sifreDisabled").value = document.getElementById("sifreInput").value;
            }
        </script>
    <?php
    } else {
        echo '<h3 class="text-danger text-center">Hata Kodu: S-01</h3>';
    }
    ?>
</div>