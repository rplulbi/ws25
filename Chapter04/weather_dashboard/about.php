<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Warna latar belakang abu-abu muda */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            height: 100vh;
            background-color: #212529; /* Warna sidebar gelap */
            color: white;
            padding: 20px;
        }
        .content {
            padding: 20px;
        }
        .logo {
            width: 100%;
            max-width: 150px; /* Ukuran maksimal logo */
            margin-bottom: 20px;
        }
        footer {
            background-color: #212529;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <img src="https://i.imgur.com/uzusvSt.png" alt="Logo" class="logo mx-auto d-block">
                <h4 class="text-white text-center">Weather Dashboard</h4>
                <ul class="nav flex-column mt-4">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="forecast.php">Forecast</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="about.php">About</a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 content">
                <h2 class="mb-4">Tentang Aplikasi Ini</h2>
                <p>Aplikasi Weather Dashboard ini dibuat sebagai proyek pemrograman untuk menampilkan informasi cuaca terkini dan prakiraan cuaca 5 hari ke depan menggunakan API dari OpenWeatherMap.</p>
                <p>Aplikasi ini dirancang agar mudah digunakan dan memberikan informasi yang relevan kepada pengguna, seperti suhu, kelembaban, kecepatan angin, dan deskripsi cuaca.</p>
                <h4>Profile Pengembang</h4>
                <ul>
                    <li>Nama: Yadi Mulyadi</li>
                    <li>NPM: 714222074</li>
                    <li>Program Studi: Teknik Informatika</li>
                    <li>Universitas: Universitas Logistik dan Bisnis Internasional</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        © 20254 ‧ Iday+. All rights reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>