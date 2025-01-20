<?php
session_start();
require '../../Koneksi-DSA/Koneksi-DSA.php';

// Query untuk mengambil data dari tabel ds_kunci_tb
$query = "SELECT id_kunci, kode_kunci FROM ds_kunci_tb";
$result = $koneksi->query($query);

// Periksa apakah ada hasil
if ($result->num_rows > 0) {
    // Simpan hasil dalam variabel
    $options = "";
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row['id_kunci'] . "'>" . $row['kode_kunci'] . "</option>";
    }
} else {
    $options = "<option value='' disabled>No data found</option>";
}
$dataPerawat = [];
try {
    $query = "SELECT id_pengguna, nip, nama_lengkap, alamat, username, foto_profil, hak_akses, 
                        tanda_tangan, DATE_FORMAT(created_at, '%d/%m/%Y') AS tanggal_daftar
                FROM ds_pengguna_tb 
                WHERE hak_akses = 'Perawat'";
    $result = $koneksi->query($query);

    if (!$result) {
        throw new Exception("Gagal mengambil data perawat: " . $koneksi->error);
    }

    while ($row = $result->fetch_assoc()) {
        $dataPerawat[] = $row; // Data tanda tangan sudah dalam bentuk file path
    }
} catch (Exception $e) {
    $_SESSION['error_perawat'] = $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Menambahkan Font Awesome -->
    <link rel="stylesheet" href="../../Styles/Css/Index-Dokter/Perawat.css">


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

        <?php if (isset($_SESSION['success_perawat'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <strong>Berhasil!</strong> <?php echo $_SESSION['success_perawat']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_perawat']); ?>
        <?php elseif (isset($_SESSION['error_perawat'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle"></i> <strong>Gagal!</strong> <?php echo $_SESSION['error_perawat']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_perawat']); ?>
        <?php endif; ?>


        <!-- Daftar Perawat Section -->
        <div class="container mt-4">
            <h2>Daftar Perawat</h2>
            <!-- Button to trigger the modal -->
            <button class="btn" id="tambahPerawatBtn" data-bs-toggle="modal" data-bs-target="#tambahPerawatModal">
                <i class="bx bx-user-plus"></i> Tambah Perawat
            </button>

            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Daftar</th>
                        <th>Id Perawat</th>
                        <th>NIP</th>
                        <th>Foto Profil</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>Username</th>
                        <th>Tanda Tangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dataPerawat)): ?>
                        <?php foreach ($dataPerawat as $index => $perawat): ?>
                            <tr>
                                <td><?= $index + 1; ?></td>
                                <td><?= htmlspecialchars($perawat['tanggal_daftar']); ?></td>
                                <td><?= htmlspecialchars($perawat['id_pengguna']); ?></td>
                                <td><?= htmlspecialchars($perawat['nip']); ?></td>
                                <td>
                                    <img src="../../Img/<?= htmlspecialchars($perawat['foto_profil']); ?>"
                                        alt="Foto Profil"
                                        style="width: 50px; height: 50px; border-radius: 50%;">
                                </td>
                                <td><?= htmlspecialchars($perawat['nama_lengkap']); ?></td>
                                <td><?= htmlspecialchars($perawat['alamat']); ?></td>
                                <td><?= htmlspecialchars($perawat['username']); ?></td>
                                <td>
                                    <?php
                                    $folderTandaTangan = "../../Img/"; // Folder tempat file gambar tanda tangan disimpan
                                    // Ganti ekstensi .sig dengan .png
                                    $fileTandaTangan = $folderTandaTangan . pathinfo($perawat['tanda_tangan'], PATHINFO_FILENAME) . '.png';

                                    if (!empty($perawat['tanda_tangan']) && file_exists($fileTandaTangan)) {
                                        // Tampilkan gambar tanda tangan jika file .png ditemukan
                                        echo '<img src="' . htmlspecialchars($fileTandaTangan) . '" alt="Tanda Tangan" style="width: 100px;">';
                                    } else {
                                        echo "Tidak ada tanda tangan.";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm">
                                        <i class="bx bx-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="bx bx-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data perawat.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
    <!-- Modal for adding a nurse -->
    <div class="modal fade" id="tambahPerawatModal" tabindex="-1" aria-labelledby="tambahPerawatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPerawatModalLabel">Form Tambah Perawat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to add a nurse -->
                    <form id="tambahPerawatForm" action="./Proses-Page/Proses_Tambah_Perawat.php" method="POST" enctype="multipart/form-data">
                        <!-- Grid Row 1 -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" required readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="namaLengkap" name="namaLengkap" required>
                            </div>
                        </div>

                        <!-- Grid Row 2 -->
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>

                        <!-- Grid Row 3 -->
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="fotoProfil" class="form-label">Foto Profil</label>
                                <input type="file" class="form-control" id="fotoProfil" name="fotoProfil" required>
                            </div>
                            <div class="col-md-6">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>
                        </div>

                        <!-- Kode Kunci (Select) -->
                        <div class="row g-3 mt-2">
                            <div class="col-md-12">
                                <label for="idKunci" class="form-label">Kode Kunci</label>
                                <select class="form-select" id="idKunci" name="idKunci" required>
                                    <option value="" disabled selected>Pilih Kode Kunci</option>
                                    <?php echo $options; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 mt-4">
                            <label for="tandaTangan" class="form-label">Tanda Tangan</label>
                            <canvas id="signature-pad" class="signature-pad" width="400" height="200"></canvas> <!-- Canvas untuk tanda tangan -->
                            <button type="button" id="clearSignature" class="btn btn-warning mt-2">Clear</button>
                            <button type="button" id="saveSignature" class="btn btn-success mt-2">Save</button>
                        </div>

                        <input type="hidden" id="tandaTangan" name="tandaTangan">


                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk alert -->
    <div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signatureModalLabel">Tanda Tangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage">Tanda tangan berhasil disimpan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const canvas = document.getElementById("signature-pad");
            if (!canvas) return; // Pastikan canvas ada sebelum melanjutkan

            const signaturePad = new SignaturePad(canvas);

            const clearButton = document.getElementById("clearSignature");
            if (clearButton) {
                clearButton.addEventListener("click", function() {
                    signaturePad.clear();
                });
            }

            const saveButton = document.getElementById("saveSignature");
            const form = document.getElementById("tambahPerawatForm");
            const tandaTanganInput = document.getElementById("tandaTangan"); // Ambil input tersembunyi
            const signatureModal = new bootstrap.Modal(document.getElementById('signatureModal')); // Modal untuk tanda tangan

            if (saveButton) {
                saveButton.addEventListener("click", function() {
                    if (signaturePad.isEmpty()) {
                        alert("Tanda tangan belum diisi!");
                    } else {
                        // Ambil data tanda tangan dalam bentuk base64
                        const dataUrl = signaturePad.toDataURL();

                        // Isi nilai input tersembunyi dengan data tanda tangan
                        tandaTanganInput.value = dataUrl;

                        // Tampilkan modal yang memberi tahu tanda tangan berhasil disimpan
                        document.getElementById("modalMessage").textContent = "Tanda tangan berhasil disimpan!";
                        signatureModal.show();
                    }
                });
            }

            // Menambahkan event listener untuk mengirimkan form setelah modal ditutup
            const closeModalButton = document.querySelector('#signatureModal .btn-secondary');
            if (closeModalButton) {
                closeModalButton.addEventListener('click', function() {
                    form.submit(); // Kirimkan form setelah modal ditutup
                });
            }
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



        // Fungsi untuk generate NIP
        function generateNIP() {
            // Ambil tanggal dan bulan hari ini
            var tanggal = new Date().getDate().toString().padStart(2, '0');
            var bulan = (new Date().getMonth() + 1).toString().padStart(2, '0');

            // Generate 3 angka acak
            var angkaAcak = Math.floor(Math.random() * 900) + 100;

            // Generate 3 huruf acak
            var hurufAcak = '';
            var letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            for (var i = 0; i < 3; i++) {
                hurufAcak += letters.charAt(Math.floor(Math.random() * letters.length));
            }

            // Gabungkan menjadi NIP dan set di input
            return "PRW-" + tanggal + bulan + angkaAcak + hurufAcak;
        }

        // Set nilai NIP pada input field
        document.getElementById('nip').value = generateNIP();
    </script>


</body>

</html>