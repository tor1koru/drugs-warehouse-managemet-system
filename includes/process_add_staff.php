<?php
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_dep = intval($_POST['id_dep']);
    $id_person = intval($_POST['id_person']);
    $position = $conn->real_escape_string($_POST['position']);

    // Вставка нового запису в таблицю Staff
    $insert_staff_sql = "
        INSERT INTO Staff (id_dep, id_person, position)
        VALUES ($id_dep, $id_person, '$position')
    ";

    if ($conn->query($insert_staff_sql) === TRUE) {
        // Повернення до списку відділів (med.php)
        header("Location: ../pages/dep.php");
    } else {
        echo "Помилка при додаванні персоналу: " . $conn->error;
    }

    // Закриття з'єднання з базою даних
    $conn->close();
} else {
    echo "Некоректний запит";
}
?>
