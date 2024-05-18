<?php
// Підключення до бази даних
include_once "../database/db_connection.php";

// Перевірка, чи передано параметр id в запиті GET
if (isset($_GET['id'])) {
    // Отримання id відділу з запиту GET
    $dep_id = $_GET['id'];

    // SQL-запит для видалення відділу з бази даних
    $sql = "DELETE FROM Department WHERE id_dep = $dep_id";

    // Виконання запиту
    if ($conn->query($sql) === TRUE) {
        echo "Відділ успішно видалено";
    } else {
        echo "Помилка видалення відділу: " . $conn->error;
    }
} else {
    echo "Не передано параметр id";
}

// Закриття з'єднання з базою даних
$conn->close();

// Повернення до списку відділів (med.php)
header("Location: ../pages/dep.php");
exit();
?>
