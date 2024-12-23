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
        <div id="soal" class="flex flex-col items-center text-sm p-4 text-custom2 bg-transparent shadow-2xl opacity-90 w-full">
            <div class="flex justify-center items-center font-bold text-lg">
                <p>Latihan Soal</p>
            </div>
            <div class="bg-custom7 w-[70%] flex items-center justify-start mb-12 mt-28 px-4">
                <p class="text-lg font-semibold mb-4">Pilih yang  benar</p>
            </div>
            <div class="flex flex-col items-center w-[70%] mb-28 space-y-4">
                <button class="button-option option w-full" data-answer="Au">
                    Lao do hami mardalani to Medan. (Kami akan pergi berlibur ke Medan)
                </button>
                <button class="button-option option w-full" data-answer="Hami">
                    Halaki karejo di PT Dirgantara Indonesia. (Saya bekerja di PT Dirgantara Indonesia)
                </button>
                <button class="button-option option w-full data-answer="Ibana">
                    On ma Tao Toba. (Inilah Danau Toba)
                </button>
            </div>
        </div>
    </section>
    <footer class="flex items-center justify-between w-full px-4 py-4">
        <button id="kembali-button" class="button-custom2 text-sm mx-6">Kembali</button>
        <button id="keluar-button" class="button-custom2 text-sm mx-6">Keluar</button>
    </footer>
    <script>
        const kembali = document.getElementById("kembali-button");
        const keluar = document.getElementById("keluar-button");

        kembali.addEventListener("click", () => {
            window.location.href = "LatihanSoal1.html";
        });

        keluar.addEventListener("click", () => {
            window.location.href = "dashboardBatak.html";
        });

        function saveAnswer(answer) {
            localStorage.setItem('selectedAnswer', answer);
            displaySavedAnswer();
        }

        function displaySavedAnswer() {
            const savedAnswer = localStorage.getItem('selectedAnswer');
            const savedAnswerElement = document.getElementById('saved-answer');
            if (savedAnswer) {
                savedAnswerElement.textContent = `Jawaban yang Anda pilih: ${savedAnswer}`;
            } else {
                savedAnswerElement.textContent = '';
            }
        }

        document.querySelectorAll('.option').forEach(button => {
            button.addEventListener('click', () => {
                const answer = button.getAttribute('data-answer');
                saveAnswer(answer);
            });
        });

        displaySavedAnswer();
    </script>
</body>
</html>
