<?php
if (empty($kadi)) {
?>
    <div class="text-start mt-5 row col-sm-3 col-10 position-absolute top-50 start-50 translate-middle">
        <?php
        include "login_form.php";
        ?>
    </div>
<?php } else {
    header("location:./");
} ?>