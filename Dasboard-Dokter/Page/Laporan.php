<?php
session_start();
require '../../Koneksi-DSA/Koneksi-DSA.php';

// Query untuk mengambil data laporan pasien
$query = "
    SELECT 
        laporan.id_laporan_pasien,
        pengguna.nama_lengkap,
        laporan.created_at,
        laporan.keluhan,
        laporan.saran
    FROM 
        ds_laporan_pasien_tb AS laporan
    JOIN 
        ds_pengguna_tb AS pengguna
    ON 
        laporan.id_pasien = pengguna.id_pengguna
    ORDER BY 
        laporan.created_at DESC";

$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Menambahkan Font Awesome -->
    <link rel="stylesheet" href="../../Styles/Css/Index-Dokter/Pasien.css">


    <title>Dashboard Dokter</title>
</head>

<body>

    <!-- Sidebar -->
    <div id="sidebar" class="bg-dark text-white p-4" style="width: 250px; height: 100vh; position: fixed; left: 0; transition: left 0.3s;">
        <div class="text-center mb-4">
            <h4>Dasboard Admin</h4>
            <p>Nur Afifah Najwa</p>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="../Index-Dokter.php" id="dashboardLink"><i class="fas fa-tachometer-alt"></i> Ringkasan</a> <!-- Ikon untuk Ringkasan -->
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="./Perawat.php" id="nurseLink"><i class="fas fa-user-md"></i> Perawat</a> <!-- Ikon untuk Perawat -->
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#" id="patientLink"><i class="fas fa-procedures"></i> Pasien</a> <!-- Ikon untuk Pasien -->
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#" id="reportLink"><i class="fas fa-file-alt"></i> Laporan</a> <!-- Ikon untuk Laporan -->
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="./Kunci.php" id="lockLink"><i class="fas fa-lock"></i> Kunci</a> <!-- Ikon untuk Kunci -->
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#" id="logoutLink"><i class="fas fa-sign-out-alt"></i> Keluar</a> <!-- Ikon untuk Keluar -->
            </li>
        </ul>

        <!-- Tombol Kelu an r -->
        <div class="mt-4">
            <button class="btn btn-danger w-100" id="logoutButton">Keluar</button>
        </div>
    </div>



    <!-- Konten utama -->
    <div id="content" class="content" style="padding: 20px; margin-left: 250px; transition: margin-left 0.3s;">

    <?php if (isset($_SESSION['success_laporan'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <strong>Berhasil!</strong> <?php echo $_SESSION['success_laporan']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success_laporan']); ?>
<?php elseif (isset($_SESSION['error_laporan'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-times-circle"></i> <strong>Gagal!</strong> <?php echo $_SESSION['error_laporan']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error_laporan']); ?>
<?php endif; ?>


        <div class="container mt-4">
    <h2>Daftar Laporan</h2>

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Nama Pasien</th>
                <th>Tanggal Laporan</th>
                <th>Keluhan</th>
                <th>Saran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($row['created_at'])); ?></td>
                        <td><?php echo htmlspecialchars($row['keluhan']); ?></td>
                        <td><?php echo htmlspecialchars($row['saran']); ?></td>
                        <td>
    <button class="btn btn-success btn-sm btn-verifikasi" data-id="<?php echo $row['id_laporan_pasien']; ?>">
        <i class="bx bx-check"></i> Verifikasi
    </button>
</td>

                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada laporan tersedia.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>




    </div>



    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".btn-verifikasi");

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            if (id) {
                window.location.href = `./Proses-Page/Verifikasi_Laporan.php?id=${id}`;
            }
        });
    });
});

        // Menangani klik pada setiap link di sidebar
        document.getElementById("dashboardLink").addEventListener("click", function() {
            setActiveLink("dashboardLink");
        });

        document.getElementById("nurseLink").addEventListener("click", function() {
            setActiveLink("nurseLink");
        });

        document.getElementById("patientLink").addEventListener("click", function() {
            setActiveLink("patientLink");
        });

        document.getElementById("reportLink").addEventListener("click", function() {
            setActiveLink("reportLink");
        });

        document.getElementById("lockLink").addEventListener("click", function() {
            setActiveLink("lockLink");

        });

        document.getElementById("logoutLink").addEventListener("click", function() {
            setActiveLink("logoutLink");
            // Logika logout, seperti mengarahkan ke halaman login atau menghapus sesi
            window.location.href = "/logout"; // Ganti dengan URL logout Anda
        });

        // Menangani aksi logout dengan tombol
        document.getElementById("logoutButton").addEventListener("click", function() {
            // Logika logout bisa ditambahkan di sini, seperti mengarahkan ke halaman login
            window.location.href = "/logout"; // Ganti dengan URL logout Anda
        });

        // Fungsi untuk mengubah class active pada link yang diklik
        function setActiveLink(activeId) {
            // Menghapus class 'active' dari semua link
            let links = document.querySelectorAll('.nav-link');
            links.forEach(link => {
                link.classList.remove('active');
            });

            // Menambahkan class 'active' pada link yang dipilih
            document.getElementById(activeId).classList.add('active');
        }


        // Menangani event saat modal ditampilkan
        const laporanModal = document.getElementById('laporanModal');
        laporanModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Tombol yang men-trigger modal
            const idPengguna = button.getAttribute('data-id'); // Ambil ID pengguna dari data-id

            const modalBody = laporanModal.querySelector('.modal-body');
            const idInput = modalBody.querySelector('#id_pengguna');
            idInput.value = idPengguna; // Set ID pengguna ke input

            // Ambil tanda tangan berdasarkan hak akses
            fetch('./Proses-Page/ambil_tanda_tangan.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        'id_pengguna': idPengguna
                    })
                })
                .then(response => response.text())
                .then(data => {
                    const tandaTanganInput = modalBody.querySelector('#tanda_tangan');
                    tandaTanganInput.value = data; // Isi tanda tangan
                })
                .catch(error => console.error('Error:', error));
        });

    </script>


</body>

</html>