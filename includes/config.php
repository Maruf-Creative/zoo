<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = 'reseau.proxy.rlwy.net';
$db_user = 'root';
$db_pass = 'xtTNsewYEFcmNNLxrtUSaRhqaSceBuqG';
$db_name = 'db_kebun_binatang';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
