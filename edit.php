<?php
// Include database connection
include 'connection.php';

// Periksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $tanggal = $_POST['tanggal'];
    $tiket= $_POST['tiket'];

    // Query untuk update data
    $sql = "UPDATE bookings SET visit_date=?, ticket_type_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $tanggal, $tiket, $id);

    if ($stmt->execute()) {
        echo "Data berhasil diperbarui!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Ambil data berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM bookings WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "ID tidak ditemukan!";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
    <h1>Edit Data</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        <label for="tanggal">Tanggal Kunjungan:</label>
        <input type="text" id="tanggal" name="tanggal" value="<?php echo $data['visit_date']; ?>" required><br><br>
        <label for="tiket">Ticket Type:</label>
        <input type="tiket" id="tiket" name="tiket" value="<?php echo $data['ticket_type_id']; ?>" required><br><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>