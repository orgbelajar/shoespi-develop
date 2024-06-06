<?php

$db_name = 'mysql:host=localhost;dbname=db_shoespi_dvlp';
$user_name = 'root';
$user_password = '';

try {
    $conn = new PDO($db_name, $user_name, $user_password);
} catch (PDOException $e) {
    //Tangani kesalahan koneksi
    die('Database connection failed: ' . $e->getMessage());
}
