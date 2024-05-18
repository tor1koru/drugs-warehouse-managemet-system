<?php
include_once "../database/db_connection.php";

// Перевірка, чи переданий параметр id
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL-запит для видалення постачальника за вказаним id
    $sql = "DELETE FROM Providers WHERE id_provider = $id";

    if ($conn->query($sql) === TRUE) {
        // Після успішного видалення перенаправляємо користувача на сторінку providers.php
        header("Location: ../pages/providers.php");
        exit();
    } else {
        echo "Помилка при видаленні постачальника: " . $conn->error;
    }
} else {
    echo "Не передано параметр ID";
    exit();
}

$conn->close();
?>
