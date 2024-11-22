<div class="col-sm-9 m-auto mt-5">
    <?php
    if (isset($kadi) && $user["yetki_id"] > 2) {
    ?>
        <div>
            <?php
            if (isset($_POST["yetkili_kaydet"])) {
                $user_name = clean($_POST["adi"]);
                $sifre = md5(clean($_POST["sifre"]));
                $yetki = clean($_POST["yetki"]);
                $tc = clean($_POST["tc"]);
                if ($user_name != "" && $sifre != "" && $yetki != 0 && $tc != "" && strlen($tc) == 11) {
                    $control = $conn->prepare("INSERT INTO users(
                    user_name,
                    user_password,
                    yetki_id,
                    user_tc) VALUES(
                    :user_name,
                    :sifre,
                    :yetki,
                    :tc)
                    ");
                    $control->bindParam(":user_name", $user_name);
                    $control->bindParam(":sifre", $sifre);
                    $control->bindParam(":yetki", $yetki);
                    $control->bindParam(":tc", $tc);
                    if ($control->execute()) {
                        header("location: yetkililer?success=true");
                    } else {
                        header("location: yetkililer?success=false");
                    }
                } else {
                    echo "<p class='text-danger'>Hata Kodu: Y-86</p>";
                }
            }
            ?>
            <form method="post">
                <h4 class="text-warning">Yetkili Ekle:</h4>
                <div class="mb-1">
                    <label>Yetkili TC*</label>
                    <input name="tc" minlength="11" type="number" maxlength="11" class="form-control">
                </div>
                <div class="mb-1">
                    <label>Yetkili Adı*</label>
                    <input name="adi" type="text" class="form-control">
                </div>
                <div class="mb-1">
                    <label>Yetki*</label>
                    <select name="yetki" class="form-control">
                        <option value="0">
                            Seçiniz
                        </option>
                        <?php
                        $control = $conn->prepare("SELECT * FROM yetkiler");
                        $control->execute();
                        $control = $control->fetchAll();
                        foreach ($control as $key => $value) { ?>
                            <option value="<?= $key + 1; ?>">
                                <?= $value["yetki"]; ?>
                            </option>
                        <?php }
                        ?>
                    </select>
                </div>
                <label>Yetkili Şifre*</label>
                <div class="mb-1 input-group">
                    <input id="sifreInput" name="sifre" type="password" onkeyup="passwordShow()" class="form-control">
                    <input id="sifreDisabled" type="text" class="form-control" value="" disabled>
                </div>
                <div class="text-center">
                    <button name="yetkili_kaydet" type="submit" class="btn btn-primary col-sm-3 mt-3">Yetkili Kaydet</button>
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