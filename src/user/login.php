<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedUser = $_POST["selected_user"];

    $_SESSION["selected_user"] = $selectedUser;

    header("Location: ../index.php");
    exit();
}
?>
