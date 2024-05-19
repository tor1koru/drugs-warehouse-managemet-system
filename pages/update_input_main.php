<?php
// Підключення до бази даних та інші необхідні файли
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Перевірка, чи були передані обов'язкові дані
    if (isset($_POST['id']) && isset($_POST['count']) && isset($_POST['date_input'])) {
        $id = $_POST['id'];
        $count = $_POST['count'];
        $date_input = $_POST['date_input'];

        // Підготовлений SQL-запит для оновлення даних
        $sql = "UPDATE Input_to_main SET count = ?, date_input = ? WHERE id_input_to_main = ?";

        // Підготовка та виконання запиту
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $count, $date_input, $id);

        if ($stmt->execute()) {
            header("Location: ../pages/input_to_main_table.php");
        } else {
            echo "Помилка при оновленні даних: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Не вдалося отримати всі необхідні дані.";
    }
} else {
    echo "Метод запиту повинен бути POST.";
}

$conn->close();
?>
