<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = 'sql305.infinityfree.com';
$db_user = 'if0_42244373';
$db_pass = 'Gkampret12';
$db_name = 'if0_42244373_db_kebun_binatang';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
