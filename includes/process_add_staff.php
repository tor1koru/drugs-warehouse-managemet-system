<?php
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_dep = intval($_POST['id_dep']);
    $id_person = intval($_POST['id_person']);
    $position = $conn->real_escape_string($_POST['position']);
    $login = $conn->real_escape_string($_POST['login']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT);

    // Перевірка, чи особа вже в іншому відділі
    $check_person_sql = "SELECT * FROM Staff WHERE id_person = $id_person";
    $check_result = $conn->query($check_person_sql);

    if ($check_result->num_rows > 0) {
        echo "Помилка: Особа вже є в іншому відділі.";
    } else {
        // Вставка нового запису в таблицю Staff
        $insert_staff_sql = "
            INSERT INTO Staff (id_dep, id_person, position, login, password)
            VALUES ($id_dep, $id_person, '$position', '$login', '$password')
        ";

        if ($conn->query($insert_staff_sql) === TRUE) {
            // Повернення до списку відділів (med.php)
            header("Location: ../pages/dep.php");
        } else {
            echo "Помилка при додаванні персоналу: " . $conn->error;
        }
    }

    // Закриття з'єднання з базою даних
    $conn->close();
} else {
    echo "Некоректний запит";
}
?>
