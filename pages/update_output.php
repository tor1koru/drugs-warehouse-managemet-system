<?php
// Підключення до бази даних та інші необхідні файли
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Перевірка, чи були передані обов'язкові дані
    if (isset($_POST['id']) && isset($_POST['count']) && isset($_POST['date_output'])) {
        $id = $_POST['id'];
        $count = $_POST['count'];
        $date_output = $_POST['date_output'];

        // Підготовлений SQL-запит для оновлення даних
        $sql = "UPDATE Output_from_main SET count = ?, date_output = ? WHERE id_output = ?";

        // Підготовка та виконання запиту
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $count, $date_output, $id);

        if ($stmt->execute()) {
            header("Location: ../pages/output_from_main_table.php");
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
