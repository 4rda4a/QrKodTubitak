<?php
include_once "../conn.php";
$zaman = date("d.m.Y - H:i");
$MaintenanceId = clean($_POST["MaintenanceId"]);
$UserId = clean($_POST["UserId"]);

$pc = $conn->prepare("SELECT * FROM maintenance
INNER JOIN users ON users.user_id = maintenance.maintenance_user
WHERE maintenance_id = :MaintenanceId");
$pc->bindParam(":MaintenanceId", $MaintenanceId);
$pc->execute();
$pc = $pc->fetch(PDO::FETCH_ASSOC);
$pc_id = $pc["maintenance_no"];
//LOG 
$control = $conn->prepare("INSERT INTO logs (
    user_id, 
    bakim, 
    bakim_zaman) VALUES(
    :user_id,
    :bakim,
    :bakim_zaman
    )");
$control->bindParam(":user_id", $UserId);
$bakim_text = "ID'si '<a href='bilgisayarlar?pc=$pc_id'>$pc_id</a>' olan bilgisayara ait bir bakımı sildi.
    <div>
        <p class='m-0'>Detaylar:</p>
        <ol>Bakım: $pc[maintenance_text]</ol>
        <ol>Yetkili: $pc[user_name]</ol>
        <ol>Tarih: $pc[maintenance_date]</ol>
    </div>";
$control->bindParam(":bakim", $bakim_text);
$control->bindParam(":bakim_zaman", $zaman);
$control->execute();


$control = $conn->prepare("DELETE FROM maintenance WHERE maintenance_id = :MaintenanceId");
$control->bindParam(":MaintenanceId", $MaintenanceId);
$control->execute();
