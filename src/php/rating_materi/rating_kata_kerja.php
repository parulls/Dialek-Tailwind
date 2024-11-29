<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Sanitasi input materi_id
    $materi_id = isset($_GET['materi_id']) ? intval($_GET['materi_id']) : 2;

    if ($materi_id > 0) {
        // Query untuk menghitung rata-rata rating dan distribusi rating
        $query = "
            SELECT 
                AVG(rating) AS average_rating, 
                COUNT(*) AS total_ratings,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) AS star_5,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) AS star_4,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) AS star_3,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) AS star_2,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) AS star_1
            FROM materi_ratings
            WHERE materi_id = ?
        ";

        if ($stmt = $conn->prepare($query)) {
            $stmt->bindValue(1, $materi_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                // Pastikan data yang diterima valid
                $response = [
                    "status" => "success",
                    "average_rating" => round($result['average_rating'], 2),
                    "total_ratings" => $result['total_ratings'],
                    "ratings_distribution" => [
                        "5" => intval($result['star_5']),
                        "4" => intval($result['star_4']),
                        "3" => intval($result['star_3']),
                        "2" => intval($result['star_2']),
                        "1" => intval($result['star_1'])
                    ]
                ];
                echo json_encode($response);
            } else {
                // Penanganan error jika query gagal
                echo json_encode(["status" => "error", "message" => "Gagal mendapatkan data rating."]);
            }
            $stmt = null;
        } else {
            // Penanganan error koneksi database
            echo json_encode(["status" => "error", "message" => "Koneksi ke database gagal."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ID materi tidak valid."]);
    }
    $conn = null;
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dialek.id - Platform untuk belajar topik dengan rating pengguna">
    <meta name="author" content="Dialek.id">
    <title>Dialek.Id</title>
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .stars i.active {
            color: #177b49;
        }

        .loading {
            display: none;
        }
    </style>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2 cursor-pointer">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>
    <section class="main flex flex-col mx-auto w-4/5 items-center flex-grow space-y-16">
        <div class="bg-shadow2 flex-col flex justify-between items-center">
            <div class="text-center">
                <h1 class="text-gradient font-extrabold text-6xl">KATA KERJA</h1>
            </div>
            <button id="mulai-button" class="button-custom drop-shadow-sm mt-6">
                <p class="text-center font-bold text-md mx-6">Mulai!</p>
            </button>
        </div>
        <div class="rating-box bg-shadow2 flex justify-center items-center flex-col">
            <div class="text-2xl w-1/2 items-center justify-center flex-row flex">
                <div class="stars text-bar justify-between">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <p id="rating-value" class="text-lg font-bold ml-1">0/5</p>
                </div>
            </div>
            <div class="progress-bars mt-6 w-full">
                <div class="bar flex items-center mb-2">
                    <span class="font-bold">5/5</span>
                    <div class="progress flex-1 h-4 rounded-full ml-2 relative overflow-hidden">
                        <div class="fill h-full bg-bar transition-all duration-300"></div>
                    </div>
                </div>
                <div class="bar flex items-center mb-2">
                    <span class="font-bold">4/5</span>
                    <div class="progress flex-1 h-4 rounded-full ml-2 relative overflow-hidden">
                        <div class="fill h-full bg-bar transition-all duration-300"></div>
                    </div>
                </div>
                <div class="bar flex items-center mb-2">
                    <span class="font-bold">3/5</span>
                    <div class="progress flex-1 h-4 rounded-full ml-2 relative overflow-hidden">
                        <div class="fill h-full bg-bar transition-all duration-300"></div>
                    </div>
                </div>
                <div class="bar flex items-center mb-2">
                    <span class="font-bold">2/5</span>
                    <div class="progress flex-1 h-4 rounded-full ml-2 relative overflow-hidden">
                        <div class="fill h-full bg-bar transition-all duration-300"></div>
                    </div>
                </div>
                <div class="bar flex items-center">
                    <span class="font-bold">1/5</span>
                    <div class="progress flex-1 h-4 rounded-full ml-2 relative overflow-hidden">
                        <div class="fill h-full bg-bar transition-all duration-300"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="flex items-center justify-between w-full px-4 py-4">
        <button id="kembali-button" class="button-custom2 text-sm mx-6">
            Kembali
        </button>
    </footer>

    <script>
        const stars = document.querySelectorAll(".stars i");
        const ratingValue = document.getElementById("rating-value");
        const progressBars = document.querySelectorAll(".progress .fill");

        const materiId = 1; // Ganti sesuai kebutuhan

        // Fungsi untuk memvalidasi data sebelum ditampilkan
        function validateData(data) {
            if (
                typeof data.average_rating === "number" &&
                typeof data.total_ratings === "number" &&
                typeof data.ratings_distribution === "object" &&
                Object.keys(data.ratings_distribution).length === 5
            ) {
                return true;
            }
            return false;
        }

        // Fungsi untuk memperbarui UI
        function updateUI(data) {
            ratingValue.textContent = `${data.average_rating}/5`;

            const average = Math.round(data.average_rating);
            stars.forEach((star, index) => {
                if (index < average) {
                    star.classList.add("active");
                } else {
                    star.classList.remove("active");
                }
            });

            const totalRatings = data.total_ratings;
            const distributions = data.ratings_distribution;
            progressBars.forEach((bar, index) => {
                const starValue = 5 - index;
                const percentage = totalRatings
                    ? (distributions[starValue] / totalRatings) * 100
                    : 0;
                bar.style.width = `${percentage}%`;
            });
        }

        // Fungsi untuk memuat data rating dengan penanganan loading
        function loadRating() {
            // Menampilkan loading state
            const loadingElement = document.createElement('div');
            loadingElement.classList.add('loading');
            loadingElement.textContent = 'Loading...';
            document.body.appendChild(loadingElement);

            fetch(`get_rating.php?materi_id=${materiId}`)
                .then(response => response.json())
                .then(data => {
                    // Menyembunyikan loading state
                    loadingElement.style.display = 'none';

                    if (data.status === "success" && validateData(data)) {
                        updateUI(data);
                    } else {
                        alert("Data rating tidak valid atau gagal memuat.");
                    }
                })
                .catch(error => {
                    loadingElement.style.display = 'none';
                    console.error("Error:", error);
                    alert("Terjadi kesalahan saat memuat data rating.");
                });
        }

        loadRating();
    </script>
</body>
</html>
