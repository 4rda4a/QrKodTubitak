<?php
if (isset($_GET["pc"])) {
    $pc = clean($_GET["pc"]);
    $control = $conn->prepare("SELECT * FROM computers
    WHERE computers.computer_no = :pc");
    $control->bindParam(":pc", $pc);
    $control->execute();
    if ($control->rowCount() > 0) {
        $control = $control->fetch(PDO::FETCH_ASSOC);
        include "navbar.php";
?>
        <div class="card col-sm-8 m-auto bg-dark text-light border-white text-center">
            <div class="card-header text-p-emphasis border-white text-uppercase fs-5">ID: #<?= $control["computer_no"]; ?></div>
            <div class="card-body border-white">
                <div class="row justify-content-center">
                    <div class="col">
                        <span class="text-warning"><i class="fi fi-rr-marker"></i> Konum:</span>
                        <?= $control["computer_location"]; ?>
                    </div>
                    <?php
                    if ($control["computer_name"]) {
                    ?>
                        <div class="col">
                            Adı: <?= $control["computer_name"]; ?>
                        </div>
                    <?php } ?>
                </div>
                <hr>
                <div class="row justify-content-center row-cols-1 row-cols-sm-3">
                    <div class="col my-1">
                        <span class="text-s-emphasis"><i class="fi fi-rr-memory"></i> RAM:</span>
                        <?= $control["computer_ram"]; ?> GB
                    </div>
                    <?php if ($control["computer_islemci"]) {
                    ?>
                        <div class="col my-1">
                            <span class="text-s-emphasis"><i class="fi fi-rr-dice-four"></i> İşlemci:</span>
                            <?= $control["computer_islemci"]; ?>
                        </div>
                    <?php  }
                    if ($control["computer_harddisk"]) { ?>
                        <div class="col my-1">
                            <span class="text-s-emphasis"><i class="fi fi-rr-database"></i> Harddisk:</span>
                            <?= $control["computer_harddisk"]; ?> GB
                        </div>
                    <?php } ?>
                </div>
                <hr>
                <div class="row justify-content-center row-cols-1 row-cols-sm-3">
                    <?php
                    $control = $conn->prepare("SELECT * FROM computer_user WHERE computer_user_no = :pc_id");
                    $control->bindParam(":pc_id", $pc);
                    $control->execute();
                    $control = $control->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($control as $key => $value) {
                    ?>
                        <div class="col my-1 text-center">
                            <p class="m-0 text-center text-danger"><?= $key + 1; ?>. Kullanıcı</p>
                            <p class="m-0">
                                <span class="text-d-emphasis"> Ad:</span>
                                <?= $value["computer_user_name"]; ?>
                            </p>
                            <?php
                            if ($value["computer_user_numara"]) { ?>
                                <p class="m-0">
                                    <span class="text-d-emphasis"> Numara:</span>
                                    <?= $value["computer_user_numara"]; ?>
                                </p>
                            <?php  }
                            if ($value["computer_user_sinif"]) { ?>
                                <p class="m-0">
                                    <span class="text-d-emphasis"> Sınıf:</span>
                                    <?= $value["computer_user_sinif"]; ?>
                                </p>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <hr>
                <div class="row justify-content-center row-cols-1 row-cols-sm-2">
                    <?php
                    $control = $conn->prepare("SELECT * FROM maintenance
                    INNER JOIN users
                    ON maintenance.maintenance_user = users.user_id
                    WHERE maintenance_no = :pc_id
                    ORDER BY maintenance_id;");
                    $control->bindParam(":pc_id", $pc);
                    $control->execute();
                    $control = $control->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($control as $key => $value) {
                    ?>
                        <div class="col my-1 text-center">
                            <p class="m-0 text-center text-primary"><?= $key + 1; ?>. Bakım</p>
                            <p class="m-0">
                                <span class="text-p-emphasis2"> İşlem:</span>
                                <?= $value["maintenance_text"]; ?>
                            </p>
                            <p class="m-0">
                                <span class="text-p-emphasis2"> Tarih:</span>
                                <?= $value["maintenance_date"]; ?>
                            </p>
                            <p class="m-0">
                                <span class="text-p-emphasis2"> Yetkili:</span>
                                <?= $value["user_name"]; ?>
                            </p>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="card-footer border-white"></div>
        </div>
    <?php
    } else {
        echo "<h3 class='text-danger text-center mt-3'>Hata Kodu: I-05</h3>";
    }
} else {
    ?>
    <div class="text-start mt-5 row col-sm-8 col-10 position-absolute top-50 start-50 translate-middle">
        <h5>İşlemler:</h5>
        <div class="list-group col-sm-6">
            <?php
            if (empty($kadi)) { ?>
                <a href="giris" class="list-group-item list-group-item-action bg-dark text-light">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Giriş</h5>
                        <span class="text-body-light"></span>
                    </div>
                    <p class="mb-1">Sisteme giriş yaparak bilgisayarları düzenleyebilir veya bakım ekleyebilirsiniz.</p>
                </a>
            <?php } else { ?>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-light">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Merhaba, <?= $kadi; ?></h5>
                    </div>
                    <p class="mb-1">Sisteme <span class="text-warning"><?= $user["yetki"]; ?></span> olarak giriş yaptınız.</p>
                </a>
            <?php } ?>
            <a href="bilgisayarlar" class="list-group-item list-group-item-action bg-dark text-light" aria-current="true">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">Bilgisayarlar</h5>
                    <span class="text-body-light"></span>
                </div>
                <p class="mb-1">Sistemdeki bilgisayarları inceleyebilirsiniz.</p>
            </a>
            <?php
            if (isset($kadi)) { ?>
                <div class="list-group-item bg-dark">
                    <h5 class="text-warning">Yönetici İşlemleri:</h5>
                    <div class="row p-0">
                        <a href="" class="col-sm-6 text-light list-group-item-action-2 text-center border list-group-item bg-dark">
                            Bilgisayar Oluştur
                        </a>
                        <a href="" class="col-sm-6 text-light list-group-item-action-2 text-center border list-group-item bg-dark">
                            Yönetici Oluştur
                        </a>
                    </div>
                </div>
            <?php } ?>
            <a href="yoneticiler" class="list-group-item list-group-item-action bg-dark text-light">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">Yöneticiler</h5>
                    <span class="text-body-light"></span>
                </div>
                <p class="mb-1">İletişim kurmak için sistemdeki yöneticilere göz atabilirsiniz.</p>
            </a>
            <?php if (isset($kadi)) { ?>
                <a href="cikis" class="list-group-item list-group-item-action bg-dark text-danger">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Çıkış</h5>
                        <span class="text-body-light"></span>
                    </div>
                    <p class="mb-1">Güvenle çıkış yap.</p>
                </a>
            <?php } ?>
        </div>
        <div class="list-group col-sm-6">
            <p class="text-center pt-5 text-warning" style="font-size: 100px; line-height: 1;">
                <!-- <img src="img/feature-2.svg" id="islem-img-id" class="col-8"> -->
                Æ
                <span class="text-center d-block" style="line-height: 1;">Yazılım</span>
            </p>
        </div>
    </div>
<?php
}
?>