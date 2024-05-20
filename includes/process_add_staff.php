<?php
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Перевірка наявності всіх обов'язкових полів у формі
    if (!isset($_POST['id_dep'], $_POST['id_person'], $_POST['position'], $_POST['login'], $_POST['password'])) {
        echo "Помилка: Будь ласка, заповніть всі обов'язкові поля.";
        exit;
    }

    // Перевірка на коректність ID відділу та особи
    $id_dep = intval($_POST['id_dep']);
    $id_person = intval($_POST['id_person']);

    if ($id_dep <= 0 || $id_person <= 0) {
        echo "Помилка: Некоректні дані для ID відділу або особи.";
        exit;
    }

    // Перевірка на коректність позиції та логіна
    $position = $conn->real_escape_string($_POST['position']);
    $login = $conn->real_escape_string($_POST['login']);

    if (empty($position) || empty($login)) {
        echo "Помилка: Позиція та логін не можуть бути порожніми.";
        exit;
    }

    // Перевірка, чи особа вже в іншому відділі
    $check_person_sql = "SELECT * FROM Staff WHERE id_person = $id_person";
    $check_result = $conn->query($check_person_sql);

    if ($check_result->num_rows > 0) {
        echo "Помилка: Особа вже є в іншому відділі.";
    } else {
        // Вставка нового запису в таблицю Staff без хешування пароля
        $insert_staff_sql = "
            INSERT INTO Staff (id_dep, id_person, position, login, password)
            VALUES ($id_dep, $id_person, '$position', '$login', '$_POST[password]')
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
