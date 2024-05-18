<?php
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання даних з форми
    $id_med_client = $_POST['id_med_client'];
    $id_staff_client = $_POST['id_staff_client'];
    $id_person = $_POST['id_person'];
    $count_to_person = $_POST['count_to_person'];

    // Перевірка чи всі дані надійшли
    if (isset($id_med_client, $id_staff_client, $id_person, $count_to_person)) {
        // Вставка даних в таблицю Output_to_client
        $sql = "INSERT INTO Output_to_client (id_med_client, id_staff_client, id_person, count_to_person, date_output_to_client)
                VALUES ($id_med_client, $id_staff_client, $id_person, $count_to_person, CURRENT_DATE)";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../pages/home.php");
        } else {
            echo "Помилка: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Не всі дані надійшли.";
    }
} else {
    echo "Неправильний метод запиту.";
}

// Закриття з'єднання з базою даних
$conn->close();
?>
