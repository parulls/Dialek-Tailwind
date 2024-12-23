<?php
include("../connect.php");

if (!$conn) {
    echo "<p>Koneksi database gagal</p>";
    exit;
}

$id_material = 4;
$query = "SELECT id_material, material_title, material_content FROM materials WHERE id_material = :id_material";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_material', $id_material, PDO::PARAM_INT);
$stmt->execute();
$materials = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dialek.Id - Detail Materi</title>
    <link rel="stylesheet" href="../../styles/style2.css">
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
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div class="logo font-irish m-0 text-2xl">dialek.id</div>
        <div class="flex items-center m-0 font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

    <section class="main flex flex-col mx-auto w-4/5 items-center flex-grow space-y-16 text-xl p-8 text-custom2 min-h-[500px] h-full drop-shadow-2xl" id="content-section">
        <?php if (!empty($materials)): ?>
            <div class="materi-item">
                <h2><?= htmlspecialchars($materials['material_title']); ?></h2>
                <p><?= nl2br(htmlspecialchars($materials['material_content'])); ?></p>
            </div>
        <?php else: ?>
            <p>Materi tidak ditemukan.</p>
        <?php endif; ?>
    </section>

    <footer class="flex items-center justify-between w-full px-4 py-4">
        <button id="kembali-button" class="button-custom2 text-sm mx-6">
            Kembali
        </button>
    </footer>

    <script>
        const kembaliButton = document.getElementById("kembali-button");

        kembaliButton.addEventListener("click", function() {
            window.location.href = "../html/Materi.html";
        });
    </script>

</body>
</html>
