<?php
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримуємо дані з форми
    $id = $_POST['id'];
    $name_med = $_POST['name_med'];
    $med_form = $_POST['med_form'];
    $dosage = $_POST['dosage'];
    $producer = $_POST['producer'];

    // SQL-запит для оновлення медикаменту
    $sql = "UPDATE Medicines SET name_med='$name_med', med_form='$med_form', dosage='$dosage', producer='$producer' WHERE id_med=$id";

    // Виконуємо SQL-запит
    if ($conn->query($sql) === TRUE) {
        header("Location: ../pages/med.php");
    } else {
        echo "Помилка оновлення даних про медикамент: " . $conn->error;
    }
}

// Закриваємо з'єднання з базою даних
$conn->close();
?>
