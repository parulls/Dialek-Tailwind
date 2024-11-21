<?php
    $host = 'aws-0-ap-southeast-1.pooler.supabase.com';
    $user = 'postgres.ifhedwymtdwjybimejrq';
    $password = 'dialekdevwomen';
    $port = '6543';
    $dbname = 'postgres';

    try {
        // Create a new PDO instance for Supabase
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    
        // Set PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Optional: Set default fetch mode to associative array
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
        echo "Connected to Supabase successfully!"; // Debugging message
    } catch (PDOException $e) {
        // Catch and display connection error
        die("Failed to connect to Supabase: " . $e->getMessage());
    }
?>
