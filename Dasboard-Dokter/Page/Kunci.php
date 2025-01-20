<?php
session_start();

require '../../Koneksi-DSA/Koneksi-DSA.php';

$query = "SELECT id_kunci, created_at, kode_kunci, kunci_privat, kunci_publik FROM ds_kunci_tb";
$result = $koneksi->query($query);

// Mengecek jika query berhasil
if ($result === false) {
    die("Terjadi kesalahan dalam pengambilan data: " . $koneksi->error);
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Menambahkan Font Awesome -->
    <link rel="stylesheet" href="../../Styles/Css/Index-Dokter/Kunci.css">
    <script src="https://cdn.jsdelivr.net/npm/lemonadejs/dist/lemonade.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@lemonadejs/signature/dist/index.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.2/dist/sweetalert2.min.css" rel="stylesheet">
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

        <!-- Formulir untuk menghasilkan kunci privat dan publik -->
        <div class="container mt-4">
            <h2>Generate Kunci Privat dan Publik</h2>

            <?php if (isset($_SESSION['success_key'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <strong>Berhasil!</strong> <?php echo $_SESSION['success_key']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_key']); ?>
            <?php elseif (isset($_SESSION['error_key'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle"></i> <strong>Gagal!</strong> <?php echo $_SESSION['error_key']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_key']); ?>
            <?php endif; ?>
            <!-- Modal untuk generate kunci -->
            <div class="modal fade" id="generateKeyModal" tabindex="-1" aria-labelledby="generateKeyModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="generateKeyModalLabel">Generate Kunci Privat dan Publik</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="./Proses-Page/Proses_Tambah_Kunci.php" method="POST">
                                <div class="form-group">
                                    <label for="keyLength">Panjang Kunci (bit):</label>
                                    <br>
                                    <select class="form-control" id="keyLength" name="keyLength" required>
                                        <option value="" selected disabled>Pilih Panjang Kunci</option>
                                        <option value="1024">1024 bit</option>
                                        <option value="2048">2048 bit ( Rekomendasi )</option>
                                        <option value="4096">4096 bit</option>
                                    </select>
                                </div>

                                <!-- Input untuk Kode Kunci -->
                                <div class="form-group mt-3">
                                    <label for="keyCode">Kode Kunci:</label>
                                    <br>
                                    <input type="text" class="form-control" id="keyCode" name="keyCode" required>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Generate Kunci</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Tabel Kunci yang Dihasilkan -->
            <div class="mt-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Dibuat</th>
                            <th>Kode Kunci</th>
                            <th>Kunci Private</th>
                            <th>Kunci Publik</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Inisialisasi nomor urut
                        $no = 1;

                        // Menampilkan data dari database
                        while ($row = $result->fetch_assoc()) {
                            // Mengambil dan memformat tanggal
                            $created_at = new DateTime($row['created_at']);
                            $formatted_date = $created_at->format('d F Y'); // Format: 12 Desember 2024
                            $keyId = $row['id_kunci'];
                            $keyCode = $row['kode_kunci'];
                            $privateKey = $row['kunci_privat'];
                            $publicKey = $row['kunci_publik'];
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $formatted_date; ?></td>
                                <td><?php echo $keyCode; ?></td>
                                <td>
                                    <button class="btn btn-secondary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#passwordModal"
                                        data-key-id="<?php echo htmlspecialchars($keyId, ENT_QUOTES); ?>"
                                        data-key-type="private"
                                        data-key-value="<?php echo htmlspecialchars($row['kunci_privat'], ENT_QUOTES); ?>">
                                        <i class="fas fa-eye"></i> <!-- Ikon untuk melihat -->
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-secondary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#passwordModal"
                                        data-key-id="<?php echo htmlspecialchars($keyId, ENT_QUOTES); ?>"
                                        data-key-type="public"
                                        data-key-value="<?php echo htmlspecialchars($row['kunci_publik'], ENT_QUOTES); ?>">
                                        <i class="fas fa-eye"></i> <!-- Ikon untuk melihat -->
                                    </button>
                                </td>

                                <td>
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> <!-- Ikon untuk edit -->
                                    </button>
                                    <!-- Tombol Hapus -->
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteKeyModal" data-keyid="<?php echo $row['id_kunci']; ?>">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <button class="btn mb-3" data-bs-toggle="modal" data-bs-target="#generateKeyModal">Tambah Kunci</button>
            </div>

            <div class="modal fade" id="editKeyModal" tabindex="-1" aria-labelledby="editKeyModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editKeyModalLabel">Edit Kunci</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="./Proses-Page/Proses_Edit_Kunci.php" method="POST">
                                <input type="hidden" id="editKeyId" name="keyId">

                                <div class="mb-3">
                                    <label for="editKeyCode" class="form-label">Kode Kunci:</label>
                                    <input type="text" class="form-control" id="editKeyCode" name="keyCode" required>
                                </div>

                                <div class="mb-3">
                                    <label for="editKeyLength" class="form-label">Panjang Kunci (bit):</label>
                                    <select class="form-select" id="editKeyLength" name="keyLength" required>
                                        <option value="" selected disabled>Pilih Panjang Kunci</option>
                                        <option value="1024">1024 bit</option>
                                        <option value="2048">2048 bit</option>
                                        <option value="4096">4096 bit</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Konfirmasi -->
            <div class="modal fade" id="deleteKeyModal" tabindex="-1" aria-labelledby="deleteKeyModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteKeyModalLabel">Hapus Kunci</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus kunci ini?</p>
                            <form action="./Proses-Page/Proses_Hapus_Kunci.php" method="POST">
                                <input type="hidden" id="deleteKeyId" name="keyId">
                                <button type="submit" class="btn btn-danger">Hapus</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Modal Password -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Masukkan Kode Akses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modalKeyId" class="form-label">ID Kunci</label>
                        <input type="text" class="form-control" id="modalKeyId" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="modalKeyType" class="form-label">Jenis Kunci</label>
                        <input type="text" class="form-control" id="modalKeyType" readonly>
                    </div>
                    <!-- Input Tersembunyi untuk Key Value -->
                    <input type="hidden" id="modalKeyValue">
                    <div class="mb-3">
                        <label for="inputPassword" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" id="inputPassword" placeholder="Masukkan Kata Sandi">
                    </div>
                    <p id="error-message" class="text-danger" style="display: none;">Kode atau kata sandi salah, silakan coba lagi.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="submitAccessButton">Verifikasi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk menampilkan kunci akses -->
    <div class="modal fade" id="keyModal" tabindex="-1" aria-labelledby="keyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header Modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="keyModalLabel">Kunci Akses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <!-- Body Modal -->
                <div class="modal-body">
                    <!-- Menampilkan ID Kunci dan Jenis Kunci di atas, Nilai Kunci di bawah -->
                    <div>
                        <p><strong>ID Kunci:</strong> <span id="keyIdContent"></span></p>
                        <p><strong>Jenis Kunci:</strong> <span id="keyTypeContent"></span></p>
                    </div>
                    <div>
                        <p><strong>Nilai Kunci:</strong> <br>
                            <span id="keyValueContent"></span>
                        </p>
                    </div>
                </div>
                <!-- Footer Modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
        document.addEventListener('DOMContentLoaded', () => {
            const passwordModal = document.getElementById('passwordModal');
            const submitAccessButton = document.getElementById('submitAccessButton');
            const errorMessage = document.getElementById('error-message');
            const keyModal = new bootstrap.Modal(document.getElementById('keyModal'));
            const keyContent = document.getElementById('keyContent');

            passwordModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget; // Tombol yang memicu modal

                // Ambil data dari atribut tombol
                const keyId = button.getAttribute('data-key-id');
                const keyType = button.getAttribute('data-key-type');
                const keyValue = button.getAttribute('data-key-value');

                // Masukkan data ke dalam elemen modal
                document.getElementById('modalKeyId').value = keyId;
                document.getElementById('modalKeyType').value = keyType;
                document.getElementById('modalKeyValue').value = keyValue;
            });

            submitAccessButton.addEventListener('click', () => {
                const inputPassword = document.getElementById('inputPassword').value;
                const keyValue = document.getElementById('modalKeyValue').value;

                if (inputPassword === '160901') {
                    // Password benar, sembunyikan modal pertama (passwordModal)
                    const passwordModalInstance = bootstrap.Modal.getInstance(passwordModal);
                    passwordModalInstance.hide();

                    // Tampilkan modal kedua (keyModal)
                    document.getElementById('keyIdContent').textContent = document.getElementById('modalKeyId').value;
                    document.getElementById('keyTypeContent').textContent = document.getElementById('modalKeyType').value;
                    document.getElementById('keyValueContent').textContent = keyValue;

                    errorMessage.style.display = 'none'; // Sembunyikan pesan error
                    keyModal.show(); // Menampilkan modal kedua (keyModal)
                } else {
                    // Password salah, tampilkan pesan error
                    errorMessage.style.display = 'block';
                }
            });
        });
        document.addEventListener('DOMContentLoaded', () => {
            // Event listener untuk tombol Edit
            document.querySelectorAll('.btn-warning').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const keyId = row.querySelector('td:nth-child(1)').textContent.trim();
                    const keyCode = row.querySelector('td:nth-child(3)').textContent.trim();
                    const keyLength = ""; // Tambahkan logika untuk panjang kunci

                    document.getElementById('editKeyId').value = keyId;
                    document.getElementById('editKeyCode').value = keyCode;
                    document.getElementById('editKeyLength').value = keyLength;

                    const editModal = new bootstrap.Modal(document.getElementById('editKeyModal'));
                    editModal.show();
                });
            });

            // Event listener untuk tombol Hapus
            const deleteKeyModal = document.getElementById('deleteKeyModal');
    deleteKeyModal.addEventListener('show.bs.modal', function (event) {
        // Tombol yang memicu modal
        const button = event.relatedTarget;

        // Ambil data-id dari tombol
        const keyId = button.getAttribute('data-keyid');

        // Masukkan ke input hidden dalam modal
        const inputKeyId = deleteKeyModal.querySelector('#deleteKeyId');
        inputKeyId.value = keyId;
    });
        });
    </script>


</body>

</html>