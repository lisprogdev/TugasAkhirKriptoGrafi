<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Menambahkan Font Awesome -->
    <link rel="stylesheet" href="../Styles/Css/Index-Dokter/Index-Dokter.css">
    <title>Dashboard Dokter</title>
</head>

<body>

    <!-- Sidebar -->
    <div id="sidebar" class="bg-dark text-white p-4" style="width: 250px; height: 100vh; position: fixed; left: 0; transition: left 0.3s;">
        <div class="text-center mb-4">
            <h4>Judul</h4>
            <p>Username</p>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white active" href="./Index-Dokter.php" id="dashboardLink"><i class="fas fa-tachometer-alt"></i> Ringkasan</a> <!-- Ikon untuk Ringkasan -->
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="./Page/Perawat.php" id="nurseLink"><i class="fas fa-user-md"></i> Perawat</a> <!-- Ikon untuk Perawat -->
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#" id="patientLink"><i class="fas fa-procedures"></i> Pasien</a> <!-- Ikon untuk Pasien -->
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#" id="reportLink"><i class="fas fa-file-alt"></i> Laporan</a> <!-- Ikon untuk Laporan -->
            </li>
        </ul>
        <!-- Tombol Keluar -->
        <div class="mt-4">
            <button class="btn btn-danger w-100" id="logoutButton">Keluar</button>
        </div>
    </div>



    <!-- Tombol Toggle Sidebar -->
    <button class="btn btn-dark" id="toggleSidebar" style="position: absolute; top: 20px; left: 20px;">
        <i class="fas fa-bars"></i> Menu <!-- Ikon untuk tombol toggle sidebar -->
    </button>

    <!-- Konten utama -->
    <div id="content" class="content" style="padding: 20px; margin-left: 250px; transition: margin-left 0.3s;">
        <h1>Dashboard Dokter</h1>
        <p>Selamat datang di dashboard dokter.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script>
        // Mengontrol pembukaan dan penutupan sidebar
        const toggleSidebarBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        toggleSidebarBtn.addEventListener('click', () => {
            // Toggle sidebar visibility
            if (sidebar.style.left === '0px') {
                sidebar.style.left = '-250px'; // Menyembunyikan sidebar
                content.style.marginLeft = '0'; // Menyesuaikan konten
            } else {
                sidebar.style.left = '0'; // Menampilkan sidebar
                content.style.marginLeft = '250px'; // Menyesuaikan konten
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

        // Menangani aksi logout
        document.getElementById("logoutButton").addEventListener("click", function() {
            // Logika logout bisa ditambahkan di sini, seperti mengarahkan ke halaman login
            window.location.href = "/logout"; // Ganti dengan URL logout Anda
        });
    </script>

</body>

</html>