<?php
include_once "../database/db_connection.php";
session_start();
$id_dep = $_SESSION['id_dep'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання даних з форми
    if (isset($_POST['id_med_client'], $_SESSION['id_staff'], $_POST['id_person'], $_POST['count_to_person'])) {
        $id_med_client = $_POST['id_med_client'];
        $id_staff_client = $_SESSION['id_staff'];
        $id_person = $_POST['id_person'];
        $count_to_person = $_POST['count_to_person'];

        // Перевірка на порожні значення
        if (!empty($id_med_client) && !empty($id_staff_client) && !empty($id_person) && !empty($count_to_person)) {
            // Вставка даних в таблицю Output_to_client
            $sql = "INSERT INTO Output_to_client (id_med_client, id_staff_client, id_person, count_to_person, date_output_to_client)
                    VALUES ($id_med_client, $id_staff_client, $id_person, $count_to_person, CURRENT_DATE)";

            if ($conn->query($sql) === TRUE) {
                header("Location: ../pages/dep_storage.php?id_dep=" . $id_dep);
            } else {
                echo "<script>
                    alert('Помилка: " . $conn->error . "');
                    window.location.href = '../pages/dep_storage.php?id_dep=$id_dep';
                  </script>";
            }
        } else {
            echo "<script>
                    alert('Помилка: Одне або декілька значень надійшли порожніми. Перевірте дані, які ви вводите');
                    window.location.href = '../pages/dep_storage.php?id_dep=$id_dep';
                  </script>";
        }
    } else {
        echo "<script>
                    alert('Помилка: Не всі дані надійшли.');
                    window.location.href = '../pages/dep_storage.php?id_dep=$id_dep';
                  </script>";
    }
} else {
    echo "<script>
                    alert('Помилка: Неправильний метод запиту.');
                    window.location.href = '../pages/dep_storage.php?id_dep=$id_dep';
                  </script>";
}

// Закриття з'єднання з базою даних
$conn->close();
?>