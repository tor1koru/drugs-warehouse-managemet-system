<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "СompofMed";

// Підключення до бази даних
$conn = new mysqli($servername, $username, $password, $database);

// Перевірка з'єднання
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}
?>
