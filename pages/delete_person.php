<?php
include_once "../database/db_connection.php";

// Перевірка, чи отримано id особи для видалення
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL-запит для видалення особи
    $sql = "DELETE FROM Person WHERE id_person=$id";

    if ($conn->query($sql) === TRUE) {
        // Повідомлення про успішне видалення
        header("Location: pers.php");
    } else {
        // Повідомлення про помилку
        echo "Помилка: " . $conn->error;
    }

    // Закриття з'єднання з базою даних
    $conn->close();
} else {
    // Повідомлення про помилку, якщо не отримано id
    echo "Помилка: Не отримано ID для видалення";
}
?>
