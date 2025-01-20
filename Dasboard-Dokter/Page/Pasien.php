<?php
session_start();
require '../../Koneksi-DSA/Koneksi-DSA.php';

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

        <!-- Tombol Keluar -->
        <div class="mt-4">
            <button class="btn btn-danger w-100" id="logoutButton">Keluar</button>
        </div>
    </div>



    <!-- Konten utama -->
    <div id="content" class="content" style="padding: 20px; margin-left: 250px; transition: margin-left 0.3s;">

        <?php if (isset($_SESSION['success_pasien'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <strong>Berhasil!</strong> <?php echo $_SESSION['success_pasien']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_pasien']); ?>
        <?php elseif (isset($_SESSION['error_pasien'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle"></i> <strong>Gagal!</strong> <?php echo $_SESSION['error_pasien']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_pasien']); ?>
        <?php endif; ?>

        <!-- Daftar Perawat Section -->
        <div class="container mt-4">
            <h2>Daftar Pasien</h2>
            <!-- Button to trigger the modal -->
            <button class="btn" id="tambahPasienBtn" data-bs-toggle="modal" data-bs-target="#tambahPasienModal">
                <i class="bx bx-user-plus"></i> Tambah Pasien
            </button>

            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Daftar</th>
                        <th>Id Pasien</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    // Query untuk mengambil data pasien
                    $query = "SELECT id_pengguna, nama_lengkap, username, alamat, created_at 
                  FROM ds_pengguna_tb 
                  WHERE hak_akses = 'Pasien' 
                  ORDER BY created_at DESC";

                    $result = $koneksi->query($query);

                    if ($result->num_rows > 0):
                        $no = 1;
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo date("d/m/Y", strtotime($row['created_at'])); ?></td>
                                <td><?php echo htmlspecialchars($row['id_pengguna']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm">
                                        <i class="bx bx-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="bx bx-trash"></i> Hapus
                                    </button>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#laporanModal" data-id="<?php echo $row['id_pengguna']; ?>">
        <i class="bx bx-report"></i> Kirim Laporan
    </button>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pasien.</td>
                        </tr>
                    <?php endif;

                    $koneksi->close();
                    ?>
                </tbody>
            </table>



        </div>

    </div>
    <!-- Modal for adding a nurse -->
    <div class="modal fade" id="tambahPasienModal" tabindex="-1" aria-labelledby="tambahPasienModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPasienModalLabel">Form Tambah Pasien</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to add a patient -->
                    <form id="tambahPasienForm" action="./Proses-Page/Proses_Tambah_Pasien.php" method="POST" enctype="multipart/form-data">
                        <!-- Grid Row 1 -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="namaLengkap" name="namaLengkap" required>
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                        </div>

                        <!-- Grid Row 2 -->
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>

                        <!-- Grid Row 3 -->
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>
                        </div>



                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Kirim Laporan -->
<div class="modal fade" id="laporanModal" tabindex="-1" aria-labelledby="laporanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="laporanModalLabel">Kirim Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./Proses-Page/Proses_Kirim_Laporan.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="id_pengguna" name="id_pengguna">
                    
                    <div class="form-group">
                        <label for="keluhan">Keluhan</label>
                        <textarea id="keluhan" name="keluhan" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="saran">Saran</label>
                        <textarea id="saran" name="saran" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="tanda_tangan">Tanda Tangan</label>
                        <textarea id="tanda_tangan" name="tanda_tangan" class="form-control" rows="2" readonly required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script>
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
    laporanModal.addEventListener('show.bs.modal', function (event) {
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