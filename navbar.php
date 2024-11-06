<div class="text-end">
    <ul class="nav justify-content-center">
        <li class="nav-item">
            <a class="nav-link text-center text-light" <?php if ($_SESSION["k"] == "") {
                                                            echo "bg-cadetblue";
                                                        } ?> href="./"> Anasayfa</a>
        </li>
        <?php
        if (empty($kadi)) {
        ?>
            <li class="nav-item">
                <a class="nav-link text-center text-light <?php if ($_SESSION["k"] == "giris") {
                                                                echo "bg-cadetblue";
                                                            } ?>" href="giris">Giriş</a>
            </li>
        <?php } ?>
        <li class="nav-item">
            <a class="nav-link text-center text-light <?php if ($_SESSION["k"] == "bilgisayarlar") {
                                                            echo "bg-cadetblue";
                                                        } ?>" href="bilgisayarlar">Bilgisayarlar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center text-light <?php if ($_SESSION["k"] == "yoneticiler") {
                                                            echo "bg-cadetblue";
                                                        } ?>" href="yoneticiler">Yöneticiler</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center text-light <?php if ($_SESSION["k"] == "hata-kodlari") {
                                                            echo "bg-cadetblue";
                                                        } ?>" href="hata-kodlari">Hata Kodları</a>
        </li>
        <?php
        if (isset($kadi)) {
        ?>
            <li class="nav-item">
                <a id="cikis-btn" class="nav-link text-center text-light <?php if ($_SESSION["k"] == "giris") {
                                                                                echo "bg-cadetblue";
                                                                            } ?>" href="cikis">Çıkış</a>
            </li>
        <?php } ?>
        <li class="nav-item">
            <!-- <h6 class="project-name-navbar text-center text-light">Qr Kod Destekli Bİlgisayar Yönetim ve Kullanıcı Takip Sistemi</h6> -->
        </li>
    </ul>
</div>