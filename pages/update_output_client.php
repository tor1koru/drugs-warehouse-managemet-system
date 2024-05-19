<?php
// Підключення до бази даних та інші необхідні файли
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Перевірка, чи були передані обов'язкові дані
    if (isset($_POST['id']) && isset($_POST['count_to_person']) && isset($_POST['date_output_to_client'])) {
        $id = $_POST['id'];
        $count_to_person = $_POST['count_to_person'];
        $date_output_to_client = $_POST['date_output_to_client'];

        // Підготовлений SQL-запит для оновлення даних
        $sql = "UPDATE Output_to_client SET count_to_person = ?, date_output_to_client = ? WHERE id_output_client = ?";

        // Підготовка та виконання запиту
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $count_to_person, $date_output_to_client, $id);

        if ($stmt->execute()) {
            header("Location: ../pages/output_to_client_table.php");
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
