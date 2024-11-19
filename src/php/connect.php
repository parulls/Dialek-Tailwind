<?php
    $host = 'aws-0-ap-southeast-1.pooler.supabase.com';
    $user = 'postgres.ifhedwymtdwjybimejrq';
    $password = 'dialekdevwomen';
    $dbname = 'postgres';

    $conn = new mysqli($host, $user, $password, $dbname);

    // if ($conn -> connect_error) {
    //     die ("Koneksi gagal: ". $conn -> connect_error);
    // } else {
    //     echo "Koneksi berhasil";
    // }
?>
