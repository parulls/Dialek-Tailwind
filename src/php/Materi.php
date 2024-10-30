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
        .stars i.active {
            color: #177b49;
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
        <div class="bg-shadow2 flex-col flex justify-between items-center">
            <div class="text-center">
                <h1 class="text-gradient font-extrabold text-6xl">SUBJEK</h1>
            </div>
            <button id="mulai-button" class="button-custom drop-shadow-sm mt-6">
                <p class="text-center font-bold text-md mx-6">Mulai!</p>
            </button>
        </div>
        <div class="rating-box bg-shadow2 flex justify-center items-center flex-col">
            <div class="text-2xl w-1/2 items-center justify-center flex-row flex">
                <div class="stars text-bar justify-between">
                    <i class="fa-solid fa-star cursor-pointer"></i>
                    <i class="fa-solid fa-star cursor-pointer"></i>
                    <i class="fa-solid fa-star cursor-pointer"></i>
                    <i class="fa-solid fa-star cursor-pointer"></i>
                    <i class="fa-solid fa-star cursor-pointer"></i>
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
        <button class="button-custom2 text-sm mx-6">
            Kembali
        </button>
    </footer>

    <script>
        const stars = document.querySelectorAll(".stars i");
        const ratingValue = document.getElementById("rating-value");
        const progressBars = document.querySelectorAll(".progress");
        const mulai = document.getElementById("mulai-button")

        stars.forEach((star, index1) => {
            star.addEventListener("click", () => {
                stars.forEach((star, index2) => {
                    index1 >= index2 ? star.classList.add("active") : star.classList.remove("active");
                });
                const rating = index1 + 1;
                ratingValue.textContent = `${rating}/5`;

                progressBars.forEach(progress => {
                    const barRating = parseInt(progress.getAttribute("data-rating"));
                    progress.classList.toggle("active", barRating === rating);
                    progress.style.setProperty('--bar-width', barRating === rating ? '100%' : '0');
                });
            });
        });

        mulai.addEventListener("click", () => {
            window.location.href = "Materi2.html";
        });
    </script>
</body>
</html>
