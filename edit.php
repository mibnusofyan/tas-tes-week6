<?php
// Include database connection
include 'connection.php';

// Periksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $tanggal = $_POST['tanggal'];
    $tiket = $_POST['tiket'];
    $payment_status = $_POST['payment_status']; // Tambahkan variabel untuk payment_status

    // Query untuk update data termasuk payment_status
    $sql = "UPDATE bookings SET visit_date=?, ticket_type_id=?, payment_status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisi", $tanggal, $tiket, $payment_status, $id);

    if ($stmt->execute()) {
        // Tambahkan script redirect ke tampil.php setelah berhasil update
        header("Location: tampil.php");
        exit(); // Penting untuk menghentikan eksekusi script setelah redirect
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
        <input type="date" id="tanggal" name="tanggal" value="<?php echo $data['visit_date']; ?>" required><br><br>

        <label for="tiket">Ticket Type:</label>
        <select id="tiket" name="tiket" required>
            <option value="1" <?php echo ($data['ticket_type_id'] == 1) ? 'selected' : ''; ?>>Reguler (Rp 20.000)</option>
            <option value="2" <?php echo ($data['ticket_type_id'] == 2) ? 'selected' : ''; ?>>Premium (Rp 50.000)</option>
        </select><br><br>

        <label for="payment_status">Status Pembayaran:</label>
        <select id="payment_status" name="payment_status" required>
            <option value="pending" <?php echo ($data['payment_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="paid" <?php echo ($data['payment_status'] == 'paid') ? 'selected' : ''; ?>>Paid</option>
            <option value="cancelled" <?php echo ($data['payment_status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
        </select><br><br>

        <button type="submit">Update</button>
    </form>
</body>

</html>