<?php
// Підключення до бази даних
include_once "../database/db_connection.php";

// Перевірка, чи передано id_person через GET параметр
if (isset($_GET['id_person'])) {
    $id_person = intval($_GET['id_person']);

    // SQL запит для видалення запису з таблиці Staff за id_person
    $delete_sql = "DELETE FROM Staff WHERE id_person = $id_person";

    if ($conn->query($delete_sql) === TRUE) {
        header("Location: dep.php");
    } else {
        echo "Помилка видалення запису: " . $conn->error;
    }
} else {
    echo "Не вказано ID особи для видалення";
}

// Закриття з'єднання з базою даних
$conn->close();
?>
