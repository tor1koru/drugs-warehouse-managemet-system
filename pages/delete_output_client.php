<?php
// Підключення до бази даних та інші необхідні файли
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Перевірка, чи був переданий параметр ID
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];

        // Підготовлений SQL-запит для видалення запису з бази даних
        $sql = "DELETE FROM Output_to_client WHERE id_output_client = ?";

        // Підготовка та виконання запиту
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: ../pages/output_to_client_table.php");
        } else {
            echo "Помилка при видаленні запису: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Не вказаний параметр ID.";
    }
} else {
    echo "Метод запиту повинен бути GET.";
}

$conn->close();
?>
