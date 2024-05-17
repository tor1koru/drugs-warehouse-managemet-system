<?php
include_once "../database/db_connection.php";

// Перевірка, чи передано id медикаменту
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL-запит для видалення медикаменту з вказаним id
    $sql = "DELETE FROM Medicines WHERE id_med=$id";

    // Виконання запиту
    if ($conn->query($sql) === TRUE) {
        echo "Медикамент успішно видалено.";
    } else {
        echo "Помилка видалення медикаменту: " . $conn->error;
    }
} else {
    echo "Не передано параметр id.";
}

// Закриття з'єднання з базою даних
$conn->close();

// Повернення до med.php
header("Location: ../pages/med.php");
exit;
?>
