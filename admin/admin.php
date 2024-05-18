<?php
require_once '../database/db_connection.php';
session_start();


$login =$_POST["login"];
$password =$_POST["password"];

$sql = "SELECT * FROM Staff WHERE login = '$login' AND password = '$password'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();


if($row['login'] == "admin" && $row['password'] == "admin"){
    header("location:../pages/home.php");
    exit();
}
else{
    $sql = "SELECT * FROM Staff WHERE login = '$login' AND password = '$password'";

// Виконання запиту
    $result = $conn->query($sql);

// Перевірка результату запиту
    if ($result) {
        // Отримання рядка даних з результату запиту
        $row = $result->fetch_assoc();

        // Перевірка, чи знайдено id_dep для вказаного логіну
        if ($row) {
            // Отримання id_dep
            $id_dep = $row['id_dep'];

            // Перенаправлення на іншу сторінку з параметром id_dep
            header("Location: ../pages/dep_storage.php?id_dep=$id_dep");
            exit(); // Важливо викликати exit() після header() для припинення подальшого виконання скрипту
        } else {
            // Якщо id_dep не знайдено для вказаного логіну
            echo "No id_dep found for the specified login.";
        }
    } else {
        // Якщо виникла помилка під час виконання запиту
        echo "Error: " . $conn->error;
    }
}
?>
