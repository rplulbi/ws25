<?php
// Konfigurasi API OpenWeatherMap
$apiKey = "61aaba206e018026fc31579d100fa9cd"; // Ganti dengan API Key Anda
$city = $_GET['city'] ?? "Bandung"; // Kota default atau dari input pengguna
$url = "https://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$apiKey&units=metric";

// Opsi konteks untuk menonaktifkan verifikasi SSL (opsional)
$options = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
];

// Mengambil data prakiraan cuaca 5 hari
$response = file_get_contents($url, false, stream_context_create($options));
$data = json_decode($response, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forecast</title>
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
        .weather-icon {
            width: 60px;
            height: 60px;
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
                <h2 class="mb-4">Prakiraan Cuaca 5 Hari Mendatang</h2>

                <!-- Form Input Kota -->
                <form method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="city" class="form-control" placeholder="Masukkan nama kota" required value="<?= htmlspecialchars($city) ?>">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>

                <!-- Menampilkan Data Prakiraan Cuaca -->
                <?php if ($data && isset($data['list'])): ?>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Prakiraan Cuaca 5 Hari di <?= htmlspecialchars($city) ?></h4>
                            <div class="row">
                                <?php
                                $uniqueDays = [];
                                $dayMapping = [
                                    'Monday' => 'Senin',
                                    'Tuesday' => 'Selasa',
                                    'Wednesday' => 'Rabu',
                                    'Thursday' => 'Kamis',
                                    'Friday' => 'Jumat',
                                    'Saturday' => 'Sabtu',
                                    'Sunday' => 'Minggu',
                                ];
                                foreach ($data['list'] as $forecast) {
                                    $date = strtotime($forecast['dt_txt']);
                                    $dayName = date('l', $date); // Nama hari dalam bahasa Inggris
                                    $dayDate = date('Y-m-d', $date); // Tanggal unik
                                    $formattedDate = date('d M Y', $date); // Format tanggal (contoh: 05 Oct 2023)

                                    // Hindari duplikasi hari
                                    if (!in_array($dayDate, $uniqueDays)) {
                                        $uniqueDays[] = $dayDate;
                                        $indonesianDay = $dayMapping[$dayName] ?? $dayName;
                                        ?>
                                        <div class="col-md-2 text-center mb-4">
                                            <img src="https://openweathermap.org/img/wn/<?= htmlspecialchars($forecast['weather'][0]['icon']) ?>@2x.png" alt="Weather Icon" class="weather-icon">
                                            <p><strong><?= htmlspecialchars($indonesianDay) ?></strong></p>
                                            <p><?= htmlspecialchars($forecast['main']['temp']) ?> °C</p>
                                            <p><?= htmlspecialchars($forecast['weather'][0]['description']) ?></p>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php elseif ($data && isset($data['cod']) && $data['cod'] == 404): ?>
                    <div class="alert alert-danger">
                        Kota tidak ditemukan. Silakan coba lagi dengan nama kota yang valid.
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        Terjadi kesalahan saat memuat data cuaca. Silakan coba lagi nanti.
                    </div>
                <?php endif; ?>
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