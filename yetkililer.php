<?php
if (isset($_GET["add"]) && $_GET["add"] == "true") {
    include "yetkili_ekle.php";
} else {
    if (isset($_POST["yetkili_duzenle"])) {
        $user_id = clean($_POST["user_id"]);
        include "yetkili_duzenle.php";
    } else {
?>
        <div class="col-sm-9 m-auto mt-5">
            <div class="row text-center text-warning">
                <p class="col fs-4 m-0">Adı</p>
                <p class="col fs-4 m-0">Yetki</p>
                <p class="col fs-4 m-0">Tanımı</p>
            </div>
            <table class="table text-light">
                <tbody>
                    <?php
                    $control = $conn->prepare("SELECT * FROM users
                    INNER JOIN yetkiler
                    ON users.yetki_id = yetkiler.yetki_id");
                    $control->execute();
                    $control = $control->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($control as $key => $value) { ?>
                        <tr class="row row-cols-1 row-cols-sm-3">
                            <td class="col text-flex-center text-d-emphasis"><?= $value["user_name"]; ?></td>
                            <td class="col text-flex-center text-s-emphasis"><?= $value["yetki"]; ?></td>
                            <td class="col text-flex-center text-center text-p-emphasis">
                                <button type="button" class="btn btn-success col-sm-4 col-3 mx-1"
                                    data-bs-toggle="popover" data-bs-placement="right"
                                    data-bs-custom-class="custom-popover"
                                    data-bs-title="<?= $value["yetki"]; ?>"
                                    data-bs-content="<?= $value["aciklama"]; ?>">
                                    Detay
                                </button>
                                <?php
                                if (isset($kadi)) {
                                ?>
                                    <form method="post" class="col-sm-4 col-3">
                                        <input type="hidden" name="user_id" value="<?= $value["user_id"]; ?>">
                                        <button class="btn btn-primary col-12" name="yetkili_duzenle">Düzenle</button>
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Tüm popover'ları etkinleştirme
                    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                        return new bootstrap.Popover(popoverTriggerEl);
                    });
                });
                // new DataTable('#user_table');
                // document.getElementById("dt-search-0").classList.add("bg-dark", "text-light");
            </script>
        </div>
<?php }
}
?>