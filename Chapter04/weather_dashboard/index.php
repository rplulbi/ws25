<?php
// Konfigurasi API OpenWeatherMap
$apiKey = "61aaba206e018026fc31579d100fa9cd"; // Ganti dengan API Key Anda
$city = $_GET['city'] ?? "Bandung"; // Kota default atau dari input pengguna
$url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";

// Opsi konteks untuk menonaktifkan verifikasi SSL (opsional)
$options = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
];

// Mengambil data cuaca saat ini
$response = file_get_contents($url, false, stream_context_create($options));
$data = json_decode($response, true);

// Fungsi untuk menampilkan ikon cuaca
function getWeatherIcon($iconCode) {
    return "https://openweathermap.org/img/wn/$iconCode@2x.png";
}

// URL untuk prakiraan cuaca 5 hari
$forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$apiKey&units=metric";
$forecastResponse = file_get_contents($forecastUrl, false, stream_context_create($options));
$forecastData = json_decode($forecastResponse, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard</title>
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
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .card.show {
            opacity: 1;
            transform: translateY(0);
        }
        .weather-icon {
            width: 80px;
            height: 80px;
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
                <h2 class="mb-4">Gambaran Umum Cuaca</h2>

                <!-- Form Input Kota -->
                <form method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="city" class="form-control" placeholder="Masukkan nama kota" required value="<?= htmlspecialchars($city) ?>">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>

                <!-- Menampilkan Data Cuaca -->
                <?php if ($data && isset($data['main'])): ?>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="<?= getWeatherIcon($data['weather'][0]['icon']) ?>" alt="Weather Icon" class="weather-icon">
                                    <h4><?= htmlspecialchars($data['weather'][0]['description']) ?></h4>
                                </div>
                                <div class="col-md-8">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Kota:</strong> <?= htmlspecialchars($data['name']) ?></li>
                                        <li class="list-group-item"><strong>Suhu:</strong> <?= htmlspecialchars($data['main']['temp']) ?> °C</li>
                                        <li class="list-group-item"><strong>Kelembaban:</strong> <?= htmlspecialchars($data['main']['humidity']) ?> %</li>
                                        <li class="list-group-item"><strong>Kecepatan Angin:</strong> <?= htmlspecialchars($data['wind']['speed']) ?> m/s</li>
                                        <li class="list-group-item"><strong>Tekanan Udara:</strong> <?= htmlspecialchars($data['main']['pressure']) ?> hPa</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prakiraan Cuaca 5 Hari -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Prakiraan Cuaca 5 Hari</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Hari & Tanggal</th>
                                        <th>Suhu (°C)</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($forecastData && isset($forecastData['list'])) {
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
                                        foreach ($forecastData['list'] as $forecast) {
                                            $date = strtotime($forecast['dt_txt']);
                                            $dayName = date('l', $date); // Nama hari dalam bahasa Inggris
                                            $dayDate = date('Y-m-d', $date); // Tanggal unik
                                            $formattedDate = date('d M Y', $date); // Format tanggal (contoh: 05 Oct 2023)

                                            // Hindari duplikasi hari
                                            if (!in_array($dayDate, $uniqueDays)) {
                                                $uniqueDays[] = $dayDate;
                                                $indonesianDay = $dayMapping[$dayName] ?? $dayName;
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars("$indonesianDay, $formattedDate") ?></td>
                                                    <td><?= htmlspecialchars($forecast['main']['temp']) ?></td>
                                                    <td><?= htmlspecialchars($forecast['weather'][0]['description']) ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
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

    <!-- Animasi Kartu Cuaca -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cards = document.querySelectorAll(".card");
            cards.forEach(card => card.classList.add("show"));
        });
    </script>
</body>
</html>