<?php
// Include database connection
include 'connection.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query to get all ticket purchase data with specified columns
$query = "SELECT b.id, b.user_id, b.booking_code, b.ticket_type_id, b.visit_date, 
          b.quantity, b.total_price, b.special_request, b.payment_status,
          t.name AS ticket_type_name, u.name AS user_name
          FROM bookings b
          LEFT JOIN ticket_types t ON b.ticket_type_id = t.id
          LEFT JOIN users u ON b.user_id = u.id
          WHERE b.booking_code LIKE '%$search%'
          ORDER BY b.id ASC";
$result = $conn->query($query);

$bookings = [];
while ($booking = $result->fetch_assoc()) {
    $bookings[] = $booking;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembelian Tiket - Menara Teratai</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .payment-paid {
            background-color: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .payment-pending {
            background-color: #fff3cd;
            color: #856404;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .payment-cancelled {
            background-color: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Pembelian Tiket Menara Teratai</h1>
        
        <!-- Search Box -->
        <div class="row search-box">
            <div class="col-md-6 offset-md-3">
                <form action="" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan kode booking..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
        </div>
        
        <div class="mb-3">
            <a href="form.php" class="btn btn-primary">Tambah Data Baru</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>User ID</th>
                        <th>Nama Pemesan</th>
                        <th>Kode Booking</th>
                        <th>Jenis Tiket</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Permintaan Khusus</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($bookings) > 0): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($booking['user_id']) ?></td>
                                <td><?= htmlspecialchars($booking['user_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($booking['booking_code']) ?></td>
                                <td><?= htmlspecialchars($booking['ticket_type_name'] ?? $booking['ticket_type_id']) ?></td>
                                <td><?= htmlspecialchars(date('d-m-Y', strtotime($booking['visit_date']))) ?></td>
                                <td><?= htmlspecialchars($booking['quantity']) ?></td>
                                <td>Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($booking['special_request'] ?: '-') ?></td>
                                <td>
                                    <?php if ($booking['payment_status'] == 'paid'): ?>
                                        <span class="payment-paid">Lunas</span>
                                    <?php elseif ($booking['payment_status'] == 'pending'): ?>
                                        <span class="payment-pending">Menunggu Pembayaran</span>
                                    <?php else: ?>
                                        <span class="payment-cancelled">Dibatalkan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?= $booking['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete.php?id=<?= $booking['id'] ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada data pembelian tiket</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>