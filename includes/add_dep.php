<?php
// Підключення до бази даних
include_once "../database/db_connection.php";

// Якщо форма була відправлена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання даних з форми
    $name_dep = $_POST['name_dep'];

    // Перевірка наявності такого самого відділу в базі даних
    $check_sql = "SELECT * FROM Department WHERE name_dep = '$name_dep'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Такий відділ вже існує.";
        header("location: ../pages/dep.php");
    } else {
        // SQL-запит для додавання нового відділу
        $sql = "INSERT INTO Department (name_dep) VALUES ('$name_dep')";

        // Виконання запиту
        if ($conn->query($sql) === TRUE) {
            // Повернення до списку відділів (med.php)
            header("Location: ../pages/dep.php");
        } else {
            echo "Помилка додавання відділу: " . $conn->error;
        }
    }
}

// Закриття з'єднання з базою даних
$conn->close();
?>


