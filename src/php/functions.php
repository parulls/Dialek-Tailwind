<?php 
    // koneksi ke database
    $conn = mysqli_connect("localhost", "root", "", "dialekid");

    function query($query) {
        global $conn;
        $result = mysqli_query($conn, $query);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    function registrasi($data) {
        global $conn;

        $name = strtolower(stripslashes($data["name"]));
        $username = strtolower(stripslashes($data["username"]));
        $email = strtolower(stripslashes($data["email"]));
        $password = mysqli_real_escape_string($conn, $data["password"]);
        $confirmPassword = mysqli_real_escape_string($conn, $data["confirmPassword"]);

        // cek apakah kolom ada yang kosong
        if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            echo "<script>
                    alert('semua kolom harus diisi!');
                  </script>";
            return false;
        }

        // cek username sudah ada atau belum
        $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
        if (mysqli_fetch_assoc($result)) {  
            echo "<script>
                    alert('username sudah terdaftar!');
                  </script>";
            return false;
        }

        // cek email sudah ada atau belum
        $result = mysqli_query($conn, "SELECT email FROM user WHERE email = '$email'");
        if (mysqli_fetch_assoc($result)) {
            echo "<script>
                    alert('E-Mail sudah terdaftar!');
                  </script>";
            return false;
        }

        // cek konfirmasi password
        if ($password !== $confirmPassword) {
            echo "<script>
                    alert('konfirmasi password tidak sesuai!');
                  </script>";
            return false;
        }

        // enkripsi password
        // $password = password_hash($password, PASSWORD_DEFAULT);

        // tambahkan user baru ke database
        mysqli_query($conn, "INSERT INTO user VALUES('', '$name', '$username', '$email', '$password', '', '')");

        return mysqli_affected_rows($conn);
    }
?>