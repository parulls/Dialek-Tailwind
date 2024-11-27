<?php
    require '.functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dialek.Id</title>
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .stars i {
            font-size: 5rem;
            color: #0D4422;
        }
    </style>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-4 py-4">
        <div class="logo font-irish text-2xl">dialek.id</div>
        <div class="flex items-center font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>
    <section class="main flex flex-col mx-auto w-4/5 items-center flex-grow space-y-16">
        <div class="flex flex-col justify-center items-center">
            <div class="stars flex mb-16">
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
            </div>
            <textarea name="komentar" id="komentar-materi" class="bg-custom7 w-full h-40 p-4 font-semibold text-sm rounded-3xl placeholder-gray-500 text-gray-500 border-black focus:outline-none focus:ring-2" placeholder="Tuliskan Komentar"></textarea>
        </div>
        <div class="flex items-center justify-center w-full px-4 py-4">
            <button id="kirim-button" class="button-custom2 h-12 rounded-md text-sm px-12">
                Kirim
            </button>
        </div>
    </section>
    <footer class="flex items-center justify-between w-full px-4 py-4">
        <button id="kembali-button" class="button-custom2 text-sm mx-6">
            Kembali
        </button>
        <button id="selanjutnya-button" class="button-custom2 text-sm mx-6">
            Selanjutnya
        </button>
    </footer>
    <script>
        const stars = document.querySelectorAll(".stars i");
        const kembali = document.getElementById("kembali-button");
        const selanjutnya = document.getElementById("selanjutnya-button");
        const kirim = document.getElementById("kirim-button");
        const komentar = document.getElementById("komentar-materi");

        let ratingGiven = false;
        let komentarGiven = false;

        // Kembali ke halaman sebelumnya
        kembali.addEventListener("click", () => {
            window.location.href = "Materi2.php";
        });

        // Menandai rating telah diberikan
        stars.forEach((star, index1) => {
            star.addEventListener("click", () => {
                stars.forEach((star, index2) => {
                    if (index1 >= index2) {
                        star.classList.remove("fa-regular");
                        star.classList.add("fa-solid", "active");
                    } else {
                        star.classList.remove("fa-solid", "active");
                        star.classList.add("fa-regular");
                    }
                });
                ratingGiven = true;
                enableNextButton();
            });
        });

        // Fungsi untuk mengaktifkan tombol Selanjutnya
        function enableNextButton() {
            if (ratingGiven && komentarGiven) {
                selanjutnya.disabled = false;
                selanjutnya.classList.remove("opacity-50");
            } else {
                selanjutnya.disabled = true;
                selanjutnya.classList.add("opacity-50");
            }
        }

        // Validasi untuk mengirim komentar
        kirim.addEventListener("click", () => {
            if (komentar.value.trim() === "") {
                alert("Komentar tidak boleh kosong!");
            } else if (!ratingGiven) {
                alert("Silakan beri rating sebelum mengirim komentar!");
            } else {
                alert("Komentar berhasil dikirim!");
                komentarGiven = true;
                enableNextButton();

                // Reset komentar setelah dikirim
                komentar.value = "";
            }
        });

        // Cek apakah bisa lanjut ke halaman berikutnya
        selanjutnya.addEventListener("click", () => {
            if (ratingGiven && komentarGiven) {
                window.location.href = "LatihanSoal1.php";
            } else {
                alert("Harap isi komentar dan beri rating sebelum melanjutkan.");
            }
        });

        // Inisialisasi tombol Selanjutnya saat pertama kali halaman dimuat
        enableNextButton();
    </script>
</body>
</html>