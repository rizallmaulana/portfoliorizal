<?php
// Koneksi ke database
$host = 'localhost';
$username = 'root'; // Sesuaikan dengan username database Anda
$password = ''; // Sesuaikan dengan password database Anda
$dbname = 'contact_form';

// Variabel untuk status dan pesan notifikasi
$status = '';
$message = '';

// Coba untuk melakukan koneksi ke database
try {
    $conn = new mysqli($host, $username, $password, $dbname);

    // Periksa jika koneksi gagal
    if ($conn->connect_error) {
        throw new Exception('Gagal terhubung ke database. Pastikan MySQL berjalan.');
    }

    // Tangkap data dari form
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $messageText = $conn->real_escape_string($_POST['message']);

        // Masukkan data ke tabel
        $sql = "INSERT INTO messages (name, email, message) VALUES ('$name', '$email', '$messageText')";

        if ($conn->query($sql) === TRUE) {
            $status = 'success';
            $message = 'Pesan berhasil dikirim!';
        } else {
            throw new Exception('Gagal mengirim pesan. Error: ' . $conn->error);
        }
    }
} catch (Exception $e) {
    // Tangani error koneksi atau query
    $status = 'error';
    $message = $e->getMessage();
} finally {
    // Menutup koneksi
    if (isset($conn)) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesan</title>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tampilkan SweetAlert2 berdasarkan status dan pesan
            Swal.fire({
                icon: '<?php echo $status; ?>', // success atau error
                title: '<?php echo ($status === "success") ? "Berhasil!" : "Gagal!"; ?>',
                text: '<?php echo $message; ?>',
                confirmButtonText: 'OK'
            }).then(() => {
                // Arahkan ke halaman lain jika perlu (misalnya index.html)
                window.location.href = "index.html";
            });
        });
    </script>
</body>

</html>