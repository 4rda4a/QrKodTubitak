<div class="col-sm-9 m-auto mt-5">
    <table id="user_table" class="table border text-light ">
        <thead>
            <tr class="text-center">
                <th>Adı</th>
                <th>Yetki</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $control = $conn->prepare("SELECT * FROM users
            INNER JOIN yetkiler
            ON users.yetki_id = yetkiler.yetki_id");
            $control->execute();
            $control = $control->fetchAll(PDO::FETCH_ASSOC);
            foreach ($control as $key => $value) { ?>
                <tr>
                    <td><?= $value["user_name"]; ?></td>
                    <td><?= $value["yetki"]; ?></td>
                </tr>
            <?php }
            ?>
        </tbody>
    </table>
    <script>
        new DataTable('#user_table');
        document.getElementById("dt-search-0").classList.add("bg-dark", "text-light");
    </script>
</div>