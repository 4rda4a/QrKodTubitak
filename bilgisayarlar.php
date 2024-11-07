<div class="col-sm-9 m-auto mt-5">
    <?php
    if (isset($_GET["pc"]) && isset($kadi)) {
        $ayir = explode(",", clean($_GET["pc"]));
        $pc_id = $ayir[0];
        if (isset($ayir[1]) && $ayir[1] == 1) {
            include "duzenle_page.php";
        } elseif (isset($ayir[1]) && $ayir[1] == 2) {
            include "bakim_page.php";
        } else {
            echo '<p class="m-0 text-danger text-center fs-3">Hata Kodu: I-65</p>';
        }
    } else {
    ?>
        <table id="pc_table" class="table border text-light ">
            <thead>
                <tr class="text-center">
                    <th>Konum</th>
                    <th>Ram</th>
                    <th>Harddisk</th>
                    <th>İşlemci</th>
                    <th>Kullanıcılar</th>
                    <th>QR</th>
                    <?php
                    if (isset($kadi)) { ?>
                        <th>İşlem</th>
                    <?php }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $control = $conn->prepare("SELECT * FROM computers");
                $control->execute();
                $control = $control->fetchAll(PDO::FETCH_ASSOC);
                foreach ($control as $key => $value) { ?>
                    <tr>
                        <td><?= $value["computer_location"]; ?></td>
                        <td><?= $value["computer_ram"]; ?> GB</td>
                        <td><?= $value["computer_harddisk"]; ?> GB</td>
                        <td><?= $value["computer_islemci"]; ?></td>
                        <td><?php
                            $control = $conn->prepare("SELECT computer_user_no FROM computer_user WHERE computer_user_no = '$value[computer_no]'");
                            $control->execute();
                            echo $control->rowCount();
                            ?></td>
                        <td></td>
                        <?php
                        if (isset($kadi)) { ?>
                            <td class="text-center">
                                <form action="bilgisayarlar" class="row g-1 text-center row-cols-2" method="get">
                                    <button name="pc" type="submit" class="btn col-auto btn-primary pb-0" value="<?= $value["computer_no"]; ?>,1">
                                        <i class="fi fi-rr-customize"></i>
                                    </button>
                                    <button name="pc" type="submit" class="btn col-auto btn-warning pb-0 ms-auto" value="<?= $value["computer_no"]; ?>,2">
                                        <i class="fi fi-rr-hammer-crash"></i>
                                    </button>
                                </form>
                            </td>
                        <?php }
                        ?>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
        <script>
            new DataTable('#pc_table');
            document.getElementById("dt-search-0").classList.add("bg-dark", "text-light");
        </script>
    <?php } ?>
</div>