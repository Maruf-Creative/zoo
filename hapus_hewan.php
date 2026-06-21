<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'] ?? 0;

if ($id) {
    $stmt = $conn->prepare("SELECT gambar FROM hewan WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $gambar = $row['gambar'];
        
        $del_stmt = $conn->prepare("DELETE FROM hewan WHERE id = ?");
        $del_stmt->bind_param("i", $id);
        
        if ($del_stmt->execute()) {
            if ($gambar && file_exists('uploads/' . $gambar)) {
                unlink('uploads/' . $gambar);
            }
            $_SESSION['flash_message'] = 'Data hewan berhasil dihapus.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Gagal menghapus data: ' . $conn->error;
            $_SESSION['flash_type'] = 'error';
        }
        $del_stmt->close();
    } else {
        $_SESSION['flash_message'] = 'Data hewan tidak ditemukan.';
        $_SESSION['flash_type'] = 'error';
    }
    $stmt->close();
}

header('Location: hewan.php');
exit;
