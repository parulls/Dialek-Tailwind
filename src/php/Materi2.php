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
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #b4b4b464;
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
        <div id="materi" class="flex flex-col text-sm p-8 text-custom2 bg-transparent shadow-2xl rounded-3xl opacity-90">
            <p class="font-medium">
                Dalam sebuah kalimat, subjek adalah orang atau benda yang melakukan tindakan. Dalam bahasa Batak Toba, subjek bisa berupa kata ganti orang atau nama benda/orang tertentu. 
                Berikut ini adalah beberapa kata ganti orang yang digunakan sebagai subjek dalam Bahasa Batak Toba:
            </p>
            <div class="my-4 flex items-center justify-center">
                <table class="bg-white table-auto border-collapse">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kata Ganti</th>
                            <th>Arti</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Au / Ahu</td>
                            <td>Aku</td>
                            <td>Kata ganti orang pertama tunggal</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Ho</td>
                            <td>Kamu</td>
                            <td>Kurang halus, informal</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Ibana / Imana</td>
                            <td>Dia (laki-laki/perempuan)</td>
                            <td>Kurang halus, informal</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Hasida / Nasida / Halaki</td>
                            <td>Mereka/Dia</td>
                            <td>Halus, lebih sopan</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Hami</td>
                            <td>Kami</td>
                            <td>Kata ganti orang pertama jamak</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Hamu</td>
                            <td>Kalian / Kamu</td>
                            <td>Halus, lebih sopan</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>On</td>
                            <td>Kata ganti benda</td>
                            <td>Menggantikan "itu" untuk benda</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <p class="font-medium">
                Penggunaan kata ganti dalam Bahasa Batak Toba sangat dipengaruhi oleh konteks formalitas dan hubungan sosial. Kata ganti yang lebih halus biasanya digunakan untuk menunjukkan rasa hormat, terutama kepada orang yang lebih tua atau dalam situasi formal.
            </p>
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
        const kembali = document.getElementById("kembali-button");
        const selanjutnya = document.getElementById("selanjutnya-button");

        kembali.addEventListener("click", () => {
            window.location.href = "Materi.php";
        });

        selanjutnya.addEventListener("click", () => {
            window.location.href = "MateriRating.php";
        });
    </script>
</body>
</html>
