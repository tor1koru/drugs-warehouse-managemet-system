<?php
// Підключення до бази даних
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання даних з форми
    $name_med = $_POST['name_med'];
    $med_form = $_POST['med_form'];
    $dosage = $_POST['dosage'];
    $producer = $_POST['producer'];

    // Перевірка на однакові записи
    $check_sql = "SELECT * FROM Medicines WHERE name_med = '$name_med' AND med_form = '$med_form' AND dosage = '$dosage' AND producer = '$producer'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        echo "Такий медикамент вже існує.";
    } else {
        // SQL запит для вставки нового медикаменту
        $insert_sql = "INSERT INTO Medicines (name_med, med_form, dosage, producer) VALUES ('$name_med', '$med_form', '$dosage', '$producer')";

        if ($conn->query($insert_sql) === TRUE) {
            echo "Новий медикамент успішно додано.";
            // Повернення до сторінки зі списком медикаментів
            header("Location: ../pages/med.php");
            exit();
        } else {
            echo "Помилка: " . $insert_sql . "<br>" . $conn->error;
        }
    }

    // Закриття з'єднання з базою даних
    $conn->close();
}
?>
