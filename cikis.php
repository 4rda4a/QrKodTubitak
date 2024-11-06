<?php
session_destroy();

$deleteTime = time() - 360;
// setcookie("a", "", $deleteTime, "/");

header("location: ./");
?>