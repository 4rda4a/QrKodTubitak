<div class="col-sm-9 m-auto mt-5">
    <?php
    if (isset($kadi) && $user["yetki_id"] > 2 && isset($_POST["UserId"])) {
        $user_id = clean($_POST["UserId"]);
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
        echo "<h4 class='text-warning'>" . $user["user_name"] . " Log:</h4>"; ?>
        <table id="pc_table" class="table border text-light">
            <thead>
                <tr class="text-center">
                    <th>Tarih</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $control = $conn->prepare("SELECT * FROM logs WHERE user_id = :user_id ORDER BY bakim_zaman DESC");
                $control->bindParam(":user_id", $user_id);
                $control->execute();
                $control = $control->fetchAll(PDO::FETCH_ASSOC);
                foreach ($control as $key => $value) { ?>
                    <tr>
                        <td><?= $value["bakim_zaman"]; ?></td>
                        <td><?= $value["bakim"]; ?></td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
        <script>
            document.getElementById("dt-search-0").classList.add("bg-dark", "text-light");
        </script>
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <script>
            new DataTable('#pc_table');
        </script>
    <?php } else {
        echo '<h3 class="text-danger text-center">Hata Kodu: S-01</h3>';
    } ?>
</div>