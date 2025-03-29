<?php
include 'connection.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleteQuery = "DELETE FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: tampil.php?message=Data berhasil dihapus");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "ID tidak ditemukan.";
}
$stmt->close();
?>