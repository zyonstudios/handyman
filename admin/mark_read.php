<?php
session_start();
include '../db.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Update the is_read status to 1 (read)
    $stmt = $conn->prepare("UPDATE quotes SET is_read = 1 WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}

$conn->close();
?>

